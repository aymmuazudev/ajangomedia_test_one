@extends('emails.layout-plain')

@section('content')
Welcome, {{ $user->name }}!

An admin account has been created for you on AJANGO MEDIA. You have been assigned the role of {{ $user->roleLabel() }}.

Account email: {{ $user->email }}
Role: {{ $user->roleLabel() }}

@if ($resetUrl)
Before you can sign in, you need to set your password. Use the link below to choose your password and activate your account.

Set your password: {{ $resetUrl }}
@else
Sign in with your email address and the password provided to you by an administrator.

Sign in: {{ url(route('admin.login', [], false)) }}
@endif
@endsection
