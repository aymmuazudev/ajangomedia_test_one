@extends('emails.layout-plain')

@section('content')
Great news — your story is live!

Your story has been published on AJANGO MEDIA and is now visible to readers.

Title: {{ $article->title }}
@if ($article->category)
Section: {{ $article->category->name }}
@endif
Published: {{ $article->published_at->timezone(config('app.timezone'))->format('j M Y, H:i') }}

@if ($article->summary)
Summary:
{{ $article->summary }}
@endif

Read your published story: {{ url(route('articles.show', $article, false)) }}
@endsection
