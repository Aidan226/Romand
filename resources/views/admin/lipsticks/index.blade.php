@extends('admin.layouts.app')
@section('title','Products & Stock')
@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:end;gap:15px;margin-bottom:17px;"><div><h3 style="margin-bottom:5px;">Add Product / Shade</h3><span class="small">Products are saved directly to the lipsticks database table.</span></div></div>
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="form-grid">@csrf
        <div><label>Product Name / Shade</label><input name="name" placeholder="e.g. Juicy Lasting Tint - New Shade" required></div>
        <div><label>Category</label><select name="category" required>@foreach($categories as $category)<option value="{{ $category }}">{{ $category }}</option>@endforeach</select></div>
        <div><label>Price (MMK)</label><input type="number" name="price" min="0" required></div>
        <div><label>Stock</label><input type="number" name="stock" min="0" value="15" required></div>
        <div><label>Shade Image</label><input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/avif" required class="shade-image-input"><input type="hidden" name="shade_hex" class="shade-hex-input"><span class="small shade-detect-status" style="display:block;margin-top:5px;">The shade color will be detected automatically from the uploaded image.</span></div>
        <button class="btn primary">Add</button>
    </form>
</div>

<div class="section table-wrap"><table><thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Replace Image</th><th>Actions</th></tr></thead><tbody>
@forelse($lipsticks as $lipstick)
<tr>
<form method="POST" action="{{ route('admin.products.update',$lipstick->id) }}" enctype="multipart/form-data">@csrf @method('PUT')
<td>{{ $lipstick->id }}</td>
<td>@if($lipstick->image)<img class="product-thumb" src="{{ asset('images/'.$lipstick->image) }}" alt="">@else<span class="small">Original image</span>@endif</td>
<td><input name="name" value="{{ $lipstick->name }}" required></td>
<td><select name="category">@foreach($categories as $category)<option value="{{ $category }}" @selected($lipstick->category===$category)>{{ $category }}</option>@endforeach</select></td>
<td><input type="number" name="price" min="0" value="{{ (int)$lipstick->price }}" required></td>
<td><input type="number" name="stock" min="0" value="{{ $lipstick->stock }}" required></td>
<td><input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/avif" class="shade-image-input"><input type="hidden" name="shade_hex" value="{{ $lipstick->shade_hex }}" class="shade-hex-input"><span class="small shade-detect-status">Leave empty to keep the current image and detected shade color.</span></td>
<td><div class="actions"><button class="btn primary">Save</button></form><form method="POST" action="{{ route('admin.products.destroy',$lipstick->id) }}" onsubmit="return confirm('Delete this product / shade?')">@csrf @method('DELETE')<button class="btn danger">Delete</button></form></div></td>
</tr>
@empty<tr><td colspan="8">No products found.</td></tr>@endforelse
</tbody></table></div>
<script>
(function () {
    function rgbToHex(r, g, b) {
        return '#' + [r, g, b].map(v => Math.max(0, Math.min(255, Math.round(v))).toString(16).padStart(2, '0')).join('').toUpperCase();
    }

    function detectLipstickColor(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onerror = () => reject(new Error('Could not read image.'));
            reader.onload = e => {
                const img = new Image();
                img.onerror = () => reject(new Error('Could not load image.'));
                img.onload = () => {
                    const maxSide = 320;
                    const scale = Math.min(1, maxSide / Math.max(img.width, img.height));
                    const canvas = document.createElement('canvas');
                    canvas.width = Math.max(1, Math.round(img.width * scale));
                    canvas.height = Math.max(1, Math.round(img.height * scale));
                    const ctx = canvas.getContext('2d', { willReadFrequently: true });
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    const pixels = ctx.getImageData(0, 0, canvas.width, canvas.height).data;

                    // Find the dominant colourful region instead of averaging white/grey background.
                    const bins = new Map();
                    for (let i = 0; i < pixels.length; i += 16) {
                        const r = pixels[i], g = pixels[i + 1], b = pixels[i + 2], a = pixels[i + 3];
                        if (a < 200) continue;
                        const max = Math.max(r, g, b), min = Math.min(r, g, b);
                        const brightness = (r + g + b) / (3 * 255);
                        const saturation = max === 0 ? 0 : (max - min) / max;
                        if (brightness > 0.94 || brightness < 0.10 || saturation < 0.16) continue;

                        const qr = Math.floor(r / 32), qg = Math.floor(g / 32), qb = Math.floor(b / 32);
                        const key = `${qr}-${qg}-${qb}`;
                        const entry = bins.get(key) || { score: 0, r: 0, g: 0, b: 0, n: 0 };
                        const weight = 0.5 + saturation;
                        entry.score += weight;
                        entry.r += r; entry.g += g; entry.b += b; entry.n++;
                        bins.set(key, entry);
                    }

                    let best = null;
                    for (const entry of bins.values()) {
                        if (!best || entry.score > best.score) best = entry;
                    }
                    if (!best || best.n < 3) {
                        reject(new Error('Could not detect a clear lipstick shade. Please use a clearer shade image.'));
                        return;
                    }
                    resolve(rgbToHex(best.r / best.n, best.g / best.n, best.b / best.n));
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    document.querySelectorAll('form').forEach(form => {
        const imageInput = form.querySelector('.shade-image-input');
        const hexInput = form.querySelector('.shade-hex-input');
        const status = form.querySelector('.shade-detect-status');
        if (!imageInput || !hexInput) return;

        imageInput.addEventListener('change', async () => {
            if (!imageInput.files || !imageInput.files[0]) return;
            if (status) status.textContent = 'Detecting shade color from image...';
            try {
                const hex = await detectLipstickColor(imageInput.files[0]);
                hexInput.value = hex;
                if (status) status.innerHTML = `Detected shade color: <strong>${hex}</strong>`;
            } catch (error) {
                hexInput.value = '';
                imageInput.value = '';
                if (status) status.textContent = error.message;
                alert(error.message);
            }
        });

        form.addEventListener('submit', e => {
            if (imageInput.files && imageInput.files.length && !hexInput.value) {
                e.preventDefault();
                alert('Please wait for the shade color to be detected from the image.');
            }
        });
    });
})();
</script>
@endsection
