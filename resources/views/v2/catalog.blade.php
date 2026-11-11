@extends('v2.layout')

@section('content')
<section class="hero">
    <div>
        <div class="eyebrow">A little color. A little confidence.</div>
        <h1>Meet your<br>next favorite shade.</h1>

        <p>
            Explore lip colors and cushion shades.
            Choose a product and shade, then open its camera check on the product page.
        </p>

        <a class="button" href="#collection">Shop the collection ↓</a>
    </div>

    <div class="palette-art" aria-hidden="true">
        <i style="--c:#a65962"></i>
        <i style="--c:#c18379"></i>
        <i style="--c:#dcaa89"></i>
        <i style="--c:#81565d"></i>
        <i style="--c:#b56d55"></i>
    </div>
</section>

<div class="toolbar" id="collection">
    <div class="chips">
        @foreach(['' => 'All', 'lipstick' => 'Lips', 'cushion' => 'Cushion'] as $type => $label)
            <a href="{{ route('home', ['type' => $type]) }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <form method="get">
        <input type="hidden" name="type" value="{{ request('type') }}">

        <label class="small">
            Search products
            <input
                name="q"
                maxlength="100"
                placeholder="Search the collection"
                value="{{ request('q') }}"
            >
        </label>
    </form>
</div>

@php
    $visibleProducts = $products->reject(function ($product) {
        return $product->name === 'Juicy Lasting Tint'
            && $product->shades->isEmpty();
    });
@endphp

<div class="grid">
    @forelse($visibleProducts as $product)
        @php($first = $product->shades->first())

        <article class="card">
            @if($first && $first->image)
                <img
                    class="product-image"
                    src="{{ asset('images/'.$first->image) }}"
                    alt="{{ $product->name }}"
                    loading="lazy"
                >
            @else
                <div
                    class="palette-art"
                    style="height:250px;border-radius:0;transform:none"
                    aria-hidden="true"
                >
                    @foreach($product->shades->take(4) as $shade)
                        <i style="--c:{{ $shade->shade_hex ?? '#b98175' }};height:120px;width:42px"></i>
                    @endforeach
                </div>
            @endif

            <div class="card-body">
                <span class="eyebrow">
                    {{ $product->brand }} · {{ $product->product_type }}
                </span>

                @if($product->is_demo)
                    <p>
                        <span class="tag">Illustrative demo data</span>
                    </p>
                @endif

                <h3>{{ $product->name }}</h3>

                <p class="muted small">
                    {{ $product->finish }} · {{ $product->shades->count() }} shades
                </p>

                <div class="swatches">
                    @foreach($product->shades as $shade)
                        <span
                            class="swatch"
                            style="--c:{{ $shade->shade_hex ?? '#ddd' }}"
                            title="{{ $shade->shade_name }}"
                        ></span>
                    @endforeach
                </div>

                @if($first)
                    <p>
                        @if($product->shades->min('price') > 0)
                            From {{ number_format($product->shades->min('price')) }} MMK
                        @else
                            Price to be set
                        @endif
                    </p>

                    <a
                        class="button secondary"
                        href="{{ route('product.show', $first->id) }}"
                    >
                        Explore shades →
                    </a>
                @else
                    <p>No shades added yet.</p>
                @endif
            </div>
        </article>
    @empty
        <p>No products found. Try another category or search.</p>
    @endforelse
</div>
@endsection