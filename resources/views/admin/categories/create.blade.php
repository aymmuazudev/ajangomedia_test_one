@extends('layouts.admin')

@section('title', 'New section')
@section('heading', 'New section')

@section('content')
    @include('admin.categories._form', [
        'category' => $category,
        'parents' => $parents,
        'action' => route('admin.categories.store'),
        'method' => 'POST',
    ])
@endsection
