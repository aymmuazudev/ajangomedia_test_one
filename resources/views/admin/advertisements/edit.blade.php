@extends('layouts.admin')

@section('title', 'Advertisement')
@section('heading', 'Edit advertisement')

@section('content')
    @include('admin.advertisements._form', ['advertisement' => $advertisement])
@endsection
