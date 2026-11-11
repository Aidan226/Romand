@extends('v2.layout')

@section('content')
<section class="panel" style="max-width:450px;margin:60px auto;">
    <h2 style="text-align:center;">Login to Continue</h2>

    <p class="muted" style="text-align:center;">
        Please log in to your account.
    </p>

    @if(session('status'))
        <div class="notice">{{ session('status') }}</div>
    @endif

    @foreach($errors->all() as $error)
        <p role="alert">{{ $error }}</p>
    @endforeach

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <label class="field">
            Email address
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="username"
                required
            >
        </label>

        <label class="field">
            Password
            <input
                type="password"
                name="password"
                autocomplete="current-password"
                required
            >
        </label>

        <p style="text-align:right;">
            <a href="{{ route('password.request') }}">
                Forgot password?
            </a>
        </p>

        <button type="submit">Login</button>

        <p>
            Don't have an account?
            <a href="{{ route('register') }}">Register here</a>
        </p>
    </form>
</section>
@endsection