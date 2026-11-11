@extends('layouts.admin')

@section('title', 'Advertisement')
@section('heading', 'New advertisement')

@section('content')
    @include('admin.advertisements._form', ['advertisement' => $advertisement])
@endsection
