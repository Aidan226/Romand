@extends('admin.layouts.app')
@section('title','Order Management')
@section('content')

@php
    $alertOrders = $orders->filter(function($o) {
        return in_array($o->slip_verification_status, ['mismatch', 'duplicate', 'unreadable']) && $o->status === 'Pending';
    });
@endphp

@if($alertOrders->isNotEmpty())
    <div style="background:#fce8e6; border:1px solid #fad2cf; color:#c5221f; padding:12px 16px; border-radius:8px; margin-bottom:18px; font-size:13px; display:flex; align-items:center; justify-content:space-between;">
        <div>
            <strong>⚠️ ACTION REQUIRED: {{ $alertOrders->count() }} Payment Exception(s) Detected!</strong><br>
            <span>Some KPay receipts have amount mismatches, duplicate transaction numbers, or unreadable details. Please review them below.</span>
        </div>
    </div>
@else
    <div style="background:#e6f4ea; border:1px solid #ceead6; color:#137333; padding:10px 16px; border-radius:8px; margin-bottom:18px; font-size:13px;">
        <strong>✅ Exception-Based Automation Active:</strong> All orders with valid matching KPay slips are automatically approved and processing. Zero manual check needed unless an anomaly is detected.
    </div>
@endif

<div class="table-wrap">
<table>
<thead>
<tr>
    <th>Order</th>
    <th>Customer</th>
    <th>Items</th>
    <th>Total & Payment</th>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
@forelse($orders as $order)
<tr style="{{ in_array($order->slip_verification_status, ['mismatch', 'duplicate']) && $order->status === 'Pending' ? 'background: #fff8f7;' : '' }}">
    <td>
        <b>#{{ $order->id }}</b>
    </td>
    <td>
        <b>{{ $order->fullname }}</b>
        <div class="small">{{ $order->phone }}<br>{{ $order->address }}<br>{{ $order->user?->email }}</div>
    </td>
    <td>
        @forelse($order->items as $item)
            <div>{{ $item->product_name }} @if($item->shade) - {{ $item->shade }} @endif × {{ $item->quantity }}</div>
        @empty
            <span class="small">No items</span>
        @endforelse
    </td>
    <td>
        <b>{{ number_format($order->total_price) }} MMK</b><br>
        <span class="small" style="color:#5f6368;">Method: {{ strtoupper($order->payment_method) }}</span>

        @if($order->payment_method === 'kpay')
            @if($order->slip_verification_status === 'matched')
                <div style="margin-top:6px;">
                    <span style="display:inline-block; background:#e6f4ea; color:#137333; font-size:11px; font-weight:700; padding:2px 8px; border-radius:4px; border:1px solid #ceead6;">
                        🟢 Auto-Verified: {{ number_format($order->slip_detected_amount ?? $order->total_price) }} Ks
                    </span>
                    @if($order->transaction_id)
                        <div class="small" style="color:#5f6368; font-size:10px; margin-top:2px;">
                            Trans ID: {{ $order->transaction_id }}
                        </div>
                    @endif
                </div>
            @elseif($order->slip_verification_status === 'mismatch')
                <div style="margin-top:6px; background:#fce8e6; border:1px solid #fad2cf; color:#c5221f; padding:6px 8px; border-radius:6px; font-size:11px; text-align:left;">
                    <strong style="display:block; margin-bottom:2px;">🔴 ALERT: Amount Mismatch!</strong>
                    <span>{{ $order->slip_verification_notes }}</span>
                </div>
            @elseif($order->slip_verification_status === 'duplicate')
                <div style="margin-top:6px; background:#fef7e0; border:1px solid #feefc3; color:#b06000; padding:6px 8px; border-radius:6px; font-size:11px; text-align:left;">
                    <strong style="display:block; margin-bottom:2px;">⚠️ FRAUD ALERT: Duplicate Slip!</strong>
                    <span>{{ $order->slip_verification_notes }}</span>
                </div>
            @elseif($order->slip_verification_status === 'unreadable')
                <div style="margin-top:6px; background:#f1f3f4; border:1px solid #dadce0; color:#3c4043; padding:6px 8px; border-radius:6px; font-size:11px; text-align:left;">
                    <strong style="display:block; margin-bottom:2px;">❓ Check Needed: Unreadable Slip</strong>
                    <span>{{ $order->slip_verification_notes }}</span>
                </div>
            @else
                <div class="small" style="margin-top:4px; color:#5f6368;">Payment: {{ $order->payment_status }}</div>
            @endif

            @if($order->payment_screenshot)
                <div style="margin-top:6px;">
                    <a target="_blank" rel="noopener" href="{{ route('orders.payment-proof',$order) }}" style="font-size:11px; text-decoration:underline;">
                        🔍 View payment slip
                    </a>
                </div>
            @endif
        @else
            <div class="small" style="margin-top:2px;">Status: {{ $order->payment_status }}</div>
        @endif
    </td>
    <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
    <td>
        <form method="POST" action="{{ route('admin.orders.status',$order->id) }}">
            @csrf
            @method('PATCH')
            <select name="status" onchange="this.form.submit()" style="{{ $order->status === 'Processing' ? 'border-color:#137333; color:#137333; font-weight:600;' : '' }}">
                @foreach(['Pending','Processing','Shipped','Completed','Cancelled'] as $status)
                    <option @selected($order->status===$status)>{{ $status }}</option>
                @endforeach
            </select>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="6">No orders found.</td></tr>
@endforelse
</tbody>
</table>
</div>
@endsection
