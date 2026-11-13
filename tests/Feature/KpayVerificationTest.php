<?php

namespace Tests\Feature;

use App\Models\Lipstick;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KpayVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        config([
            'services.gemini.key' => '',
            'studio.payment_name' => 'Ma Shwe Chaw',
        ]);
    }

    private function createShade(int $stock = 10, int $price = 30000): Lipstick
    {
        $product = Product::create([
            'name' => 'Zero Matte Lipstick',
            'brand' => 'rom&nd',
            'product_type' => 'lipstick',
            'category' => 'Velvet & Matte',
            'finish' => 'matte',
        ]);

        return Lipstick::create([
            'product_id' => $product->id,
            'name' => 'Zero Matte Lipstick - Red',
            'shade_name' => 'Red',
            'category' => 'Velvet & Matte',
            'stock' => $stock,
            'price' => $price,
            'verified' => true,
        ]);
    }

    private function cartFor(Lipstick $shade, int $price = 30000): array
    {
        return [
            sha1((string) $shade->id) => [
                'db_id' => $shade->id,
                'id' => $shade->id,
                'name' => 'Zero Matte Lipstick',
                'shade' => 'Red',
                'quantity' => 1,
                'price' => $price,
                'image' => '',
            ],
        ];
    }

    public function test_kpay_matched_slip_auto_approves_order(): void
    {
        $shade = $this->createShade(10, 30000);
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('kpay_receipt_match.png', 100, 'image/png');

        $response = $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade)])
            ->post('/order/confirm', [
                'fullname' => 'Daw Aye',
                'phone' => '09123456789',
                'address' => 'Kamayut, Yangon',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertNotNull($order);

        $this->assertSame('Processing', $order->status);
        $this->assertSame('Paid', $order->payment_status);
        $this->assertSame('matched', $order->slip_verification_status);
        $this->assertStringContainsString('Auto-Verified', $order->slip_verification_notes);

        $response->assertRedirect('/orders/' . $order->id);
    }

    public function test_kpay_mismatched_amount_holds_order_and_alerts_admin(): void
    {
        $shade = $this->createShade(10, 30000);
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('kpay_slip_mismatch.png', 100, 'image/png');

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade)])
            ->post('/order/confirm', [
                'fullname' => 'Mg Ba',
                'phone' => '09987654321',
                'address' => 'Mandalay',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertNotNull($order);

        $this->assertSame('Pending', $order->status);
        $this->assertSame('Under Review', $order->payment_status);
        $this->assertSame('mismatch', $order->slip_verification_status);
        $this->assertStringContainsString('ငွေမပြည့်ပါ', $order->slip_verification_notes);
    }

    public function test_kpay_duplicate_trans_id_alerts_fraud_and_holds_order(): void
    {
        $shade = $this->createShade(10, 30000);
        $user = User::factory()->create();

        Order::create([
            'user_id' => $user->id,
            'fullname' => 'Old Customer',
            'phone' => '09111111111',
            'address' => 'Yangon',
            'total_price' => 30000,
            'status' => 'Completed',
            'payment_method' => 'kpay',
            'payment_status' => 'Paid',
            'transaction_id' => '20261110001234567890',
        ]);

        $file = UploadedFile::fake()->create('kpay_slip_duplicate.png', 100, 'image/png');

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade)])
            ->post('/order/confirm', [
                'fullname' => 'Copycat Customer',
                'phone' => '09222222222',
                'address' => 'Naypyidaw',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertNotNull($order);

        $this->assertSame('Pending', $order->status);
        $this->assertSame('Under Review', $order->payment_status);
        $this->assertSame('duplicate', $order->slip_verification_status);
        $this->assertStringContainsString('Fraud Alert', $order->slip_verification_notes);
    }

    public function test_unknown_slip_without_api_key_fails_closed_for_admin_review(): void
    {
        $shade = $this->createShade(10, 30000);
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('random_screenshot.jpg', 100, 'image/jpeg');

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade)])
            ->post('/order/confirm', [
                'fullname' => 'Test User',
                'phone' => '09333333333',
                'address' => 'Yangon',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertSame('Pending', $order->status);
        $this->assertSame('Under Review', $order->payment_status);
        $this->assertSame('unreadable', $order->slip_verification_status);
    }

    public function test_gemini_wrong_amount_holds_order_and_alerts_admin(): void
    {
        config(['services.gemini.key' => 'test-gemini-key']);
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'is_kpay_receipt' => true,
                                'transaction_id' => '01004325091504245039',
                                'amount' => 5000,
                            ]),
                        ]],
                    ],
                ]],
            ], 200),
        ]);

        $shade = $this->createShade(10, 21000);
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('Screenshot_2026.jpg', 100, 'image/jpeg');

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade, 21000)])
            ->post('/order/confirm', [
                'fullname' => 'Real Slip User',
                'phone' => '09444444444',
                'address' => 'Yangon',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertSame('Pending', $order->status);
        $this->assertSame('Under Review', $order->payment_status);
        $this->assertSame('mismatch', $order->slip_verification_status);
        $this->assertSame(5000, $order->slip_detected_amount);
        $this->assertSame('01004325091504245039', $order->transaction_id);
    }

    public function test_gemini_api_failure_never_auto_approves_order(): void
    {
        config(['services.gemini.key' => 'test-gemini-key']);
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(['error' => ['message' => 'Service down']], 503),
        ]);

        $shade = $this->createShade(10, 21000);
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('Screenshot_2026.jpg', 100, 'image/jpeg');

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade, 21000)])
            ->post('/order/confirm', [
                'fullname' => 'Outage User',
                'phone' => '09555555555',
                'address' => 'Yangon',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertSame('Pending', $order->status);
        $this->assertSame('Under Review', $order->payment_status);
        $this->assertSame('unreadable', $order->slip_verification_status);
    }

    public function test_gemini_matched_amount_and_recipient_name_auto_approves_order(): void
    {
        config(['services.gemini.key' => 'test-gemini-key']);
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'is_kpay_receipt' => true,
                                'transaction_id' => '01004325091504249999',
                                'amount' => 21000,
                                'receiver_name' => 'Ma Shwe Chaw (******1234)',
                            ]),
                        ]],
                    ],
                ]],
            ], 200),
        ]);

        $shade = $this->createShade(10, 21000);
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('Screenshot_match.jpg', 100, 'image/jpeg');

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade, 21000)])
            ->post('/order/confirm', [
                'fullname' => 'Verified Buyer',
                'phone' => '09666666666',
                'address' => 'Yangon',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertSame('Processing', $order->status);
        $this->assertSame('Paid', $order->payment_status);
        $this->assertSame('matched', $order->slip_verification_status);
        $this->assertSame('Ma Shwe Chaw', $order->slip_detected_recipient);
        $this->assertStringContainsString('Ma Shwe Chaw', $order->slip_verification_notes);
    }

    public function test_gemini_wrong_recipient_with_same_amount_and_new_trans_id_holds_order(): void
    {
        config(['services.gemini.key' => 'test-gemini-key']);
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'is_kpay_receipt' => true,
                                'transaction_id' => '01004325091504248888',
                                'amount' => 21000,
                                'receiver_name' => 'U Phyo Khant Kywal (******6226)',
                            ]),
                        ]],
                    ],
                ]],
            ], 200),
        ]);

        $shade = $this->createShade(10, 21000);
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('Screenshot_wrong_person.jpg', 100, 'image/jpeg');

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade, 21000)])
            ->post('/order/confirm', [
                'fullname' => 'Wrong Person Buyer',
                'phone' => '09777777777',
                'address' => 'Yangon',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertSame('Pending', $order->status);
        $this->assertSame('Under Review', $order->payment_status);
        $this->assertSame('recipient_mismatch', $order->slip_verification_status);
        $this->assertSame('U Phyo Khant Kywal', $order->slip_detected_recipient);
        $this->assertStringContainsString('Ma Shwe Chaw', $order->slip_verification_notes);
        $this->assertStringContainsString('U Phyo Khant Kywal', $order->slip_verification_notes);
    }

    public function test_gemini_missing_recipient_name_fails_closed(): void
    {
        config(['services.gemini.key' => 'test-gemini-key']);
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'is_kpay_receipt' => true,
                                'transaction_id' => '01004325091504247777',
                                'amount' => 21000,
                                'receiver_name' => null,
                            ]),
                        ]],
                    ],
                ]],
            ], 200),
        ]);

        $shade = $this->createShade(10, 21000);
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('Screenshot_cropped.jpg', 100, 'image/jpeg');

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade, 21000)])
            ->post('/order/confirm', [
                'fullname' => 'Cropped Slip Buyer',
                'phone' => '09888888888',
                'address' => 'Yangon',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertSame('Pending', $order->status);
        $this->assertSame('Under Review', $order->payment_status);
        $this->assertSame('recipient_mismatch', $order->slip_verification_status);
        $this->assertNull($order->slip_detected_recipient);
    }

    public function test_demo_wrong_recipient_filename_holds_order(): void
    {
        $shade = $this->createShade(10, 30000);
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('kpay_wrong_recipient.png', 100, 'image/png');

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartFor($shade)])
            ->post('/order/confirm', [
                'fullname' => 'Demo Wrong Recipient',
                'phone' => '09999999999',
                'address' => 'Yangon',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertSame('Pending', $order->status);
        $this->assertSame('Under Review', $order->payment_status);
        $this->assertSame('recipient_mismatch', $order->slip_verification_status);
    }
}
