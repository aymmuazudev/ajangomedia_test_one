@extends('layouts.public')

@section('title', 'Search | AJANGO MEDIA')

@section('content')
    <section class="py-4 border-bottom bg-light">
        <div class="container">
            <h1 class="h2 mb-3">Search</h1>
            <form method="GET" action="{{ route('search') }}" role="search" class="d-flex gap-2 search-page-form">
                <label for="search-page-input" class="visually-hidden">Search news</label>
                <input type="search" id="search-page-input" name="q" value="{{ $term }}"
                       class="form-control form-control-lg" placeholder="Search headlines, stories and reports"
                       minlength="2" maxlength="100" required>
                <button type="submit" class="btn btn-primary px-4">Search</button>
            </form>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            @if ($articles === null)
                <p class="text-secondary mb-0">Enter at least two characters to search published stories.</p>
            @elseif ($articles->isEmpty())
                <div class="empty-state text-center py-5 px-3">
                    <h2 class="h4 mb-2">No results for "{{ $term }}"</h2>
                    <p class="text-secondary mb-0">Try different keywords or browse the sections in the menu.</p>
                </div>
            @else
                <p class="text-secondary mb-4">{{ number_format($articles->total()) }} {{ \Illuminate\Support\Str::plural('result', $articles->total()) }} for "{{ $term }}"</p>
                <div class="row g-4">
                    @foreach ($articles as $story)
                        <div class="col-sm-6 col-lg-4">
                            <x-article-card :article="$story" />
                        </div>
                    @endforeach
                </div>
                <div class="mt-5">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
