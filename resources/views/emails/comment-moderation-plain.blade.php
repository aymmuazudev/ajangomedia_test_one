@extends('emails.layout-plain')

@section('content')
New comment needs your review

A reader has posted a comment on a story you moderate.

Story: {{ $comment->article->title }}
From: {{ $comment->author_name }}
@if ($comment->author_email)
Email: {{ $comment->author_email }}
@endif
Received: {{ $comment->created_at->timezone(config('app.timezone'))->format('j M Y, H:i') }}

Comment:
{{ $comment->body }}

Review in admin panel: {{ $moderationUrl }}
@endsection
