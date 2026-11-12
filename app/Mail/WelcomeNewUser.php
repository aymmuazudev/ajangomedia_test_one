<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeNewUser extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public ?string $resetUrl = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to AJANGO MEDIA — your admin account',
        );
    }

    public function content(): \Illuminate\Mail\Mailables\Content
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.welcome',
            text: 'emails.welcome-plain',
        );
    }
}
