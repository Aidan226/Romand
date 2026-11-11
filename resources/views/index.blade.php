@extends('layouts.app')

@section('content')

<!-- Hero Banner Section -->
<section class="hero-banner">
    <div class="hero-content">
        <span class="hero-badge">✨ Official K-Beauty Store</span>
        <h1>Timeless Elegance in Every Shade</h1>
        <p>Discover your signature K-beauty glow with Rom&nd, bringing you trendsetting lip tints and glass-like finishes straight from Seoul.</p>
        
        <div class="hero-actions">
            <a href="#categories" class="btn primary-btn">Explore Catalog</a>
        </div>
    </div>

</section>

<!-- About Section Home Preview (No White Box / Clean & Flat) -->
<section class="home-about-preview">
    <div class="home-about-container">
        <h2>About rom&nd</h2>
        <p class="home-about-subtitle">Spark your own unique beauty with rom&nd.</p>
        
        <p class="home-about-desc">
            Welcome to your favorite K-beauty destination! We are dedicated to bringing you the trendiest and highest-quality lip tints, juicy lasting glosses, and makeup collections straight from Seoul. Our mission is to help you discover your vibrant everyday look with confidence and style.
        </p>

        <div class="home-philosophy-box">
            <h3>Our Philosophy</h3>
            <p>We believe makeup should be fun, expressive, and accessible. Every shade we curate is designed to complement your natural tone and elevate your daily mood.</p>
        </div>

        <div class="home-features-grid">
            <div class="home-feature-card">
                <div class="feature-icon">💄</div>
                <h4>Authentic K-Beauty</h4>
                <p>Directly inspired by trendsetting Korean beauty standards, featuring popular lines like Juicy Lasting Tint and Glasting Melting Balm.</p>
            </div>
            <div class="home-feature-card">
                <div class="feature-icon">✨</div>
                <h4>Smart Color Analysis</h4>
                <p>Choose a lipstick shade while shopping and use the camera check on the product page to see whether that specific shade suits your undertone.</p>
            </div>
            <div class="home-feature-card">
                <div class="feature-icon">🚚</div>
                <h4>Reliable Service</h4>
                <p>Fast delivery across Myanmar, secure local payment options, and dedicated customer support ready to assist you every day.</p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 35px;">
            <a href="{{ route('about') }}" class="read-more-link">Read More About Us &rarr;</a>
        </div>
    </div>
</section>

<!-- Featured Products Categories Section -->
<section id="categories" class="container featured-section">
    <div class="section-title-wrap">
        <h2>Featured Collections</h2>
        <p>Explore our best-selling categories crafted for your everyday glow</p>
    </div>

    <div class="category-layout">
        <a href="{{ route('category.show', 'glossy') }}" class="category-card">
            <div class="category-img-wrap">
                <img src="{{ asset('images/image1/index.jpeg') }}" alt="Glossy Tint" class="category-image">
            </div>
            <div class="category-info">
                <h3>Glossy Tint</h3>
                <p>Shiny, glass-like and dewy finishes</p>
                <span class="category-link">View Products →</span>
            </div>
        </a>

        <a href="{{ route('category.show', 'velvety') }}" class="category-card">
            <div class="category-img-wrap">
                <img src="{{ asset('images/image1/index2.jpg') }}" alt="Velvety & Matte" class="category-image">
            </div>
            <div class="category-info">
                <h3>Velvety & Matte</h3>
                <p>Soft, lightweight and smooth matte finishes</p>
                <span class="category-link">View Products →</span>
            </div>
        </a>

        <a href="{{ route('category.show', 'lip-balm') }}" class="category-card">
            <div class="category-img-wrap">
                <img src="{{ asset('images/image1/index333.webp') }}" alt="Lip Balm" class="category-image">
            </div>
            <div class="category-info">
                <h3>Lip Balm & Care</h3>
                <p>Moisturizing care with jelly-like shine</p>
                <span class="category-link">View Products →</span>
            </div>
        </a>
    </div>

</section>

