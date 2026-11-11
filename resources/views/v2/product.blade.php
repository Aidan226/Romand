@extends('v2.layout')

@section('content')
<a class="small muted" href="{{ route('home') }}">
    ← Back to collection
</a>

<section class="two-col" style="margin-top:25px">
    <div class="panel">
        @if($selected->image)
            <img
                class="product-image"
                style="height:420px"
                id="product-photo"
                src="{{ asset('images/'.$selected->image) }}"
                alt="{{ $product->name }}"
            >
        @else
            <div
                id="product-photo"
                style="height:330px;background:{{ $selected->shade_hex ?? '#ddd' }};border-radius:8px"
            ></div>
        @endif

        <p class="small muted">
            Digital swatches are approximate.
        </p>
    </div>

    <div>
        <span class="eyebrow">
            {{ $product->brand }} · {{ $product->product_type }}
        </span>

        <h1 style="font-size:45px">
            {{ $product->name }}
        </h1>

        <p class="muted">{{ $product->description }}</p>

        <p>
            {{ $product->finish }} finish

            @if($product->product_type !== 'lipstick' && $product->coverage)
                · {{ $product->coverage }} coverage
            @endif
        </p>

        @if($product->is_demo)
            <div class="notice">
                Illustrative sample product and colors.
                These are not verified rom&nd shade measurements.
            </div>
        @endif

        <form
            id="product-cart-form"
            method="post"
            action="{{ route('cart.add') }}"
        >
            @csrf

            <input
                type="hidden"
                id="selected-shade-id"
                name="product_id"
                value="{{ $selected->id }}"
            >

            <fieldset>
                <legend>Choose your shade</legend>

                @foreach($product->shades as $shade)
                    <label class="shade-tile">
                        <input
                            type="radio"
                            name="shade_name"
                            value="{{ $shade->shade_name }}"
                            data-shade-id="{{ $shade->id }}"
                            data-stock="{{ $shade->stock }}"
                            data-shade-hex="{{ $shade->shade_hex }}"
                            data-image="{{ $shade->image ? asset('images/'.$shade->image) : '' }}"
                            data-undertone="{{ $shade->undertone }}"
                            data-depth="{{ $shade->depth }}"
                            data-reviewed="{{ $shade->verified ? 1 : 0 }}"
                            required
                            @checked($selected->id == $shade->id)
                            
                        >

                        <span
                            class="swatch"
                            style="--c:{{ $shade->shade_hex ?? '#ddd' }}"
                        ></span>

                        <span>
                            {{ $shade->shade_name }}
                            <small>{{ $shade->shade_code }}</small>

                            <br>

                            <span class="muted small">
                                {{ $shade->price > 0 ? number_format($shade->price).' MMK' : 'Price to be set' }}
                                · {{ $shade->stock }} available
                            </span>
                        </span>
                    </label>
                @endforeach
            </fieldset>

            <label class="field" style="margin-top:18px">
                Quantity

                <input
                    id="shade-quantity"
                    type="number"
                    name="quantity"
                    value="1"
                    min="1"
                    max="50"
                    required
                >
            </label>

            <div class="actions">
                <button id="add-to-cart">
                    Add to cart
                </button>

                <button type="button" id="open-camera" class="secondary">Camera နဲ့ ဒီ shade ကိုစမ်းမယ်</button>
            </div>

            <p id="shade-selection-status"
               class="small muted"
               role="status"
               aria-live="polite"></p>
        </form>
        <section id="inline-shade-check" class="panel inline-check" hidden
                 data-type="{{ $product->product_type }}" data-demo="{{ $product->is_demo ? 1 : 0 }}">
            <div class="eyebrow">Selected shade · Camera check</div>
            <h2 id="checking-name">{{ $selected->shade_name }}</h2>
            <p class="small muted">အလင်းညီတဲ့နေရာမှာ မျက်နှာကိုတည့်တည့်ထားပါ။ Cushion စစ်ရင် makeup မပါသော skin ကိုသုံးပါ။ ပုံကို server ပေါ်မတင်ပါ။</p>
            <video id="camera" autoplay playsinline muted hidden></video>
            <canvas id="capture-preview" class="preview" hidden></canvas>
            <p id="camera-status" role="status" aria-live="polite">Camera ကိုခွင့်ပြုပြီး Capture & Check နှိပ်ပါ။</p>
            <div class="actions">
                <button type="button" id="capture-camera" disabled>Capture & Check</button>
                <button type="button" id="close-camera" class="secondary">Close camera</button>
            </div>
            <div id="inline-result" class="compare" hidden role="status" aria-live="polite">
                <span class="eyebrow">Your selected shade</span>
                <h3 id="result-title"></h3><p id="result-message"></p>
                <div class="actions"><span id="skin-swatch" class="swatch"></span><span id="shade-swatch" class="swatch"></span><span id="result-detail" class="small"></span></div>
                <p class="small muted">Camera estimate only. Lighting and camera processing change colors. Lipstick suitability is personal; cushion needs a physical jawline swatch.</p>
            </div>
            <details id="profile-adjustments" hidden><summary class="small">Optional: correct the estimate / add a known undertone</summary>
                <label class="field">Skin depth<select id="depth">@foreach([1=>'Fair',2=>'Light',3=>'Light-medium',4=>'Medium',5=>'Tan',6=>'Deep'] as $v=>$label)<option value="{{ $v }}">{{ $label }}</option>@endforeach</select></label>
                <label class="field">Known undertone / lipstick preference<select id="undertone"><option value="unknown">Not sure</option><option value="warm">Warm</option><option value="cool">Cool</option><option value="neutral">Neutral</option></select></label>
            </details>
        </section>
    </div>
</section>
@endsection

@push('scripts')
<script type="module" src="{{ asset('js/product-camera.js') }}"></script>
@endpush
