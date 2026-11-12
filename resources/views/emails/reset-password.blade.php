@extends('emails.layout')

@section('content')
    <h1>Hello {{ $name }},</h1>
    <p>You are receiving this email because a password reset was requested for your AJANGO MEDIA admin account.</p>

    <a href="{{ $url }}" class="email-button">Reset password</a>

    <p>If the button above does not work, copy and paste this link into your browser:</p>
    <p><a href="{{ $url }}">{{ $url }}</a></p>

    <p>This password reset link will expire in {{ $count }} minutes.</p>

    <p>If you did not request a password reset, no further action is required. Your account remains secure.</p>
@endsection
