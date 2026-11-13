@extends('v2.layout')
@section('content')
<div class="container checkout">
    <h2>Checkout</h2>

    @if ($errors->any())
        <div class="validation-errors">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('order.confirm') }}" method="POST" enctype="multipart/form-data" class="shipping-form" id="checkoutForm">
        @csrf

        <h3>Shipping Information</h3>
        <input type="text" name="fullname" value="{{ old('fullname', auth()->user()?->name) }}" placeholder="Full Name" required>
        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Phone Number (for example 09xxxxxxxxx)" required>
        <textarea name="address" placeholder="Delivery Address in Myanmar" required>{{ old('address') }}</textarea>

        <h3 style="margin-top:24px;">Payment Method</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-top:12px;">
            <label style="display:block;border:1px solid #ead9df;border-radius:12px;padding:16px;background:#fff;cursor:pointer;">
                <input type="radio" name="payment_method" value="kpay" {{ old('payment_method') === 'kpay' ? 'checked' : '' }} required>
                <strong style="margin-left:6px;">KBZPay (KPay)</strong>
                <div style="color:#666;font-size:14px;margin:8px 0 0 24px;line-height:1.6;">
                    Pay to <strong>{{ config('studio.payment_phone') ?: 'Demo only — configure a payment number' }}</strong>@if(config('studio.payment_name')) · <strong>{{ config('studio.payment_name') }}</strong>@endif<br>
                    Student demo store — screenshot submission is not automatic payment verification.
                </div>
            </label>

            <label style="display:block;border:1px solid #ead9df;border-radius:12px;padding:16px;background:#fff;cursor:pointer;">
                <input type="radio" name="payment_method" value="cod" {{ old('payment_method') === 'cod' ? 'checked' : '' }} required>
                <strong style="margin-left:6px;">Cash on Delivery</strong>
                <div style="color:#666;font-size:14px;margin:8px 0 0 24px;line-height:1.6;">
                    Pay cash when your order arrives.
                </div>
            </label>
        </div>

        <div id="kpayProof" style="display:none;background:#fff7fa;border:1px solid #f0dfe5;border-radius:12px;padding:16px;margin-top:14px;">
            <label for="payment_screenshot" style="display:block;font-weight:600;margin-bottom:8px;">KPay Transaction Screenshot</label>
            <input type="file" id="payment_screenshot" name="payment_screenshot" accept="image/png,image/jpeg,image/jpg">
            <p style="font-size:13px;color:#777;margin:8px 0 0;">Upload the transaction screenshot after completing your KBZPay transfer.</p>
        </div>

        <div style="background:#fff7fa;border:1px solid #f0dfe5;border-radius:12px;padding:14px;margin-top:16px;">
            <p>Subtotal: {{ number_format($subtotal) }} MMK</p>
            @if($promotion)
                <p style="color:#d15c7a;"><strong>{{ $promotion->name }}</strong>: -{{ number_format($discount_amount) }} MMK</p>
            @endif
            <h3>Total to Pay: {{ number_format($final_total) }} MMK</h3>
        </div>

        <div style="display:flex;gap:12px;align-items:center;margin-top:20px;flex-wrap:wrap;">
            <a href="{{ route('cart.index') }}" class="btn" style="text-decoration:none;text-align:center;">Cancel</a>
            <button type="submit" class="btn primary">Confirm Order</button>
        </div>
    </form>
</div>

<script>
(function () {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const proofBox = document.getElementById('kpayProof');
    const proofInput = document.getElementById('payment_screenshot');

    function updatePaymentFields() {
        const selected = document.querySelector('input[name="payment_method"]:checked');
        const isKpay = selected && selected.value === 'kpay';
        proofBox.style.display = isKpay ? 'block' : 'none';
        proofInput.required = !!isKpay;
        if (!isKpay) {
            proofInput.value = '';
        }
    }

    paymentRadios.forEach(radio => radio.addEventListener('change', updatePaymentFields));
    updatePaymentFields();
})();
</script>
@endsection
