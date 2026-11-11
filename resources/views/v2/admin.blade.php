@extends('v2.layout')

@section('content')
    @php
        $product = $editing?->product;

        $categories = collect([
            'Glossy & Dewy',
            'Matte & Velvet',
            'Balm & Care',
            'Cushion',
        ]);
           
    @endphp

    <div class="eyebrow">Studio management</div>
    <h1>Your shade library.</h1>

    <div class="actions">
        <a class="button secondary" href="{{ route('admin.dashboard') }}">
            Dashboard
        </a>
        <a class="button secondary" href="{{ route('admin.orders.index') }}">
            Orders
        </a>
        <a class="button secondary" href="{{ route('admin.promotions.index') }}">
            Promotions
        </a>
        <a class="button secondary" href="{{ route('admin.customers.index') }}">
            Customers
        </a>
    </div>

    <p class="muted">
        One product can have many shades. Product-level fields apply to every
        shade in that product. Imported shades stay out of recommendations
        until reviewed.
    </p>

    <section class="panel">
        <h2>{{ $editing ? 'Edit shade' : 'Add product / shade' }}</h2>

        @if($errors->any())
            <div class="notice" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="post"
            enctype="multipart/form-data"
            action="{{ $editing
                ? route('admin.products.update', $editing->id)
                : route('admin.products.store') }}"
        >
            @csrf

            @if($editing)
                @method('PUT')
            @endif

            <div class="form-grid">

                {{-- Product name dropdown --}}
                <label class="field">
                    Product name

                    <select name="product_name" required>
                        <option value="">Choose a product</option>

                        @foreach($products->unique('name') as $item)
                            <option
                                value="{{ $item->name }}"
                                @selected(
                                    old('product_name', $product?->name)
                                    === $item->name
                                )
                            >
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    Brand
                    <input
                        name="brand"
                        value="{{ old('brand', $product?->brand ?? 'rom&nd') }}"
                        maxlength="80"
                        required
                    >
                </label>

                {{-- Category dropdown --}}
                <label class="field">
                    Category

                    <select name="category" required>
                        <option value="">Choose a category</option>

                        @foreach($categories as $category)
                            <option
                                value="{{ $category }}"
                                @selected(
                                    old('category', $product?->category)
                                    === $category
                                )
                            >
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    Shade name
                    <input
                        name="shade_name"
                        value="{{ old('shade_name', $editing?->shade_name) }}"
                        maxlength="100"
                        required
                    >
                </label>

                <label class="field">
                    Shade code
                    <input
                        name="shade_code"
                        value="{{ old('shade_code', $editing?->shade_code) }}"
                        maxlength="40"
                    >
                </label>

                <label class="field">
                    Price (MMK)
                    <input
                        type="number"
                        name="price"
                        value="{{ old('price', $editing?->price ?? 20000) }}"
                        min="0"
                        max="99999999"
                        step="0.01"
                        required
                    >
                </label>

                <label class="field">
                    Stock
                    <input
                        type="number"
                        name="stock"
                        value="{{ old('stock', $editing?->stock ?? 0) }}"
                        min="0"
                        max="100000"
                        step="1"
                        required
                    >
                </label>

                @php
                    $selects = [
                        'product_type' => [
                            'Product type',
                            ['lipstick','cushion'],
                            $product?->product_type ?? 'lipstick',
                        ],
                        'finish' => [
                            'Finish',
                            ['matte', 'glossy', 'natural', 'dewy'],
                            $product?->finish ?? 'natural',
                        ],
                        'coverage' => [
                            'Coverage',
                            ['light', 'medium', 'full'],
                            $product?->coverage ?? 'medium',
                        ],
                        'undertone' => [
                            'Undertone',
                            ['unknown', 'warm', 'cool', 'neutral'],
                            $editing?->undertone ?? 'unknown',
                        ],
                        'color_family' => [
                            'Lip color family',
                            ['other', 'nude', 'pink', 'red', 'coral', 'berry', 'brown'],
                            $editing?->color_family ?? 'other',
                        ],
                    ];
                @endphp

                @foreach($selects as $name => [$label, $options, $value])
                    <label class="field">
                        {{ $label }}

                        <select name="{{ $name }}">
                            @foreach($options as $option)
                                <option
                                    value="{{ $option }}"
                                    @selected(old($name, $value) === $option)
                                >
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                @endforeach

                <label class="field">
                    Skin depth group (cushion)

                    <select name="depth">
                        <option value="">Not assigned</option>

                        @foreach([
                            1 => 'Fair',
                            2 => 'Light',
                            3 => 'Light-medium',
                            4 => 'Medium',
                            5 => 'Tan',
                            6 => 'Deep',
                        ] as $v => $label)
                            <option
                                value="{{ $v }}"
                                @selected(
                                    (string) old('depth', $editing?->depth)
                                    === (string) $v
                                )
                            >
                                {{ $v }} · {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    Shade HEX (review and adjust)

                    <input
                        name="shade_hex"
                        id="shade-hex"
                        value="{{ old('shade_hex', $editing?->shade_hex ?? '#BE7A70') }}"
                        pattern="#[0-9a-fA-F]{6}"
                        required
                    >

                    <input
                        type="color"
                        id="shade-picker"
                        value="{{ old('shade_hex', $editing?->shade_hex ?? '#BE7A70') }}"
                    >
                </label>

                <label class="field">
                    Swatch image (optional)

                    <input
                        type="file"
                        name="image"
                        id="swatch-image"
                        accept="image/jpeg,image/png,image/webp,image/avif"
                    >

                    <span class="small muted">
                        Use an actual swatch image, not packaging.
                    </span>

                    <canvas
                        id="swatch-canvas"
                        class="preview"
                        hidden
                    ></canvas>

                    <span
                        id="swatch-status"
                        class="small muted"
                        role="status"
                    >
                        Upload a swatch, then click the swatch area to suggest
                        a color. Review before saving.
                    </span>
                </label>

                <label class="field">
                    Choose a supplied product photo

                    <select name="existing_image" id="library-image">
                        <option value="">
                            Keep current image / use uploaded file
                        </option>

                        @foreach(config('image_library', []) as $image)
                            <option
                                value="{{ $image['path'] }}"
                                @selected(old('existing_image') === $image['path'])
                            >
                                {{ $image['label'] }}
                            </option>
                        @endforeach
                    </select>

                    <img
                        id="library-preview"
                        class="product-image"
                        hidden
                        alt="Selected supplied image"
                    >

                    <span class="small muted">
                        Match the series and shade code yourself.
                        Upload takes priority.
                        Picking a photo does not verify shade color.
                    </span>
                </label>

                <label class="field wide">
                    Shade source / measurement notes

                    <textarea
                        name="source"
                        maxlength="1000"
                    >{{ old('source', $editing?->source) }}</textarea>
                </label>

                <label class="field wide">
                    Product description

                    <textarea
                        name="description"
                        maxlength="2000"
                    >{{ old('description', $product?->description) }}</textarea>
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="verified"
                        value="1"
                        @checked(old('verified', $editing?->verified))
                    >
                    Reviewed and eligible for recommendations
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="is_demo"
                        value="1"
                        @checked(old('is_demo', $product?->is_demo))
                    >
                    Illustrative demo product (all its shades)
                </label>
            </div>

            <p class="small muted">
                Reviewed does not mean laboratory-measured.
                Record the source honestly.
                Cushion also needs a depth group and undertone.
            </p>

            <button type="submit">Save shade</button>

            <a href="{{ route('admin.products.index') }}">
                Reset form
            </a>
        </form>
    </section>

    <section class="panel" style="margin-top:25px">
        <h2>Products & shades</h2>

        <div class="scroll">
            <table>
                <thead>
                    <tr>
                        <th>Product / shade</th>
                        <th>Color</th>
                        <th>Tags</th>
                        <th>Price / stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($products as $p)
                        @foreach($p->shades as $s)
                            <tr>
                                <td>
                                    <strong>{{ $p->name }}</strong><br>
                                    {{ $s->shade_name }}<br>

                                    <span class="small muted">
                                        {{ $p->brand }} · {{ $p->product_type }}

                                        @if($p->is_demo)
                                            · DEMO
                                        @endif
                                    </span>
                                </td>

                                <td>
                                    <span
                                        class="swatch"
                                        style="--c:{{ $s->shade_hex ?? '#ddd' }}"
                                    ></span><br>

                                    {{ $s->shade_hex }}
                                </td>

                                <td>
                                    {{ $s->undertone }}
                                    · Depth {{ $s->depth ?? '—' }}<br>

                                    {{ $s->verified ? 'Reviewed' : 'Needs review' }}
                                </td>

                                <td>
                                    {{ number_format($s->price) }} MMK<br>
                                    {{ $s->stock }} units
                                </td>

                                <td>
                                    <a href="{{ route('admin.products.index', ['edit' => $s->id]) }}">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.products.destroy', $s->id) }}"
                                        method="post"
                                        onsubmit="return confirm('Delete this shade? Existing order records remain.')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-button">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel" style="margin-top:25px">
        <h2>Customer feedback</h2>

        @forelse($feedback as $f)
            <p>
                {{ $f->name }} ·
                <strong>{{ str_replace('_', ' ', $f->feedback) }}</strong>
            </p>
        @empty
            <p>No feedback yet.</p>
        @endforelse
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-swatches.js') }}"></script>
@endpush