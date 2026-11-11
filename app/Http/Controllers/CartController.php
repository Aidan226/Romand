<?php

namespace App\Http\Controllers;

use App\Models\Lipstick;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promotion;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        return view('cart', $this->calculateCart());
    }

    private function baseName(Lipstick $row): string
    {
        return $row->product->name;
    }

    private function shadeName(Lipstick $row): string
    {
        return $row->shade_name;
    }

    private function productRows(Lipstick $row)
    {
        return Lipstick::where('product_id', $row->product_id)->orderBy('id')->get();
    }

    private function findShadeRow(Lipstick $productRow, string $shade): ?Lipstick
    {
        return $this->productRows($productRow)
            ->first(fn ($row) => $this->shadeName($row) === $shade);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:product_shades,id'],
            'shade_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $productRow = Lipstick::findOrFail($validated['product_id']);
        $shadeRow = $this->findShadeRow($productRow, $validated['shade_name']);

        if (!$shadeRow) {
            return back()->withErrors(['shade_name' => 'Please select a valid shade.'])->withInput();
        }

        $baseName = $this->baseName($productRow);
        $shadeName = $this->shadeName($shadeRow);
        $availableStock = (int) $shadeRow->stock;
        $cart = session()->get('cart', []);
        $key = sha1((string) $shadeRow->id);
        $existingQuantity = $cart[$key]['quantity'] ?? 0;
        $totalRequestedQty = $existingQuantity + $validated['quantity'];

        if ($totalRequestedQty > $availableStock) {
            return back()->with('error', 'Requested quantity exceeds available stock. Only ' . $availableStock . ' item(s) left for ' . $shadeName . '.');
        }

        $configProduct = collect(config('products'))->firstWhere('name', $baseName);
        $configShade = collect($configProduct['shades'] ?? [])->firstWhere('name', $shadeName);
        $image = $shadeRow->image ?: ($configShade['image'] ?? ($configProduct['image'] ?? 'default-lipstick.jpg'));

        $cart[$key] = [
            'db_id' => $shadeRow->id,
            'id' => $productRow->id,
            'name' => $baseName,
            'image' => $image,
            'shade' => $shadeName,
            'price' => (float) $shadeRow->price,
            'quantity' => $totalRequestedQty,
        ];

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', $baseName . ' was added to your cart.');
    }

    public function update(Request $request, string $key)
    {
        $validated = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:50']]);
        $cart = session()->get('cart', []);
        abort_unless(Arr::has($cart, $key), 404);

        $item = $cart[$key];
        $lipstick = isset($item['db_id']) ? Lipstick::find($item['db_id']) : null;
        if (!$lipstick) {
            $lipstick = Lipstick::where('name', $item['name'] . ' - ' . $item['shade'])->first()
                ?: Lipstick::where('name', $item['name'])->first();
        }

        $availableStock = $lipstick ? (int) $lipstick->stock : 0;
        if ($validated['quantity'] > $availableStock) {
            return back()->with('error', 'Only ' . $availableStock . ' item(s) left in stock.');
        }

        $cart[$key]['quantity'] = $validated['quantity'];
        session()->put('cart', $cart);
        return back()->with('success', 'Cart quantity updated.');
    }

    public function remove(string $key)
    {
        $cart = session()->get('cart', []);
        abort_unless(Arr::has($cart, $key), 404);
        unset($cart[$key]);
        session()->put('cart', $cart);
        return back()->with('success', 'Item removed from your cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Your cart has been cleared.');
    }

    public function checkout()
    {
        if (empty(session()->get('cart', []))) {
            return redirect()->route('cart.index')->with('error', 'Add at least one item before checkout.');
        }
        return view('checkout', $this->calculateCart());
    }

    public function confirmOrder(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|in:kpay,cod',
            'payment_screenshot' => 'required_if:payment_method,kpay|nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        $paymentScreenshotPath = null;
        if ($request->payment_method === 'kpay' && $request->hasFile('payment_screenshot')) {
            $paymentScreenshotPath = $request->file('payment_screenshot')->store('payment-proofs', 'local');
            if (!$paymentScreenshotPath) throw new \RuntimeException('Unable to save payment proof.');
        }

        $order = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $cart, $paymentScreenshotPath) {
        $ids = array_column($cart, 'db_id');
        $locked = Lipstick::whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        foreach ($cart as &$item) {
            $row = $locked->get($item['db_id']);
            if (!$row || $row->stock < $item['quantity']) throw \Illuminate\Validation\ValidationException::withMessages(['stock'=>'Stock changed. Please update your cart.']);
            $item['price'] = (float) $row->price;
        }
        unset($item);
        session()->put('cart', $cart);
        $totals = $this->calculateCart();
        $order = Order::create([
            'user_id' => Auth::id(),
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'address' => $request->address,
            'subtotal' => (int) round($totals['subtotal']),
            'discount_amount' => (int) round($totals['discount_amount']),
            'promotion_name' => $totals['promotion']?->name,
            'total_price' => (int) round($totals['final_total']),
            'payment_method' => $request->payment_method,
            'payment_provider' => $request->payment_method === 'kpay' ? 'KBZPay' : 'Cash on Delivery',
            'payment_status' => $request->payment_method === 'kpay' ? 'Submitted' : 'Pending',
            'payment_screenshot' => $paymentScreenshotPath,
            'status' => 'Pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'shade' => $item['shade'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            $lipstick = Lipstick::find($item['db_id']);
            if ($lipstick) {
                $lipstick->decrement('stock', $item['quantity']);
            }
        }

        // Automatic KPay Slip Verification (Exception-Based)
        $isAutoApproved = false;
        if ($request->payment_method === 'kpay' && $paymentScreenshotPath) {
            try {
                $originalName = $request->file('payment_screenshot')?->getClientOriginalName();
                $verification = app(\App\Services\KpaySlipVerifier::class)->verify($order, $paymentScreenshotPath, $originalName);
                $isAutoApproved = !empty($verification['auto_approved']);

                $order->update([
                    'status' => $isAutoApproved ? 'Processing' : 'Pending',
                    'payment_status' => $isAutoApproved ? 'Paid' : 'Under Review',
                    'transaction_id' => $verification['transaction_id'] ?? null,
                    'slip_detected_amount' => $verification['detected_amount'] ?? null,
                    'slip_verification_status' => $verification['status'] ?? null,
                    'slip_verification_notes' => $verification['notes'] ?? null,
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('KPay verification failed: ' . $e->getMessage());
            }
        }

        if ($isAutoApproved) {
            UserNotification::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'title' => 'Order #' . $order->id . ' Verified & Processing',
                'message' => 'Your payment of ' . number_format($order->total_price) . ' MMK was automatically verified via KBZPay. Your order is now processing.',
            ]);
        } else {
            UserNotification::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'title' => 'Order #' . $order->id . ' is Pending',
                'message' => 'Your order was placed on ' . $order->created_at->timezone('Asia/Yangon')->format('d M Y, h:i A') . ' and is waiting for confirmation.',
            ]);
        }

        return $order;
        });
        session()->forget('cart');

        $successMsg = ($order->payment_method === 'kpay' && $order->status === 'Processing')
            ? 'Order placed and payment automatically verified! Your order is now Processing.'
            : 'Order placed successfully. Your order is currently Pending.';

        return redirect()->route('orders.show', $order)->with('success', $successMsg);
    }

    private function calculateCart(): array
    {
        $cart = session()->get('cart', []);
        foreach ($cart as &$item) {
            $row = Lipstick::find($item['db_id'] ?? null);
            if ($row) $item['price'] = (float) $row->price;
        }
        unset($item);
        session()->put('cart',$cart);
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        $promotion = Promotion::currentlyActive()
            ->where('minimum_order', '<=', $subtotal)
            ->orderByDesc('discount_percent')
            ->first();

        $discount_applied = (bool) $promotion;
        $discount_amount = $promotion ? ($subtotal * ($promotion->effective_discount_percent / 100)) : 0;
        $final_total = max(0, $subtotal - $discount_amount);

        return compact('cart', 'subtotal', 'promotion', 'discount_applied', 'discount_amount', 'final_total');
    }
}
