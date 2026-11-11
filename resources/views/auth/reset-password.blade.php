@extends('v2.layout')

@section('content')
<section class="panel" style="max-width:450px;margin:60px auto;">
    <h2>Reset password</h2>

    @foreach($errors->all() as $error)
        <p role="alert">{{ $error }}</p>
    @endforeach

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <label class="field">
            Email address
            <input
                type="email"
                name="email"
                value="{{ old('email', $email) }}"
                autocomplete="email"
                required
            >
        </label>

        <label class="field">
            New password
            <input
                type="password"
                name="password"
                minlength="8"
                autocomplete="new-password"
                required
            >
        </label>

        <label class="field">
            Confirm new password
            <input
                type="password"
                name="password_confirmation"
                minlength="8"
                autocomplete="new-password"
                required
            >
        </label>

        <p class="small muted">Use at least 8 characters.</p>

        <button type="submit">Reset password</button>
    </form>
</section>
@endsection