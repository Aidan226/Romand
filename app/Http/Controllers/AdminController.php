<?php

namespace App\Http\Controllers;

use App\Models\Lipstick;
use App\Models\Order;
use App\Models\User;
use App\Models\Promotion;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private function productCategories(): array
    {
        $defaults = ['Glossy & Dewy', 'Velvet & Matte', 'Balm & Care'];
        $existing = Lipstick::query()->whereNotNull('category')->distinct()->pluck('category')->all();
        return array_values(array_unique(array_merge($defaults, $existing)));
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'productCount' => Lipstick::count(),
            'totalStock' => Lipstick::sum('stock'),
            'orderCount' => Order::count(),
            'pendingOrders' => Order::where('status', 'Pending')->count(),
            'customerCount' => User::where('is_admin', false)->count(),
            'recentOrders' => Order::with('items')->latest()->take(5)->get(),
            'lowStock' => Lipstick::where('stock', '<=', 5)->orderBy('stock')->take(8)->get(),
        ]);
    }

    public function index()
    {
        $lipsticks = Lipstick::orderBy('id')->get();
        $categories = $this->productCategories();
        return view('admin.lipsticks.index', compact('product_shades', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_shades,name',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:4096',
            'shade_hex' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move(public_path('images/uploads'), $imageName);
            $imageName = 'uploads/' . $imageName;
        }

        Lipstick::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
            'shade_hex' => strtoupper($request->shade_hex),
        ]);

        return back()->with('success', 'Product / shade added successfully.');
    }

    public function update(Request $request, $id)
    {
        $lipstick = Lipstick::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:product_shades,name,' . $lipstick->id,
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:4096',
            'shade_hex' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        if (!empty($data['shade_hex'])) {
            $data['shade_hex'] = strtoupper($data['shade_hex']);
        } else {
            unset($data['shade_hex']);
        }

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move(public_path('images/uploads'), $imageName);
            $data['image'] = 'uploads/' . $imageName;
        } else {
            unset($data['image']);
        }

        $lipstick->update($data);
        return back()->with('success', 'Product updated successfully.');
    }

    public function updateStock(Request $request, $id)
    {
        $data = $request->validate(['stock' => 'required|integer|min:0']);
        Lipstick::findOrFail($id)->update($data);
        return back()->with('success', 'Stock updated successfully.');
    }

    public function destroy($id)
    {
        Lipstick::findOrFail($id)->delete();
        return back()->with('success', 'Product deleted successfully.');
    }

    public function orders()
    {
        $orders = Order::with(['items', 'user'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:Pending,Processing,Shipped,Completed,Cancelled',
        ]);
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->update($data);

        if ($order->user_id && $oldStatus !== $order->status) {
            UserNotification::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'title' => 'Order #' . $order->id . ' updated',
                'message' => 'Your order status changed from ' . $oldStatus . ' to ' . $order->status . '.',
            ]);
        }

        return back()->with('success', 'Order status updated successfully.');
    }


    public function promotions()
    {
        $promotions = Promotion::latest()->get();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function storePromotion(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'discount_percent' => 'required|numeric|min:0.01|max:100',
            'minimum_order' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        Promotion::create($data);
        return back()->with('success', 'Promotion created successfully.');
    }

    public function updatePromotion(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'discount_percent' => 'required|numeric|min:0.01|max:100',
            'minimum_order' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $promotion->update($data);
        return back()->with('success', 'Promotion updated successfully.');
    }

    public function destroyPromotion(Promotion $promotion)
    {
        $promotion->delete();
        return back()->with('success', 'Promotion deleted.');
    }

    public function customers()
    {
        $customers = User::where('is_admin', false)
            ->withCount('orders')
            ->latest()
            ->get();
        return view('admin.customers.index', compact('customers'));
    }
}
