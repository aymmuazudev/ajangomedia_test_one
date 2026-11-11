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

            <div class="row g-4">
                <div class="col-lg-8">

            @if ($article->featured_image_path)
                <figure class="mb-4">
                    <img src="{{ route('media.show', ['path' => $article->featured_image_path]) }}"
                         alt="{{ $article->title }}" class="img-fluid w-100 article-page__image">
                </figure>
            @endif

            @if ($article->summary)
                <p class="article-page__summary fw-semibold mb-4">{{ $article->summary }}</p>
            @endif

            <div class="article-page__body">
                {!! nl2br(e($article->body)) !!}
            </div>

            <x-ad-slot slotName="sidebar" formatClass="d-lg-none" />

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1" aria-hidden="true"></i>Print this story
                </button>
            </div>

            <x-ad-slot slotName="article_inline" formatClass="d-flex justify-content-center" />

            <section class="mt-5 pt-4 border-top" aria-labelledby="comments-heading">
                <h2 id="comments-heading" class="h4 mb-3">Comments <span class="text-secondary fs-6">({{ $approvedComments->count() }})</span></h2>

                @if (session('comment_status'))
                    <div class="alert alert-success" role="status">{{ session('comment_status') }}</div>
                @endif

                @if ($approvedComments->isNotEmpty())
                    <div class="vstack gap-3 mb-4">
                        @foreach ($approvedComments as $comment)
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="fw-semibold">{{ $comment->author_name }}</span>
                                        <time class="text-secondary" datetime="{{ $comment->created_at->toIso8601String() }}">
                                            {{ $comment->created_at->timezone(config('app.timezone'))->format('j M Y, H:i') }}
                                        </time>
                                    </div>
                                    <p class="mb-0 text-break">{{ $comment->body }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary">No comments yet. Be the first to share your view.</p>
                @endif

                <form method="POST" action="{{ route('comments.store', $article) }}" class="card border-0 shadow-sm">
                    @csrf
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3">Join the conversation</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="comment_name" class="form-label">Your name</label>
                                <input type="text" id="comment_name" name="author_name" value="{{ old('author_name') }}" required maxlength="80"
                                       class="form-control @error('author_name') is-invalid @enderror">
                                @error('author_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="comment_email" class="form-label">Email <span class="text-secondary">(optional, not shown)</span></label>
                                <input type="email" id="comment_email" name="author_email" value="{{ old('author_email') }}" maxlength="255"
                                       class="form-control @error('author_email') is-invalid @enderror">
                                @error('author_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="comment_body" class="form-label">Your comment</label>
                                <textarea id="comment_body" name="body" rows="4" required maxlength="2000"
                                          class="form-control @error('body') is-invalid @enderror">{{ old('body') }}</textarea>
                                @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Post comment</button>
                        <div class="form-text mt-2">Comments are reviewed by a moderator before they appear.</div>
                    </div>
                </form>
            </section>
                </div>

                <div class="col-lg-4">
                    <x-ad-slot slotName="sidebar" />
                </div>
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
