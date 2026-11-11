@extends('layouts.app')

@section('content')
<div class="container" style="text-align: center; padding: 50px;">
    <h2>Thank You for Your Order, {{ $name ?? 'Customer' }}!</h2>
    <p>Your rom&nd order has been successfully placed.</p>
    <a href="{{ route('home') }}" class="btn primary">Back to Home</a>
</div>
@endsection