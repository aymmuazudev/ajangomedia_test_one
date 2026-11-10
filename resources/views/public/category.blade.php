@extends('layouts.public')

@section('title', $category->name.' | AJANGO MEDIA')

@section('content')
    <section class="py-4 border-bottom bg-light">
        <div class="container">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb small mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0">{{ $category->name }}</h1>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            @if ($articles->isEmpty())
                <div class="empty-state text-center py-5 px-3">
                    <h2 class="h4 mb-2">No stories in {{ $category->name }} yet</h2>
                    <p class="text-secondary mb-0">New reports in this section will appear here once published.</p>
                </div>
            @else
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
