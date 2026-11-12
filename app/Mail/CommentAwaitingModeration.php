<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentAwaitingModeration extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Comment $comment,
        public string $moderationUrl,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New comment awaiting moderation on "' . $this->comment->article->title . '"',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.comment-moderation',
            text: 'emails.comment-moderation-plain',
        );
    }
}
