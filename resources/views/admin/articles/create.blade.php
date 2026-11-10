@extends('layouts.admin')

@section('title', 'New story')
@section('heading', 'New story')

@section('content')
    @include('admin.articles._form', [
        'article' => $article,
        'categories' => $categories,
        'mediaFiles' => $mediaFiles,
        'tagsText' => $tagsText,
        'action' => route('admin.articles.store'),
        'method' => 'POST',
    ])
@endsection
