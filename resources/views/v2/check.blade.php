@extends('v2.layout')

@section('content')
<a class="small muted"
   href="{{ route('product.show', $shade->id) }}">
    ← Back to product
</a>

<div class="eyebrow" style="margin-top:24px">
    Your selected shade
</div>

<h1 style="font-size:48px">Does this shade suit me?</h1>

<section class="panel" style="margin-bottom:24px">
    <h2>{{ $shade->product->name }}</h2>

    <span class="swatch"
          style="--c:{{ $shade->shade_hex ?? '#ddd' }}"></span>

    <strong>{{ $shade->shade_name }}</strong>
    <span>{{ $shade->shade_code }}</span>

    <p class="muted">
        We will check this shade only.
        Digital colors and camera estimates are approximate.
    </p>
</section>

<div class="two-col">
    <section class="panel">
        <h2>Capture your skin</h2>

        <p class="muted">
            Use even daylight and turn off beauty filters.
            Camera capture suggests skin depth only.
        </p>

        <video id="camera"
               autoplay playsinline muted hidden></video>

        <canvas id="capture-preview"
                class="preview" hidden></canvas>

        <p id="camera-status"
           role="status"
           aria-live="polite">
            Open the camera, or select your profile manually.
        </p>

        <div class="actions">
            <button type="button" id="open-camera">
                Open camera
            </button>

            <button type="button"
                    id="capture-camera"
                    class="secondary"
                    disabled>
                Capture cheeks
            </button>

            <button type="button"
                    id="close-camera"
                    class="secondary">
                Close
            </button>
        </div>

        <p class="small muted">
            Confirm or correct the estimated depth on the right.
            Undertone remains your own selection.
        </p>
    </section>

    <section class="panel">
        <h2>Confirm your profile</h2>

        <form id="finder-form"
              method="post"
              action="{{ route('shade.check.submit', $shade->id) }}">
            @csrf

            <label class="field">
                Skin depth

                <select id="depth" name="depth" required>
                    @foreach([
                        1 => 'Fair',
                        2 => 'Light',
                        3 => 'Light-medium',
                        4 => 'Medium',
                        5 => 'Tan',
                        6 => 'Deep'
                    ] as $value => $label)
                        <option value="{{ $value }}"
                            @selected((int) old('depth', 3) === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="field">
                Undertone / preference

                <select id="undertone"
                        name="undertone"
                        required>
                    @foreach([
                        'unknown' => 'Not sure',
                        'warm' => 'Warm',
                        'cool' => 'Cool',
                        'neutral' => 'Neutral'
                    ] as $value => $label)
                        <option value="{{ $value }}"
                            @selected(old('undertone', 'unknown') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </label>

            <button>Check this shade →</button>
        </form>
    </section>
</div>

@if($assessment)
    <section class="panel" style="margin-top:24px">
        <div class="eyebrow">Your shade check</div>

        <h2>{{ $assessment['title'] }}</h2>

        <ul>
            @foreach($assessment['reasons'] as $reason)
                <li>{{ $reason }}</li>
            @endforeach
        </ul>

        <p class="small muted">
            This is rule-based guidance, not measured accuracy.
        </p>

        <a class="button secondary"
           href="{{ route('product.show', $shade->id) }}">
            Back to product / choose another shade
        </a>

        @if($shade->stock > 0)
            <form method="post"
                  action="{{ route('cart.add') }}"
                  style="margin-top:16px">
                @csrf

                <input type="hidden"
                       name="product_id"
                       value="{{ $shade->id }}">

                <input type="hidden"
                       name="shade_name"
                       value="{{ $shade->shade_name }}">

                <input type="hidden"
                       name="quantity"
                       value="1">

                <button>Add this shade to cart</button>
            </form>
        @else
            <p>This shade is currently out of stock.</p>
        @endif
    </section>
@endif
@endsection

@push('scripts')
<script type="module"
        src="{{ asset('js/shade-camera.js') }}"></script>
@endpush