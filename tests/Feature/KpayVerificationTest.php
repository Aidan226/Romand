<?php

namespace Tests\Feature;

use App\Models\Lipstick;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KpayVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
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

    public function test_kpay_matched_slip_auto_approves_order(): void
    {
        $shade = $this->createShade(10, 30000);
        $user = User::factory()->create();

        $cart = [
            sha1((string) $shade->id) => [
                'db_id' => $shade->id,
                'id' => $shade->id,
                'name' => 'Zero Matte Lipstick',
                'shade' => 'Red',
                'quantity' => 1,
                'price' => 30000,
                'image' => '',
            ]
        ];

        // Valid test receipt image
        $file = UploadedFile::fake()->create('kpay_receipt_match.png', 100, 'image/png');

        $response = $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post('/order/confirm', [
                'fullname' => 'Daw Aye',
                'phone' => '09123456789',
                'address' => 'Kamayut, Yangon',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest()->first();
        $this->assertNotNull($order);

        // Verification: When matched, it is AUTO-APPROVED!
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

        $cart = [
            sha1((string) $shade->id) => [
                'db_id' => $shade->id,
                'id' => $shade->id,
                'name' => 'Zero Matte Lipstick',
                'shade' => 'Red',
                'quantity' => 1,
                'price' => 30000,
                'image' => '',
            ]
        ];

        // Mismatch image triggers underpaid scenario
        $file = UploadedFile::fake()->create('kpay_slip_mismatch.png', 100, 'image/png');

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post('/order/confirm', [
                'fullname' => 'Mg Ba',
                'phone' => '09987654321',
                'address' => 'Mandalay',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertNotNull($order);

        // Verification: Exception held in Pending, flagged for admin review
        $this->assertSame('Pending', $order->status);
        $this->assertSame('Under Review', $order->payment_status);
        $this->assertSame('mismatch', $order->slip_verification_status);
        $this->assertStringContainsString('ငွေမပြည့်ပါ', $order->slip_verification_notes);
    }

    public function test_kpay_duplicate_trans_id_alerts_fraud_and_holds_order(): void
    {
        $shade = $this->createShade(10, 30000);
        $user = User::factory()->create();

        // Previous existing order with a specific transaction ID
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

        $cart = [
            sha1((string) $shade->id) => [
                'db_id' => $shade->id,
                'id' => $shade->id,
                'name' => 'Zero Matte Lipstick',
                'shade' => 'Red',
                'quantity' => 1,
                'price' => 30000,
                'image' => '',
            ]
        ];

        // Duplicate image triggers duplicate trans_id check
        $file = UploadedFile::fake()->create('kpay_slip_duplicate.png', 100, 'image/png');

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post('/order/confirm', [
                'fullname' => 'Copycat Customer',
                'phone' => '09222222222',
                'address' => 'Naypyidaw',
                'payment_method' => 'kpay',
                'payment_screenshot' => $file,
            ]);

        $order = Order::latest('id')->first();
        $this->assertNotNull($order);

        // Verification: Fraud alert held in Pending, duplicate Trans ID flagged
        $this->assertSame('Pending', $order->status);
        $this->assertSame('duplicate', $order->slip_verification_status);
        $this->assertStringContainsString('Fraud Alert', $order->slip_verification_notes);
    }
}
