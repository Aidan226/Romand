@extends('v2.layout')

@section('title', 'About Us · rom&nd Shade Studio')

@section('content')
<style>
    .about-page-wrap {
        max-width: 1040px;
        margin: 10px auto 40px;
    }

    /* Hero Section */
    .about-hero {
        text-align: center;
        padding: 30px 20px 40px;
        max-width: 780px;
        margin: 0 auto;
    }

    .about-eyebrow {
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 11px;
        font-weight: 700;
        color: var(--rose);
        margin-bottom: 12px;
        display: inline-block;
    }

    .about-title {
        font: clamp(34px, 5vw, 54px) / 1.15 Georgia, serif;
        color: var(--ink);
        letter-spacing: -1px;
        margin: 0 0 16px;
    }

    .about-lead {
        font-size: 17px;
        line-height: 1.7;
        color: var(--muted);
        margin: 0 auto;
    }

    /* Two-column Story & Philosophy */
    .about-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
        margin: 30px 0 45px;
    }

    .about-card {
        background: #ffffff;
        border: 1px solid #ebd9d2;
        border-radius: 14px;
        padding: 36px 32px;
        box-shadow: 0 4px 18px rgba(57, 40, 45, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .about-card h3 {
        font: 24px Georgia, serif;
        color: var(--ink);
        margin: 0 0 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .about-card p {
        font-size: 14.5px;
        line-height: 1.75;
        color: #554449;
        margin: 0 0 14px;
    }

    .about-card p:last-child {
        margin-bottom: 0;
    }

    .about-card.accent {
        background: linear-gradient(145deg, #fff9f7 0%, #fdf2f4 100%);
        border-color: #ebd0d7;
    }

    .about-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--rose);
        background: #ffffff;
        border: 1px solid #ebd0d7;
        padding: 5px 12px;
        border-radius: 20px;
        margin-bottom: 16px;
        align-self: flex-start;
    }

    /* Core Values 3-Col Grid */
    .values-section {
        margin: 40px 0 50px;
    }

    .section-header-center {
        text-align: center;
        margin-bottom: 30px;
    }

    .section-header-center h3 {
        font: 28px Georgia, serif;
        color: var(--ink);
        margin: 0 0 8px;
    }

    .section-header-center p {
        font-size: 14px;
        color: var(--muted);
        margin: 0;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .value-item {
        background: #ffffff;
        border: 1px solid #ebd9d2;
        border-radius: 12px;
        padding: 28px 24px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .value-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(57, 40, 45, 0.06);
        border-color: #d6b2b9;
    }

    .value-icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #fdf2f4;
        border: 1px solid #f2d2dc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--rose);
        margin-bottom: 18px;
    }

    .value-item h4 {
        font-size: 16px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 10px;
    }

    .value-item p {
        font-size: 13.5px;
        line-height: 1.65;
        color: var(--muted);
        margin: 0;
    }

    /* Bottom CTA Card */
    .about-cta-bar {
        background: #ffffff;
        border: 1px solid #ebd9d2;
        border-radius: 14px;
        padding: 36px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        box-shadow: 0 4px 18px rgba(57, 40, 45, 0.03);
    }

    .about-cta-text h3 {
        font: 22px Georgia, serif;
        color: var(--ink);
        margin: 0 0 6px;
    }

    .about-cta-text p {
        font-size: 14px;
        color: var(--muted);
        margin: 0;
    }

    .about-cta-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    @media (max-width: 860px) {
        .about-grid-2 {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .values-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }
        .about-cta-bar {
            flex-direction: column;
            text-align: center;
            padding: 28px 20px;
        }
        .about-cta-actions {
            flex-direction: column;
            width: 100%;
        }
        .about-cta-actions a {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="about-page-wrap">
    
    <!-- Hero Banner -->
    <div class="about-hero">
        <span class="about-eyebrow">Our Story & Philosophy</span>
        <h1 class="about-title">Spark your own unique beauty.</h1>
        <p class="about-lead">
            Inspired by rom&nd, Seoul’s trendsetting color laboratory. We bring you curated K-beauty makeup collections paired with smart shade guidance, designed to complement your natural skin tone with effortless confidence.
        </p>
    </div>

    <!-- 2-Column Story / Philosophy Cards -->
    <div class="about-grid-2">
        <div class="about-card">
            <div>
                <span class="about-tag-badge">Seoul to Yangon</span>
                <h3>The Story</h3>
                <p>
                    Welcome to your favorite K-beauty destination! We are dedicated to bringing you the trendiest and highest-quality lip tints, juicy lasting glosses, dewy balms, and makeup collections straight from Seoul.
                </p>
                <p>
                    Our mission is to help you discover your vibrant everyday look with confidence, style, and authentic Korean formulations tailored for everyday beauty lovers.
                </p>
            </div>
        </div>

        <div class="about-card accent">
            <div>
                <span class="about-tag-badge">Harmonious Color</span>
                <h3>Our Philosophy</h3>
                <p>
                    We believe makeup should be fun, expressive, and accessible to everyone. Every shade we curate is formulated to elevate your mood and enhance your authentic undertone rather than masking it.
                </p>
                <p>
                    From warm peaches and neutral figs to cool berries, our color palette is thoughtfully organized so finding the shade that makes your complexion glow is intuitive and simple.
                </p>
            </div>
        </div>
    </div>

    <!-- Core Values (3 Columns with Minimalist SVGs) -->
    <div class="values-section">
        <div class="section-header-center">
            <span class="about-eyebrow">Why Shade Studio</span>
            <h3>What Sets Us Apart</h3>
            <p>Every product and tool is designed around beauty clarity and customer trust.</p>
        </div>

        <div class="values-grid">
            <div class="value-item">
                <div class="value-icon-circle">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"></path>
                        <path d="M5 3v4"></path>
                        <path d="M19 17v4"></path>
                        <path d="M3 5h4"></path>
                        <path d="M17 19h4"></path>
                    </svg>
                </div>
                <h4>Authentic K-Beauty</h4>
                <p>
                    Directly inspired by trendsetting Korean beauty standards, featuring beloved formulas like Juicy Lasting Tint, Dewyful Water Tint, and Bare Water Cushion.
                </p>
            </div>

            <div class="value-item">
                <div class="value-icon-circle">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"></circle>
                        <circle cx="17.5" cy="10.5" r=".5" fill="currentColor"></circle>
                        <circle cx="8.5" cy="7.5" r=".5" fill="currentColor"></circle>
                        <circle cx="6.5" cy="12.5" r=".5" fill="currentColor"></circle>
                        <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.563-2.512 5.563-5.563C22 6.5 17.5 2 12 2Z"></path>
                    </svg>
                </div>
                <h4>Smart Color Diagnostics</h4>
                <p>
                    Select any cushion or lip shade and test it with our interactive undertone checker and camera diagnosis to see how it pairs with your skin before purchasing.
                </p>
            </div>

            <div class="value-item">
                <div class="value-icon-circle">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="16" height="13" x="1" y="5" rx="2"></rect>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                </div>
                <h4>Reliable Myanmar Delivery</h4>
                <p>
                    Fast nationwide delivery across Myanmar, safe local payment options (Cash on Delivery and automated KBZPay slip verification), and daily customer care.
                </p>
            </div>
        </div>
    </div>

    <!-- Bottom CTA Bar -->
    <div class="about-cta-bar">
        <div class="about-cta-text">
            <h3>Find the shade that was made for you.</h3>
            <p>Explore our complete catalog of cushion foundations and signature lip tints.</p>
        </div>
        <div class="about-cta-actions">
            <a href="{{ route('home') }}" class="button" style="padding: 10px 20px; font-size: 13px;">
                Shop Collection →
            </a>
            <a href="{{ route('shade.finder') }}" class="button secondary" style="padding: 10px 20px; font-size: 13px;">
                Open Shade Finder
            </a>
        </div>
    </div>

</div>
@endsection
