@extends('v2.layout')

@section('content')
<div class="container checkout" style="max-width: 450px; margin-top: 60px; margin-bottom: 60px;">
    <h2 style="text-align: center; border-bottom: none; margin-bottom: 10px;">Create an Account</h2>
    
    <p style="text-align: center; color: #666; margin-bottom: 25px;">
        Sign up to proceed to checkout and track your orders.
    </p>

    @if ($errors->any())
        <div class="validation-errors">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST" class="shipping-form">
        @csrf
        
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" required>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required>
        
        <!-- Password ရိုက်တဲ့အခါ Confirm ပါ တစ်ခါတည်း လုပ်ခိုင်းပါမယ် -->
        <input type="password" name="password" placeholder="Password (Min: 8 characters)" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        
        <button type="submit" class="btn primary" style="margin-top: 15px;">Register</button>
    </form>

    <p style="text-align: center; margin-top: 20px;">
        Already have an account? <a href="{{ route('login') }}" style="color: #d15c7a; font-weight: 600;">Login here</a>
    </p>
</div>
@endsection