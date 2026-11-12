@extends('emails.layout-plain')

@section('content')
Hello {{ $name }},

You are receiving this email because a password reset was requested for your AJANGO MEDIA admin account.

Reset password: {{ $url }}

This password reset link will expire in {{ $count }} minutes.

If you did not request a password reset, no further action is required. Your account remains secure.
@endsection
