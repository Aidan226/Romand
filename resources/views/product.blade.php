@extends('layouts.app')

@section('content')
<style>
    /* Quantity input ထဲက default အပေါ်အောက် မျှားများကို ဖယ်ရှားရန် */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield; /* Firefox အတွက် */
    }
    .shade-check-box { margin: 18px 0; padding: 18px; border: 1px solid #f1cbd6; border-radius: 14px; background: #fff8fa; }
    .shade-check-camera { display:none; margin-top:15px; }
    .shade-check-camera video { width:100%; max-width:360px; border-radius:14px; background:#222; }
    .shade-match-result { display:none; margin-top:14px; padding:14px; border-radius:12px; background:#fff; border:1px solid #efd5dd; }
</style>

<section class="product-detail-page" data-price="{{ $product['price'] }}">

    <a href="{{ url()->previous() }}" class="back-link">
        ← Back to Products
    </a>

    <div class="product-detail-layout">

        {{-- ==================== PRODUCT IMAGE ==================== --}}
        <div class="product-gallery">
            <button
                type="button"
                id="previous-shade"
                class="gallery-arrow gallery-arrow-left"
                aria-label="Previous shade"
            >
                ‹
            </button>

            <img
                id="main-product-image"
                src="{{ asset('images/' . ($product['shades'][0]['image'] ?? $product['image'])) }}"
                alt="{{ $product['name'] }}"
            >

            <button
                type="button"
                id="next-shade"
                class="gallery-arrow gallery-arrow-right"
                aria-label="Next shade"
            >
                ›
            </button>

            <div class="gallery-position">
                <span id="current-image-number">1</span>
                /
                <span>{{ count($product['shades']) }}</span>
            </div>
        </div>


        {{-- ==================== PRODUCT INFORMATION ==================== --}}
        <div class="product-information">
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                {{-- PRODUCT CATEGORY --}}
                <span class="product-tag">
                    {{ $product['category'] }}
                </span>

                {{-- PRODUCT NAME (တစ်ခုတည်းသာ ထားရှိရန်) --}}
                <h1>
                    {{ $product['name'] }}
                </h1>

                {{-- RATING --}}
                <div class="detail-rating">
                    <span>★★★★★</span>
                    <small>Customer reviews</small>
                </div>

                {{-- PRICE (တစ်ခုတည်းသာ ထားရှိရန်) --}}
                <p class="detail-price">
                    {{ number_format($product['price']) }} MMK
                </p>

                

                {{-- ==================== SHADE SELECTOR ==================== --}}
                <fieldset class="shade-selector">
                    <legend>
                        Select a shade:
                        <strong id="selected-shade-name">
                            {{ $product['shades'][0]['name'] }}
                        </strong>
                    </legend>

                    <div class="shade-options" id="shade-options-container">
                        @foreach ($product['shades'] as $index => $shade)
                           <label class="shade-option" style="text-align: center; display: inline-block;">
                                <input
                                    type="radio"
                                    name="shade_name"
                                    value="{{ $shade['name'] }}"
                                    data-index="{{ $index }}"
                                    data-image="{{ asset('images/' . ($shade['image'] ?? $product['image'])) }}"
                                    data-shade-hex="{{ $shade['hex'] ?? '' }}"
                                    @checked($index === 0)
                                    required
                                >

                                <img 
                                    src="{{ asset('images/' . ($shade['image'] ?? $product['image'])) }}" 
                                    alt="{{ $shade['name'] }}" 
                                    class="shade-img-icon"
                                >

                                <span class="shade-name" style="display: block;">
                                    {{ $shade['name'] }}
                                </span>

                                {{-- Shade တစ်ခုချင်းစီအောက်တွင် Stock ပြသရန် --}}
                                <span class="shade-stock" style="display: block; font-size: 12px; color: #2b8a3e; font-weight: bold;">
                                    Stock: {{ $shade['stock'] ?? 15 }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                {{-- ==================== CHECK THIS SHADE ON ME ==================== --}}
                <div class="shade-check-box" id="shade-check-box">
                    <strong style="display:block;margin-bottom:6px;">Will this shade suit me?</strong>
                    <p style="margin:0 0 12px;color:#666;font-size:14px;">Select any shade above, then check that exact shade with your webcam before buying.</p>
                    <button type="button" class="btn primary" id="check-shade-btn">Check This Shade On Me</button>

                    <div class="shade-check-camera" id="shade-check-camera">
                        <video id="shade-webcam" autoplay playsinline></video>
                        <div style="margin-top:10px;display:flex;gap:10px;flex-wrap:wrap;">
                            <button type="button" class="btn primary" id="capture-shade-btn">Capture & Check</button>
                            <button type="button" class="btn" id="close-shade-camera-btn">Close Camera</button>
                        </div>
                        <p id="shade-camera-status" style="margin:9px 0 0;color:#777;font-size:13px;"></p>
                    </div>

                    <div class="shade-match-result" id="shade-match-result">
                        <p style="margin:0 0 6px;"><strong>Your undertone:</strong> <span id="shade-user-tone"></span></p>
                        <p style="margin:0 0 6px;"><strong>Selected shade:</strong> <span id="shade-tested-name"></span></p>
                        <p style="margin:0 0 6px;"><strong>Shade tone:</strong> <span id="shade-tested-tone"></span></p>
                        <p id="shade-match-message" style="margin:0;font-weight:700;"></p>
                    </div>
                </div>


                {{-- ==================== QUANTITY ==================== --}}
                <div class="quantity-section">
                    <label for="quantity">
                        Quantity
                    </label>

                    <div class="quantity-controls">
                        <button
                            type="button"
                            class="qty-btn"
                            id="qty-decrease"
                            aria-label="Decrease quantity"
                        >
                            −
                        </button>

                        <input
                            id="quantity"
                            type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            max="50"
                            required
                        >

                        <button
                            type="button"
                            class="qty-btn"
                            id="qty-increase"
                            aria-label="Increase quantity"
                        >
                            +
                        </button>
                    </div>
                </div>


                {{-- ==================== PRICE CALCULATION ==================== --}}
                <div class="price-calculation-box" id="price-calculation">
                    <div class="calc-row">
                        <span class="calc-label">Unit Price</span>
                        <span class="calc-value" id="unit-price-display">
                            {{ number_format($product['price']) }} MMK
                        </span>
                    </div>

                    <div class="calc-row">
                        <span class="calc-label">Quantity</span>
                        <span class="calc-value" id="qty-display">1</span>
                    </div>

                    <div class="calc-divider"></div>

                    <div class="calc-row calc-total">
                        <span class="calc-label">Total</span>
                        <span class="calc-value" id="total-price-display">
                            {{ number_format($product['price']) }} MMK
                        </span>
                    </div>
                </div>


                {{-- Color Analysis now checks the currently selected shade above. --}}


                {{-- ==================== ADD TO CART ==================== --}}
                <button type="submit" class="add-to-cart-button">
                    Add to Cart
                </button>

            </form>
        </div>

    </div>

</section>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const quantityInput = document.getElementById('quantity');
    const qtyDecreaseBtn = document.getElementById('qty-decrease');
    const qtyIncreaseBtn = document.getElementById('qty-increase');
    const qtyDisplay = document.getElementById('qty-display');
    const totalPriceDisplay = document.getElementById('total-price-display');
    const pageSection = document.querySelector('.product-detail-page');
    
    const unitPrice = Number(pageSection.dataset.price);
    const minQty = 1;
    const maxQty = 50;

    function formatNumber(number) {
        return Number(number).toLocaleString('en-US');
    }

    function updateCalculation() {
        let qty = parseInt(quantityInput.value, 10);
        if (isNaN(qty) || qty < minQty) qty = minQty;
        if (qty > maxQty) qty = maxQty;

        quantityInput.value = qty;
        const total = unitPrice * qty;

        qtyDisplay.textContent = qty;
        totalPriceDisplay.textContent = formatNumber(total) + ' MMK';

        qtyDecreaseBtn.disabled = qty <= minQty;
        qtyIncreaseBtn.disabled = qty >= maxQty;
    }

    qtyIncreaseBtn.addEventListener('click', function (event) {
        event.preventDefault();
        let qty = parseInt(quantityInput.value, 10) || minQty;
        if (qty < maxQty) {
            quantityInput.value = qty + 1;
            updateCalculation();
        }
    });

    qtyDecreaseBtn.addEventListener('click', function (event) {
        event.preventDefault();
        let qty = parseInt(quantityInput.value, 10) || minQty;
        if (qty > minQty) {
            quantityInput.value = qty - 1;
            updateCalculation();
        }
    });

    quantityInput.addEventListener('input', updateCalculation);
    quantityInput.addEventListener('change', updateCalculation);
    updateCalculation();

    const mainImage = document.getElementById('main-product-image');
    const previousButton = document.getElementById('previous-shade');
    const nextButton = document.getElementById('next-shade');
    const selectedShadeName = document.getElementById('selected-shade-name');
    const currentImageNumber = document.getElementById('current-image-number');
    const shadeContainer = document.getElementById('shade-options-container');
    const shadeInputs = Array.from(document.querySelectorAll('input[name="shade_name"]'));

    const checkShadeBtn = document.getElementById('check-shade-btn');
    const cameraBox = document.getElementById('shade-check-camera');
    const webcam = document.getElementById('shade-webcam');
    const captureShadeBtn = document.getElementById('capture-shade-btn');
    const closeShadeCameraBtn = document.getElementById('close-shade-camera-btn');
    const cameraStatus = document.getElementById('shade-camera-status');
    const matchResult = document.getElementById('shade-match-result');
    let shadeCameraStream = null;
    let lastUndertone = null;

    function hexToRgb(hex) {
        if (!hex || !/^#[0-9A-Fa-f]{6}$/.test(hex)) return null;
        return {
            r: parseInt(hex.slice(1, 3), 16),
            g: parseInt(hex.slice(3, 5), 16),
            b: parseInt(hex.slice(5, 7), 16)
        };
    }

    function toneForShade(hex) {
        const rgb = hexToRgb(hex);
        if (!rgb) return 'Neutral Tone';
        const greenBlueDifference = rgb.g - rgb.b;
        if (greenBlueDifference >= 8) return 'Warm Tone';
        if (greenBlueDifference <= -6) return 'Cool Tone';
        return 'Neutral Tone';
    }

    function renderShadeMatch(undertone) {
        const selected = shadeInputs.find(input => input.checked) || shadeInputs[0];
        const shadeName = selected.value;
        const shadeTone = toneForShade(selected.dataset.shadeHex || '');

        document.getElementById('shade-user-tone').textContent = undertone;
        document.getElementById('shade-tested-name').textContent = shadeName;
        document.getElementById('shade-tested-tone').textContent = shadeTone;

        const message = document.getElementById('shade-match-message');
        if (undertone === shadeTone) {
            message.textContent = '✓ Excellent Match — this selected shade suits your undertone.';
            message.style.color = '#2b8a3e';
        } else if (undertone === 'Neutral Tone' || shadeTone === 'Neutral Tone') {
            message.textContent = '✓ Good Match — this shade is flexible and should work well with your undertone.';
            message.style.color = '#8a641b';
        } else {
            message.textContent = '△ Not the Best Match — you can still wear it, but another shade may suit your undertone better.';
            message.style.color = '#b14d68';
        }
        matchResult.style.display = 'block';
    }

    function analyzeFaceFromVideo() {
        const canvas = document.createElement('canvas');
        canvas.width = webcam.videoWidth || 480;
        canvas.height = webcam.videoHeight || 360;
        const ctx = canvas.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(webcam, 0, 0, canvas.width, canvas.height);

        const cropX = Math.floor(canvas.width * 0.30);
        const cropY = Math.floor(canvas.height * 0.30);
        const cropW = Math.max(1, Math.floor(canvas.width * 0.40));
        const cropH = Math.max(1, Math.floor(canvas.height * 0.40));
        const pixels = ctx.getImageData(cropX, cropY, cropW, cropH).data;

        let totalR = 0, totalG = 0, totalB = 0, count = 0;
        for (let i = 0; i < pixels.length; i += 16) {
            if (pixels[i + 3] === 0) continue;
            totalR += pixels[i];
            totalG += pixels[i + 1];
            totalB += pixels[i + 2];
            count++;
        }
        if (!count) throw new Error('Could not analyze the captured image.');

        const r = totalR / count;
        const b = totalB / count;
        if (r > b * 1.15) return 'Warm Tone';
        if (b > r * 1.05) return 'Cool Tone';
        return 'Neutral Tone';
    }

    async function openShadeCamera() {
        cameraBox.style.display = 'block';
        cameraStatus.textContent = 'Opening camera...';
        try {
            shadeCameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
            webcam.srcObject = shadeCameraStream;
            cameraStatus.textContent = 'Camera ready. Use natural light, then press Capture & Check.';
        } catch (error) {
            cameraStatus.textContent = 'Camera permission is required. Please allow camera access and try again.';
        }
    }

    function closeShadeCamera() {
        if (shadeCameraStream) {
            shadeCameraStream.getTracks().forEach(track => track.stop());
            shadeCameraStream = null;
        }
        webcam.srcObject = null;
        cameraBox.style.display = 'none';
    }

    checkShadeBtn.addEventListener('click', openShadeCamera);
    closeShadeCameraBtn.addEventListener('click', closeShadeCamera);
    captureShadeBtn.addEventListener('click', function () {
        try {
            lastUndertone = analyzeFaceFromVideo();
            renderShadeMatch(lastUndertone);
            cameraStatus.textContent = 'Check complete. If you choose another shade, please check again.';
        } catch (error) {
            cameraStatus.textContent = error.message || 'Color analysis failed. Please try again.';
        }
    });

    if (!mainImage || !shadeInputs.length) return;

    let currentIndex = shadeInputs.findIndex(input => input.checked);
    if (currentIndex < 0) currentIndex = 0;

    function selectShade(index) {
        if (index >= shadeInputs.length) index = 0;
        if (index < 0) index = shadeInputs.length - 1;

        currentIndex = index;
        const input = shadeInputs[currentIndex];
        input.checked = true;

        if (input.dataset.image) {
            mainImage.src = input.dataset.image;
        }
        mainImage.alt = input.value;
        selectedShadeName.textContent = input.value;
        currentImageNumber.textContent = currentIndex + 1;

        // A shade must be checked separately. Never reuse a previous analysis result.
        lastUndertone = null;
        matchResult.style.display = 'none';
        document.getElementById('shade-user-tone').textContent = '';
        document.getElementById('shade-tested-name').textContent = '';
        document.getElementById('shade-tested-tone').textContent = '';
        document.getElementById('shade-match-message').textContent = '';

        shadeInputs.forEach((shadeInput, idx) => {
            const option = shadeInput.closest('.shade-option');
            if (option) {
                option.classList.toggle('active', idx === currentIndex);
            }
        });

        const selectedOption = input.closest('.shade-option');
        if (selectedOption) {
            selectedOption.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
        }
    }

    previousButton.addEventListener('click', (e) => { e.preventDefault(); selectShade(currentIndex - 1); });
    nextButton.addEventListener('click', (e) => { e.preventDefault(); selectShade(currentIndex + 1); });

    shadeInputs.forEach((input, index) => {
        input.addEventListener('change', () => selectShade(index));
    });

    selectShade(currentIndex);
});
</script>

@endsection