@extends('layouts.admin')

@section('title', 'Add user')
@section('heading', 'Add user')

@section('content')
    @include('admin.users._form', [
        'action' => route('admin.users.store'),
        'method' => 'POST',
        'user' => null,
        'roles' => $roles,
    ])
@endsection
