@extends('layouts.guest')

@section('title', 'Forgot password')
@section('heading', 'Reset your password')

@section('content')
    <p class="text-secondary">Enter the email address on your account and we will send you a link to choose a new password.</p>

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" autocomplete="username" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Send reset link</button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('admin.login') }}" class="small text-decoration-none" style="color: var(--ajango-blue);">Back to sign in</a>
    </div>
@endsection
