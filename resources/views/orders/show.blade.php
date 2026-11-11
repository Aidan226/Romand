@extends('v2.layout')
@section('content')
<div class="container" style="max-width:900px;margin:40px auto;">
<h2>Order #{{ $order->id }}</h2>
<div style="background:#fff;border:1px solid #f0dfe5;border-radius:14px;padding:20px;">
<p><strong>Ordered:</strong> {{ $order->created_at->timezone('Asia/Yangon')->format('d M Y, h:i A') }}</p>
<p><strong>Status:</strong> <span style="font-weight:600; color:{{ $order->status === 'Processing' ? '#137333' : '#333' }}">{{ $order->status }}</span></p>
<p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }} ({{ $order->payment_status }})</p>
@if($order->payment_method === 'kpay')
    @if($order->slip_verification_status === 'matched')
        <p style="color:#137333; font-weight:600; font-size:14px;">
            🟢 Payment Automatically Verified ({{ number_format($order->slip_detected_amount ?? $order->total_price) }} MMK)
        </p>
    @elseif($order->slip_verification_status === 'mismatch' || $order->slip_verification_status === 'duplicate')
        <div style="background:#fce8e6; border:1px solid #fad2cf; color:#c5221f; padding:10px 14px; border-radius:8px; margin:10px 0; font-size:13px;">
            <strong>⚠️ Payment Notice:</strong> {{ $order->slip_verification_notes }}
        </div>
    @endif
@endif
<p><strong>Shipping:</strong> {{ $order->fullname }} · {{ $order->phone }}<br>{{ $order->address }}</p>
<hr>
@foreach($order->items as $item)<p>{{ $item->product_name }} — {{ $item->shade }} × {{ $item->quantity }} <strong style="float:right;">{{ number_format($item->price * $item->quantity) }} MMK</strong></p>@endforeach
<hr>
<p>Subtotal: {{ number_format($order->subtotal ?? ($order->total_price + ($order->discount_amount ?? 0))) }} MMK</p>
@if(($order->discount_amount ?? 0) > 0)<p>{{ $order->promotion_name ?? 'Promotion' }}: -{{ number_format($order->discount_amount) }} MMK</p>@endif
@if($order->payment_screenshot)
<p><a href="{{ route('orders.payment-proof',$order) }}" target="_blank" rel="noopener">View my payment proof</a></p>
@endif
<h3>Total: {{ number_format($order->total_price) }} MMK</h3>
</div></div>
@endsection
