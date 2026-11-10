@extends('layouts.admin')

@section('title', 'Edit section')
@section('heading', 'Edit section')

@section('content')
    @include('admin.categories._form', [
        'category' => $category,
        'parents' => $parents,
        'action' => route('admin.categories.update', $category),
        'method' => 'PUT',
    ])
@endsection
