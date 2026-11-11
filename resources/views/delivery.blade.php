@extends('layouts.app')

@section('content')
<style>
    .delivery-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }

    .delivery-container h2 {
        font-size: 28px;
        color: #222;
        margin-bottom: 10px;
        text-align: center;
    }

    .delivery-subtitle {
        text-align: center;
        color: #d15c7a;
        font-size: 16px;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .delivery-box {
        margin-bottom: 30px;
        background: #fafafa;
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #eaeaea;
    }

    .delivery-box h3 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 18px;
    }

    .delivery-box p, .delivery-box li {
        color: #666;
        font-size: 15px;
        line-height: 1.6;
    }

    .delivery-box ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
    }

    .delivery-box li {
        margin-bottom: 6px;
    }
</style>

<div class="container delivery-container">
    <h2>Delivery Information</h2>
    <p class="delivery-subtitle">Fast and reliable shipping straight to your doorstep across Myanmar!</p>

    <div class="delivery-box">
        <h3>1. Yangon Delivery</h3>
        <p>Deliveries within Yangon townships usually take <strong>1 to 2 business days</strong> after order confirmation.</p>
        <ul>
            <li>Standard delivery charges apply based on the township.</li>
            <li>Cash on Delivery (COD) and KPay are both fully available.</li>
        </ul>
    </div>

    <div class="delivery-box">
        <h3>2. Other Regions & Cities (Door Delivery / Express)</h3>
        <p>For regions outside Yangon, orders are shipped via trusted high-class delivery services and usually take <strong>3 to 5 business days</strong>.</p>
        <ul>
            <li>Prepayment via KPay is required for certain regional bus/car gate drops or door delivery services.</li>
        </ul>
    </div>

    <div class="delivery-box">
        <h3>3. Delivery Status & Support</h3>
        <p>You can check your order status anytime by visiting your <strong>Profile > Order Tracking</strong> page. If you have any urgent inquiries regarding your shipment, feel free to call us at <strong>09942176731</strong>.</p>
    </div>
</div>
@endsection