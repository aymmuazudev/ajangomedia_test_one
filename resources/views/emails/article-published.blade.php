@extends('emails.layout')

@section('content')
    <h1>Great news — your story is live!</h1>
    <p>Your story has been published on AJANGO MEDIA and is now visible to readers.</p>

    <div class="email-meta">
        <p><strong>Title:</strong> {{ $article->title }}</p>
        @if ($article->category)
            <p><strong>Section:</strong> {{ $article->category->name }}</p>
        @endif
        <p><strong>Published:</strong> {{ $article->published_at->timezone(config('app.timezone'))->format('j M Y, H:i') }}</p>
    </div>

    @if ($article->summary)
        <h2>Summary</h2>
        <p>{{ $article->summary }}</p>
    @endif

    <a href="{{ url(route('articles.show', $article, false)) }}" class="email-button">Read your published story</a>
@endsection
