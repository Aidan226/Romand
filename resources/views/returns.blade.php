@extends('layouts.app')

@section('content')
<style>
    .returns-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }

    .returns-container h2 {
        font-size: 28px;
        color: #222;
        margin-bottom: 10px;
        text-align: center;
    }

    .returns-subtitle {
        text-align: center;
        color: #d15c7a;
        font-size: 16px;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .policy-box {
        margin-bottom: 30px;
        background: #fafafa;
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #eaeaea;
    }

    .policy-box h3 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 18px;
    }

    .policy-box p, .policy-box li {
        color: #666;
        font-size: 15px;
        line-height: 1.6;
    }

    .policy-box ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
    }

    .policy-box li {
        margin-bottom: 6px;
    }
</style>

<div class="container returns-container">
    <h2>Returns & Exchange Policy</h2>
    <p class="returns-subtitle">We want you to love your rom&nd makeup products!</p>

    <div class="policy-box">
        <h3>1. Eligibility for Returns & Exchange</h3>
        <p>You may request a return or exchange within <strong>7 days</strong> of receiving your order if:</p>
        <ul>
            <li>You received the wrong item (e.g., incorrect shade or product line).</li>
            <li>The product is damaged, defective, or broken upon arrival.</li>
        </ul>
    </div>

    <div class="policy-box">
        <h3>2. Conditions for Return</h3>
        <p>To be eligible for a return or exchange, items must be unopened, unused, in their original packaging, and sealed with all tags intact for hygiene and safety reasons.</p>
    </div>

    <div class="policy-box">
        <h3>3. How to Request a Return</h3>
        <p>If you encounter any issues with your delivery, please contact our customer support immediately via live chat or phone at <strong>09942176731</strong> with your Order ID and unboxing video/photos.</p>
    </div>
</div>
@endsection