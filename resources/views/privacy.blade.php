@extends('layouts.app')

@section('content')
<style>
    .privacy-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }

    .privacy-container h2 {
        font-size: 28px;
        color: #222;
        margin-bottom: 10px;
        text-align: center;
    }

    .update-date {
        text-align: center;
        color: #888;
        font-size: 14px;
        margin-bottom: 35px;
    }

    .privacy-section {
        margin-bottom: 30px;
    }

    .privacy-section h3 {
        font-size: 18px;
        color: #d15c7a;
        margin-bottom: 10px;
    }

    .privacy-section p, .privacy-section li {
        font-size: 15px;
        color: #555;
        line-height: 1.7;
    }

    .privacy-section ul {
        padding-left: 20px;
        margin-top: 8px;
    }
</style>

<div class="privacy-container">
    <h2>Privacy Policy</h2>
    <p class="update-date">Last updated: August 2026</p>

    <div class="privacy-section">
        <h3>1. Information We Collect</h3>
        <p>When you visit the rom&nd official store, create an account, place an order, or use our color analysis tool, we may collect personal information such as:</p>
        <ul>
            <li>Your name, email address, phone number, and delivery address during checkout.</li>
            <li>Account login credentials (email and encrypted passwords).</li>
            <li>Shopping preferences, cart items, and order history.</li>
        </ul>
    </div>

    <div class="privacy-section">
        <h3>2. How We Use Your Information</h3>
        <p>We use the collected data to provide you with a seamless shopping experience, including:</p>
        <ul>
            <li>Processing and fulfilling your cosmetic orders (e.g., lip tints and balms).</li>
            <li>Managing your user profile and order tracking history.</li>
            <li>Communicating with you regarding order confirmations, updates, and customer support.</li>
        </ul>
    </div>

    <div class="privacy-section">
        <h3>3. Data Security</h3>
        <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, or disclosure. Your password is securely hashed, and your checkout details are handled with utmost care.</p>
    </div>

    <div class="privacy-section">
        <h3>4. Contact Us</h3>
        <p>If you have any questions or concerns about our Privacy Policy, feel free to reach out to our support team at <strong>service@romand-store.com</strong> or call us at <strong>09942176731</strong>.</p>
    </div>
</div>
@endsection