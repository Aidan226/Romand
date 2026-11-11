@extends('admin.layouts.app')
@section('title','Dashboard')
@section('content')
<div class="grid">
    <div class="card stat"><span>PRODUCT / SHADE ROWS</span><b>{{ $productCount }}</b><span class="small">Stored in MySQL</span></div>
    <div class="card stat"><span>TOTAL STOCK</span><b>{{ $totalStock }}</b><span class="small">Available units</span></div>
    <div class="card stat"><span>ORDERS</span><b>{{ $orderCount }}</b><span class="small">{{ $pendingOrders }} pending</span></div>
    <div class="card stat"><span>CUSTOMERS</span><b>{{ $customerCount }}</b><span class="small">Registered shoppers</span></div>
</div>

<div class="section dash-grid">
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:12px;"><div><h3 style="margin-bottom:4px;">Recent Orders</h3><span class="small">Latest customer purchases</span></div><a class="btn muted" href="{{ route('admin.orders.index') }}">View all</a></div>
        <div style="overflow:auto;"><table><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead><tbody>
        @forelse($recentOrders as $order)<tr><td>#{{ $order->id }}</td><td>{{ $order->fullname }}</td><td>{{ number_format($order->total_price) }} MMK</td><td><span class="badge">{{ $order->status }}</span></td></tr>@empty<tr><td colspan="4">No orders yet.</td></tr>@endforelse
        </tbody></table></div>
    </div>
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;"><div><h3 style="margin-bottom:4px;">Low Stock</h3><span class="small">5 units or fewer</span></div><a class="btn muted" href="{{ route('admin.products.index') }}">Manage</a></div>
        @forelse($lowStock as $item)<div style="display:flex;justify-content:space-between;gap:12px;padding:11px 0;border-bottom:1px solid #f4e9ed;"><span>{{ $item->name }}</span><span class="low">{{ $item->stock }}</span></div>@empty<p class="small">Everything is sufficiently stocked.</p>@endforelse
    </div>
</div>

<div class="section card" style="background:linear-gradient(135deg,#fff0f5,#fff9fb);">
    <h3 style="margin-bottom:7px;">Store tools</h3>
    <p class="small" style="font-size:14px;margin-top:0;">Manage products, stock, orders, customers and customer promotions from one place.</p>
    <div class="actions"><a class="btn primary" href="{{ route('admin.products.index') }}">Manage Products</a><a class="btn muted" href="{{ route('admin.promotions.index') }}">Manage Promotions</a><a class="btn muted" href="{{ route('home') }}">Open Store</a></div>
</div>
@endsection
