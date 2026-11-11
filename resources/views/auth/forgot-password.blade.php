@extends('v2.layout')

@section('content')
<section class="panel" style="max-width:450px;margin:60px auto;">
    <h2>Forgot password?</h2>

    <p class="muted">
        Enter your registered email to receive a reset link.
    </p>

    @if(session('status'))
        <div class="notice">{{ session('status') }}</div>
    @endif

    @foreach($errors->all() as $error)
        <p role="alert">{{ $error }}</p>
    @endforeach

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label class="field">
            Email address
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                required
            >
        </label>

        <button type="submit">Send reset link</button>
    </form>

    <p><a href="{{ route('login') }}">← Back to login</a></p>
</section>
@endsection