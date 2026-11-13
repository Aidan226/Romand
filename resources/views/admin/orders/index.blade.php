@extends('admin.layouts.app')
@section('title','Order Management')
@section('content')

@php
    $alertOrders = $orders->filter(function($o) {
        return in_array($o->slip_verification_status, ['mismatch', 'recipient_mismatch', 'duplicate', 'unreadable']) && $o->status === 'Pending';
    });
@endphp

@if($alertOrders->isNotEmpty())
    <div style="background:#fce8e6; border:1px solid #fad2cf; color:#c5221f; padding:12px 16px; border-radius:8px; margin-bottom:18px; font-size:13px; display:flex; align-items:center; justify-content:space-between;">
        <div>
            <strong>⚠️ ACTION REQUIRED: {{ $alertOrders->count() }} Payment Exception(s) Detected!</strong><br>
            <span>Some KPay receipts have amount mismatches, wrong recipient names, duplicate transaction numbers, or unreadable details. Please review them below.</span>
        </div>
    </div>
@else
    <div style="background:#e6f4ea; border:1px solid #ceead6; color:#137333; padding:10px 16px; border-radius:8px; margin-bottom:18px; font-size:13px;">
        <strong>✅ Exception-Based Automation Active:</strong> All orders with valid matching KPay slips are automatically approved and processing. Zero manual check needed unless an anomaly is detected.
    </div>
@endif

<div class="table-wrap" style="overflow-x: auto; background: #fff; border-radius: 12px; border: 1px solid var(--line); box-shadow: 0 4px 18px rgba(57,40,45,0.03);">
<table style="width: 100%; min-width: 980px; border-collapse: collapse;">
<thead>
<tr>
    <th style="width: 75px; min-width: 75px;">Order</th>
    <th style="width: 170px; min-width: 170px;">Customer</th>
    <th style="min-width: 180px;">Items</th>
    <th style="min-width: 280px;">Total & Payment</th>
    <th style="width: 130px; min-width: 130px;">Date</th>
    <th style="width: 160px; min-width: 160px; text-align: center;">Status</th>
</tr>
</thead>
<tbody>
@forelse($orders as $order)
<tr style="{{ in_array($order->slip_verification_status, ['mismatch', 'recipient_mismatch', 'duplicate']) && $order->status === 'Pending' ? 'background: #fff8f7;' : '' }}">
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
                <div style="margin-top:6px; max-width: 320px;">
                    <span style="display:inline-block; background:#e6f4ea; color:#137333; font-size:11px; font-weight:700; padding:3px 8px; border-radius:4px; border:1px solid #ceead6;">
                        🟢 Auto-Verified: {{ number_format($order->slip_detected_amount ?? $order->total_price) }} Ks
                    </span>
                    @if($order->transaction_id)
                        <div class="small" style="color:#5f6368; font-size:10px; margin-top:2px; word-break: break-all;">
                            Trans ID: {{ $order->transaction_id }}
                        </div>
                    @endif
                    @if($order->slip_detected_recipient)
                        <div class="small" style="color:#5f6368; font-size:10px; margin-top:1px;">
                            Recipient: {{ $order->slip_detected_recipient }}
                        </div>
                    @endif
                </div>
            @elseif($order->slip_verification_status === 'mismatch')
                <div style="margin-top:6px; background:#fce8e6; border:1px solid #fad2cf; color:#c5221f; padding:8px 10px; border-radius:6px; font-size:11px; text-align:left; max-width: 320px; line-height: 1.5; word-break: break-word;">
                    <strong style="display:block; margin-bottom:2px;">🔴 ALERT: Amount Mismatch!</strong>
                    <span>{{ $order->slip_verification_notes }}</span>
                </div>
            @elseif($order->slip_verification_status === 'recipient_mismatch')
                <div style="margin-top:6px; background:#fce8e6; border:1px solid #fad2cf; color:#c5221f; padding:8px 10px; border-radius:6px; font-size:11px; text-align:left; max-width: 320px; line-height: 1.5; word-break: break-word;">
                    <strong style="display:block; margin-bottom:2px;">🔴 ALERT: Wrong Recipient!</strong>
                    <span>{{ $order->slip_verification_notes }}</span>
                </div>
            @elseif($order->slip_verification_status === 'duplicate')
                <div style="margin-top:6px; background:#fef7e0; border:1px solid #feefc3; color:#b06000; padding:8px 10px; border-radius:6px; font-size:11px; text-align:left; max-width: 320px; line-height: 1.5; word-break: break-word;">
                    <strong style="display:block; margin-bottom:2px;">⚠️ FRAUD ALERT: Duplicate Slip!</strong>
                    <span>{{ $order->slip_verification_notes }}</span>
                </div>
            @elseif($order->slip_verification_status === 'unreadable')
                <div style="margin-top:6px; background:#f1f3f4; border:1px solid #dadce0; color:#3c4043; padding:8px 10px; border-radius:6px; font-size:11px; text-align:left; max-width: 320px; line-height: 1.5; word-break: break-word;">
                    <strong style="display:block; margin-bottom:2px;">❓ Check Needed: Unreadable Slip</strong>
                    <span>{{ $order->slip_verification_notes }}</span>
                </div>
            @else
                <div class="small" style="margin-top:4px; color:#5f6368;">Payment: {{ $order->payment_status }}</div>
            @endif

            @if($order->payment_screenshot)
                <div style="margin-top:6px;">
                    <a target="_blank" rel="noopener" href="{{ route('orders.payment-proof',$order) }}" style="font-size:11px; text-decoration:underline; color: var(--pink); font-weight: 600;">
                        🔍 View payment slip
                    </a>
                </div>
            @endif
        @else
            <div class="small" style="margin-top:2px;">Status: {{ $order->payment_status }}</div>
        @endif
    </td>
    <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
    <td style="width: 160px; min-width: 160px; text-align: center; vertical-align: middle;">
        <form method="POST" action="{{ route('admin.orders.status',$order->id) }}" style="margin: 0; display: inline-block;">
            @csrf
            @method('PATCH')
            @php
                $statusColorStyle = match($order->status) {
                    'Processing' => 'background: #e6f4ea; border: 1.5px solid #34a853; color: #137333;',
                    'Shipped' => 'background: #e8f0fe; border: 1.5px solid #4285f4; color: #1a73e8;',
                    'Completed' => 'background: #edf7ee; border: 1.5px solid #1e8e3e; color: #0d652d;',
                    'Cancelled' => 'background: #fce8e6; border: 1.5px solid #ea4335; color: #c5221f;',
                    default => 'background: #fff8e6; border: 1.5px solid #fbbc04; color: #b06000;', // Pending
                };
            @endphp
            <select name="status" onchange="this.form.submit()" style="{{ $statusColorStyle }} width: 140px; min-width: 140px; padding: 7px 10px; font-weight: 700; font-size: 13px; border-radius: 8px; cursor: pointer; text-align: left; display: block;">
                @foreach(['Pending','Processing','Shipped','Completed','Cancelled'] as $status)
                    <option value="{{ $status }}" @selected($order->status === $status) style="background: #ffffff; color: #39282d; font-weight: 500;">
                        {{ $status }}
                    </option>
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