<!-- Custom Styles -->
<style>
    /* Hero Banner */
    .hero-banner {
        background: linear-gradient(135deg, #fff5f7 0%, #fef8fa 100%);
        padding: 50px 20px 45px 20px;
        text-align: center;
    }
    .hero-badge {
        display: inline-block;
        background: #fce4ec;
        color: #d15c7a;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .hero-banner h1 {
        font-size: 36px;
        color: #222;
        margin-bottom: 10px;
        font-weight: 800;
    }
    .hero-banner p {
        color: #666;
        font-size: 15px;
        max-width: 600px;
        margin: 0 auto 20px auto;
        line-height: 1.6;
    }
    .hero-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 25px;
    }
    .hero-actions .btn {
        padding: 10px 22px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    .hero-actions .primary-btn {
        background: #d15c7a;
        color: #fff;
        box-shadow: 0 4px 12px rgba(209, 92, 122, 0.3);
    }
    .hero-actions .primary-btn:hover {
        background: #b84a66;
    }
    .hero-actions .outline-btn {
        background: #fff;
        color: #d15c7a;
        border: 2px solid #d15c7a;
    }
    .hero-actions .outline-btn:hover {
        background: #fff5f7;
    }

    /* Sunday Promo Banner */
    .sunday-promo-banner {
        background: #fff;
        border: 2px dashed #d15c7a;
        color: #d15c7a;
        padding: 12px 20px;
        border-radius: 10px;
        margin: 15px auto 0 auto;
        max-width: 850px;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        box-shadow: 0 2px 8px rgba(209, 92, 122, 0.05);
    }

    /* About Preview Section */
    .home-about-preview {
        max-width: 1100px;
        margin: 40px auto;
        padding: 20px;
        background: transparent;
        box-shadow: none;
        text-align: center;
    }
    .home-about-container h2 {
        font-size: 30px;
        color: #222;
        margin-bottom: 5px;
    }
    .home-about-subtitle {
        color: #d15c7a;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 15px;
    }
    .home-about-desc {
        color: #555;
        font-size: 15px;
        line-height: 1.7;
        max-width: 750px;
        margin: 0 auto 30px auto;
    }
    .home-philosophy-box {
        background: #fff;
        border-left: 4px solid #d15c7a;
        border-radius: 0 10px 10px 0;
        padding: 20px 25px;
        max-width: 750px;
        margin: 0 auto 35px auto;
        text-align: left;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .home-philosophy-box h3 {
        margin-top: 0;
        color: #333;
        font-size: 16px;
        margin-bottom: 6px;
    }
    .home-philosophy-box p {
        color: #666;
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
    }
    .home-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        text-align: left;
    }
    .home-feature-card {
        background: #fff;
        border: 1px solid #f0e6eb;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.02);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .home-feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(209, 92, 122, 0.08);
    }
    .feature-icon {
        font-size: 26px;
        margin-bottom: 10px;
    }
    .home-feature-card h4 {
        margin-top: 0;
        color: #222;
        font-size: 16px;
        margin-bottom: 8px;
    }
    .home-feature-card p {
        color: #666;
        font-size: 13px;
        margin: 0;
        line-height: 1.6;
    }
    .read-more-link {
        display: inline-block;
        color: #d15c7a;
        font-weight: bold;
        text-decoration: none;
        font-size: 15px;
        transition: color 0.2s;
    }
    .read-more-link:hover {
        color: #b84a66;
        text-decoration: underline;
    }

    /* Featured Products Section */
    .featured-section {
        margin: 60px auto;
        padding: 0 20px;
    }
    .section-title-wrap {
        text-align: center;
        margin-bottom: 35px;
    }
    .section-title-wrap h2 {
        font-size: 28px;
        color: #222;
        margin-bottom: 6px;
    }
    .section-title-wrap p {
        color: #777;
        font-size: 14px;
    }
    
    /* Category Layout */
    .category-layout {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        max-width: 1100px;
        margin: 0 auto;
    }
    .category-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        border: 1px solid #eee;
        box-shadow: 0 3px 12px rgba(0,0,0,0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(209, 92, 122, 0.12);
    }
    .category-img-wrap {
        height: 200px;
        overflow: hidden;
        background: #f9f9f9;
    }
    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .category-card:hover .category-image {
        transform: scale(1.05);
    }
    .category-info {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .category-info h3 {
        color: #222;
        font-size: 18px;
        margin: 0 0 6px 0;
    }
    .category-info p {
        color: #666;
        font-size: 13px;
        margin: 0 0 15px 0;
        line-height: 1.5;
        flex-grow: 1;
    }
    .category-link {
        color: #d15c7a;
        font-weight: bold;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>
@endsection