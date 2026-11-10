@extends('layouts.public')

@section('title', $article->title.' | AJANGO MEDIA')
@section('description', $article->summary ?? '')

@section('content')
    <article class="py-4">
        <div class="container article-page">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb small mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    @if ($article->category)
                        <li class="breadcrumb-item"><a href="{{ route('categories.show', $article->category) }}">{{ $article->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($article->title, 60) }}</li>
                </ol>
            </nav>

            <header class="mb-4">
                @if ($article->category)
                    <div class="category-label mb-2">{{ $article->category->name }}</div>
                @endif
                <h1 class="article-page__title mb-3">{{ $article->title }}</h1>
                @if ($article->subheadline)
                    <p class="article-page__subheadline lead mb-3">{{ $article->subheadline }}</p>
                @endif
                <div class="small text-secondary d-flex flex-wrap gap-2 align-items-center">
                    @if ($article->author)
                        <span class="fw-semibold text-body">{{ $article->author->name }}</span>
                        <span aria-hidden="true">&middot;</span>
                    @endif
                    @if ($article->published_at)
                        <time datetime="{{ $article->published_at->toIso8601String() }}">
                            {{ $article->published_at->timezone(config('app.timezone'))->format('j M Y, H:i') }}
                        </time>
                        <span aria-hidden="true">&middot;</span>
                    @endif
                    <span>{{ $article->readingMinutes() }} min read</span>
                </div>
            </header>

            @if ($article->featured_image_path)
                <figure class="mb-4">
                    <img src="{{ Storage::disk('public')->url($article->featured_image_path) }}"
                         alt="{{ $article->title }}" class="img-fluid w-100 article-page__image">
                </figure>
            @endif

            @if ($article->summary)
                <p class="article-page__summary fw-semibold mb-4">{{ $article->summary }}</p>
            @endif

            <div class="article-page__body">
                {!! nl2br(e($article->body)) !!}
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1" aria-hidden="true"></i>Print this story
                </button>
            </div>
        </div>
    </article>

    @if ($relatedArticles->isNotEmpty())
        <section class="py-4 bg-light border-top">
            <div class="container">
                <div class="section-heading mb-4">
                    <h2 class="h4 mb-0">More from {{ $article->category?->name ?? 'AJANGO MEDIA' }}</h2>
                </div>
                <div class="row g-4">
                    @foreach ($relatedArticles as $story)
                        <div class="col-sm-6 col-lg-4">
                            <x-article-card :article="$story" size="compact" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
