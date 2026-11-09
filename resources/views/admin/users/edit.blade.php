@extends('layouts.admin')

@section('title', 'Edit user')
@section('heading', 'Edit '.$user->name)

@section('content')
    @include('admin.users._form', [
        'action' => route('admin.users.update', $user),
        'method' => 'PUT',
        'user' => $user,
        'roles' => $roles,
    ])
@endsection
