@extends('layouts.app')

@section('content')
<style>
    .payment-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }

    .payment-container h2 {
        font-size: 28px;
        color: #222;
        margin-bottom: 10px;
        text-align: center;
    }

    .payment-subtitle {
        text-align: center;
        color: #d15c7a;
        font-size: 16px;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .payment-method-card {
        display: flex;
        gap: 25px;
        margin-bottom: 30px;
        background: #fafafa;
        padding: 30px;
        border-radius: 10px;
        border: 1px solid #eaeaea;
        align-items: center;
    }

    .payment-icon {
        font-size: 40px;
        background: #fdf2f4;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .payment-details h3 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 20px;
    }

    .payment-details p {
        margin: 0 0 8px 0;
        color: #666;
        font-size: 15px;
        line-height: 1.6;
    }

    .payment-details ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
        color: #555;
        font-size: 14px;
    }

    .payment-details li {
        margin-bottom: 5px;
    }
</style>

<div class="container payment-container">
    <h2>Payment Methods</h2>
    <p class="payment-subtitle">We offer secure and convenient payment options for your orders.</p>

    <!-- KPay Method -->
    <div class="payment-method-card">
        <div class="payment-icon">📱</div>
        <div class="payment-details">
            <h3>KBZ Pay (KPay)</h3>
            <p>Fast, secure, and cashless mobile payment method. You can transfer the total amount directly to our official KPay account.</p>
            <ul>
                <li><strong>Account Name:</strong> rom&nd Official Store</li>
                <li><strong>Account Number:</strong> 09-942176731</li>
                <li><em>Note: Please send us the payment screenshot via customer support after transferring.</em></li>
            </ul>
        </div>
    </div>

    <!-- COD Method -->
    <div class="payment-method-card">
        <div class="payment-icon">💵</div>
        <div class="payment-details">
            <h3>Cash on Delivery (COD)</h3>
            <p>Prefer to inspect your items first? Pay in cash directly to the delivery person when your package arrives at your doorstep.</p>
            <ul>
                <li>Available for all regional deliveries across Myanmar.</li>
                <li>Please prepare the exact total amount in cash to make the delivery process smoother.</li>
            </ul>
        </div>
    </div>
</div>
@endsection