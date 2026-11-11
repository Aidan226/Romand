@extends('layouts.app')

@section('content')
<style>
    .guide-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }

    .guide-container h2 {
        font-size: 28px;
        color: #222;
        margin-bottom: 10px;
        text-align: center;
    }

    .guide-subtitle {
        text-align: center;
        color: #d15c7a;
        font-size: 16px;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .step-box {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        background: #fafafa;
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #eaeaea;
        align-items: flex-start;
    }

    .step-number {
        background: #d15c7a;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        flex-shrink: 0;
    }

    .step-content h3 {
        margin: 0 0 8px 0;
        color: #333;
        font-size: 18px;
    }

    .step-content p {
        margin: 0;
        color: #666;
        font-size: 15px;
        line-height: 1.6;
    }
</style>

<div class="container guide-container">
    <h2>How To Buy Guide</h2>
    <p class="guide-subtitle">Simple and easy steps to order your favorite rom&nd cosmetics!</p>

    <div class="step-box">
        <div class="step-number">1</div>
        <div class="step-content">
            <h3>Browse & Choose Products</h3>
            <p>Explore our catalog of lip tints, balms, and lipsticks. When you open a product, choose a shade and use <strong>Check This Shade On Me</strong> to see whether that specific shade matches your skin undertone before buying.</p>
        </div>
    </div>

    <div class="step-box">
        <div class="step-number">2</div>
        <div class="step-content">
            <h3>Add to Cart</h3>
            <p>Select your preferred shade and quantity, then click <strong>"Add to Cart"</strong>. You can review your items, subtotal, and any active Saturday special discounts inside your shopping cart.</p>
        </div>
    </div>

    <div class="step-box">
        <div class="step-number">3</div>
        <div class="step-content">
            <h3>Login & Checkout</h3>
            <p>Click <strong>"Proceed to Checkout"</strong>. If you are not logged in, you will be prompted to log in or create an account. Fill in your shipping information (Name, Phone Number, and Address).</p>
        </div>
    </div>

    <div class="step-box">
        <div class="step-number">4</div>
        <div class="step-content">
            <h3>Confirm Order & Track</h3>
            <p>Click <strong>"Confirm Order"</strong> to successfully place your order. You can easily view your order history and check its status anytime by going to your <strong>Profile > Order Tracking</strong> page!</p>
        </div>
    </div>
</div>
@endsection