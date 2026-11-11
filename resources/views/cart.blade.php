@extends('v2.layout')
@section('content')
<div class="container">
    <h2>Shopping Cart</h2>
    @if($promotion)
        <div style="background:#fdf2f4;border:1px dashed #d15c7a;color:#d15c7a;padding:12px;border-radius:8px;margin-bottom:20px;text-align:center;font-weight:500;">
            🎉 <strong>{{ $promotion->name }}</strong> — {{ rtrim(rtrim(number_format($promotion->effective_discount_percent,2), '0'), '.') }}% OFF applied.
        </div>
    @endif
    @if(empty($cart))
        <p>Your cart is empty. <a href="{{ route('home') }}">Shop now</a></p>
    @else
        <div class="cart-list">
            @foreach($cart as $key => $item)
                <article class="cart-item">
                    <div><h3>{{ $item['name'] }}</h3><p>Shade: {{ $item['shade'] }}</p><p>{{ number_format($item['price']) }} MMK each</p></div>
                    <form action="{{ route('cart.update', $key) }}" method="POST" class="quantity-form">@csrf @method('PATCH')
                        <label for="quantity-{{ $key }}">Quantity</label><input id="quantity-{{ $key }}" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10" required><button type="submit" class="btn">Update</button>
                    </form>
                    <strong>{{ number_format($item['price'] * $item['quantity']) }} MMK</strong>
                    <form action="{{ route('cart.remove', $key) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Remove</button></form>
                </article>
            @endforeach
        </div><hr>
        <p>Subtotal: {{ number_format($subtotal) }} MMK</p>
        @if($discount_applied)<p>{{ $promotion->name }} ({{ rtrim(rtrim(number_format($promotion->effective_discount_percent,2), '0'), '.') }}%): -{{ number_format($discount_amount) }} MMK</p>@endif
        <h3>Total: {{ number_format($final_total) }} MMK</h3>
        <div class="cart-actions"><form action="{{ route('cart.clear') }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary">Clear Cart</button></form><a href="{{ route('checkout') }}" class="btn primary">Proceed to Checkout</a></div>
    @endif
</div>
@endsection
