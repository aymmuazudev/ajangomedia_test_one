@props(['article', 'size' => 'default'])

@php
    $titleClass = match ($size) {
        'lead' => 'h2 mb-2',
        'compact' => 'h6 mb-1',
        default => 'h5 mb-2',
    };
@endphp

<article class="article-card h-100">
    <a href="{{ route('articles.show', $article) }}" class="article-card__link d-block text-decoration-none">
        <div class="ratio ratio-16x9 article-card__media mb-3">
            @if ($article->featured_image_path)
                <img src="{{ route('media.show', ['path' => $article->featured_image_path]) }}"
                     alt="{{ $article->title }}" loading="lazy" class="object-fit-cover">
            @else
                <div class="article-card__fallback d-flex align-items-end p-3">
                    <span class="fw-bold">AJANGO MEDIA</span>
                </div>
            @endif
        </div>

        @if ($article->category)
            <div class="category-label mb-1">{{ $article->category->name }}</div>
        @endif

        <h3 class="article-card__title {{ $titleClass }}">{{ $article->title }}</h3>

        @if ($size !== 'compact' && $article->summary)
            <p class="article-card__summary mb-2 text-secondary">{{ $article->summary }}</p>
        @endif

        <div class="article-card__meta small text-secondary">
            @if ($article->author)
                <span>{{ $article->author->name }}</span>
                <span aria-hidden="true">&middot;</span>
            @endif
            @if ($article->published_at)
                <time datetime="{{ $article->published_at->toIso8601String() }}">
                    {{ $article->published_at->timezone(config('app.timezone'))->format('j M Y') }}
                </time>
            @endif
        </div>
    </a>
</article>
