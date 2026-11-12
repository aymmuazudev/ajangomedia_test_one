@extends('emails.layout')

@section('content')
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>An admin account has been created for you on AJANGO MEDIA. You have been assigned the role of <strong>{{ $user->roleLabel() }}</strong>.</p>

    <div class="email-meta">
        <p><strong>Account email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ $user->roleLabel() }}</p>
    </div>

    @if ($resetUrl)
        <p>Before you can sign in, you need to set your password. Use the link below to choose your password and activate your account.</p>
        <a href="{{ $resetUrl }}" class="email-button">Set your password</a>
        <p>If the button does not work, copy and paste this link:</p>
        <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
    @else
        <p>Sign in with your email address and the password provided to you by an administrator.</p>
        <a href="{{ url(route('admin.login', [], false)) }}" class="email-button">Sign in to your account</a>
    @endif
@endsection
