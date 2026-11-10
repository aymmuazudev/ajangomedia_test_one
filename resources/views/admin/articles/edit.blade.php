@extends('layouts.admin')

@section('title', 'Edit story')
@section('heading', 'Edit story')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <p class="text-secondary mb-0">{{ $article->title }}</p>
        @if ($article->isPublic())
            <a href="{{ route('articles.show', $article) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-box-arrow-up-right me-1"></i>View on site
            </a>
        @endif
    </div>

    @include('admin.articles._form', [
        'article' => $article,
        'categories' => $categories,
        'mediaFiles' => $mediaFiles,
        'tagsText' => $tagsText,
        'action' => route('admin.articles.update', $article),
        'method' => 'PUT',
    ])

    @can('delete', $article)
        <div class="mt-5 pt-4 border-top">
            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                  onsubmit="return confirm('Delete this story? It will be removed from the site.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash me-1"></i>Delete story
                </button>
            </form>
        </div>
    @endcan
@endsection
