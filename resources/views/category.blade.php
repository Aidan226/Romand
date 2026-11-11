@extends('layouts.app')

@section('content')
<section class="category-products-page">

    <div class="category-header">
        <a href="{{ route('home') }}" class="back-link">
            ← Back to Home
        </a>

        <h1>{{ $categoryTitle }}</h1>
        <p>{{ $categoryDescription }}</p>
    </div>

    @if ($products->isEmpty())
        <p class="empty-message">No products found in this category.</p>
    @else
        <div class="store-product-grid {{ ($category ?? '') === 'balm-care' ? 'lip-balm-grid' : '' }}">
            @foreach ($products as $prod)
                <article class="store-product-card">

                    <a href="{{ route('product.show', $prod['id']) }}" class="product-image-link">
                        <img
                            src="{{ asset('images/' . $prod['image']) }}"
                            alt="{{ $prod['name'] }}"
                        >
                    </a>

                    <h2>
                        <a href="{{ route('product.show', $prod['id']) }}">
                            {{ strtoupper($prod['name']) }}
                        </a>
                    </h2>

                    <div class="product-rating">
                        <span>★★★★★</span>
                    </div>

                    <p class="price">{{ number_format($prod['price']) }} MMK</p>

                        @if (!empty($prod['shades']) && is_array($prod['shades']))
    <div class="swatches">
        @foreach($prod['shades'] as $shade)
            <span class='swatch' style='background-color: {{ isset($shade["hex"]) ? $shade["hex"] : "#d8a8ad" }};' title='{{ $shade["name"] ?? "" }}'></span>
        @endforeach
    </div>
@endif
                    <a href="{{ route('product.show', $prod['id']) }}" class="view-product-button">
                        View Product
                    </a>

                </article>
            @endforeach
        </div>
    @endif

</section>
@endsection
