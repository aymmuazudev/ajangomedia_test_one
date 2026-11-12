@extends('emails.layout')

@section('content')
    <h1>New comment needs your review</h1>
    <p class="lead">A reader has posted a comment on a story you moderate.</p>

    <div class="email-meta">
        <p><strong>Story:</strong> {{ $comment->article->title }}</p>
        <p><strong>From:</strong> {{ $comment->author_name }}</p>
        @if ($comment->author_email)
            <p><strong>Email:</strong> {{ $comment->author_email }}</p>
        @endif
        <p><strong>Received:</strong> {{ $comment->created_at->timezone(config('app.timezone'))->format('j M Y, H:i') }}</p>
    </div>

    <h2>Comment</h2>
    <p style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px;">{{ $comment->body }}</p>

    <a href="{{ $moderationUrl }}" class="email-button">Review in admin panel</a>
@endsection
