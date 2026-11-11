@extends('layouts.app')

@section('content')
<style>
    .about-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        text-align: center;
    }

    .about-container h2 {
        font-size: 32px;
        color: #222;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    .about-container p {
        font-size: 16px;
        color: #666;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .about-banner-img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
        margin: 30px 0;
    }

    .brand-values {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-top: 40px;
        text-align: left;
    }

    .value-card {
        background: #fafafa;
        padding: 25px;
        border-radius: 8px;
        border: 1px solid #eaeaea;
    }

    .value-card h4 {
        color: #d15c7a;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .value-card p {
        font-size: 14px;
        margin: 0;
    }
</style>

<div class="about-container">
    <h2>About rom&nd</h2>
    <p style="font-size: 18px; color: #d15c7a; font-weight: 500;">
        Spark your your own unique beauty with rom&nd.
    </p>
    
    <p>
        Welcome to your favorite K-beauty destination! We are dedicated to bringing you the trendiest and highest-quality lip tints, juicy lasting glosses, and makeup collections straight from Seoul. Our mission is to help you discover your vibrant everyday look with confidence and style.
    </p>

    <!-- ပုံထည့်ချင်ရင် ဒီနေရာမှာ ထည့်လို့ရပါတယ် -->
    <div style="background: #fdf2f4; padding: 40px; border-radius: 10px; margin: 30px 0;">
        <h3 style="color: #333; margin-bottom: 10px;">Our Philosophy</h3>
        <p style="margin: 0; color: #555;">
            We believe makeup should be fun, expressive, and accessible. Every shade we curate is designed to complement your natural tone and elevate your daily mood.
        </p>
    </div>

    <div class="brand-values">
        <div class="value-card">
            <h4>💄 Authentic K-Beauty</h4>
            <p>Directly inspired by trendsetting Korean beauty standards, featuring popular lines like Juicy Lasting Tint and Glasting Melting Balm.</p>
        </div>
        <div class="value-card">
            <h4>✨ Smart Color Analysis</h4>
            <p>On each product page, you can select a shade and use the camera-based color check to see whether that specific shade matches your undertone before buying.</p>
        </div>
        <div class="value-card">
            <h4>🚚 Reliable Service</h4>
            <p>Fast delivery across Myanmar, secure local payment options, and dedicated customer support ready to assist you every day.</p>
        </div>
    </div>
</div>
@endsection