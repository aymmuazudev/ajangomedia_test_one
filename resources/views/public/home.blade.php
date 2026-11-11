@extends('layouts.public')

@section('content')
    @if ($breakingNews->isNotEmpty())
        <div class="breaking-bar border-bottom">
            <div class="container d-flex align-items-center gap-3 py-2">
                <span class="breaking-label">Breaking</span>
                <div class="breaking-ticker flex-grow-1">
                    <div class="ticker-track">
                        @foreach ($breakingNews as $item)
                            <a href="{{ route('articles.show', $item) }}" class="ticker-item">{{ $item->title }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($heroStories->isEmpty())
        <section class="py-5">
            <div class="container">
                <div class="empty-state text-center py-5 px-3">
                    <i class="bi bi-newspaper display-5 d-block mb-3" aria-hidden="true"></i>
                    <h1 class="h3 mb-2">No stories have been published yet</h1>
                    <p class="text-secondary mb-0">Stories will appear here as soon as our editors publish them.</p>
                </div>
            </div>
        </section>
    @else
        <div class="container">
            <x-ad-slot slotName="home_top" formatClass="d-flex justify-content-center" />
        </div>

        <section class="py-4" aria-label="Top stories">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <x-article-card :article="$heroStories->first()" size="lead" />
                    </div>
                    <div class="col-lg-4">
                        @foreach ($heroStories->skip(1) as $story)
                            <div class="hero-secondary pb-3 mb-3 border-bottom">
                                <x-article-card :article="$story" size="compact" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($latestStories->isNotEmpty())
        <section class="py-4">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="section-heading mb-4">
                            <h2 class="h4 mb-0">Latest news</h2>
                        </div>
                        <div class="row g-4">
                            @foreach ($latestStories as $story)
                                <div class="col-md-6">
                                    <x-article-card :article="$story" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($mostRead->isNotEmpty())
                        <aside class="col-lg-4" aria-labelledby="most-read-heading">
                            <div class="section-heading mb-4">
                                <h2 class="h4 mb-0" id="most-read-heading">Most read</h2>
                            </div>
                            <ol class="list-unstyled mb-0">
                                @foreach ($mostRead as $story)
                                    <li class="most-read-item d-flex gap-3 pb-3 mb-3 border-bottom">
                                        <span class="most-read-rank">{{ $loop->iteration }}</span>
                                        <div>
                                            <a href="{{ route('articles.show', $story) }}" class="most-read-link fw-semibold text-decoration-none">{{ $story->title }}</a>
                                            <div class="small text-secondary">{{ number_format($story->view_count) }} views</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </aside>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @foreach ($categorySections as $category)
        <section class="py-4">
            <div class="container">
                <div class="section-heading d-flex justify-content-between align-items-baseline mb-4">
                    <h2 class="h4 mb-0">{{ $category->name }}</h2>
                    <a href="{{ route('categories.show', $category) }}" class="section-link small fw-semibold text-decoration-none">View all</a>
                </div>
                <div class="row g-4">
                    @foreach ($category->latestPublished as $story)
                        <div class="col-sm-6 col-lg-3">
                            <x-article-card :article="$story" size="compact" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach
@endsection
