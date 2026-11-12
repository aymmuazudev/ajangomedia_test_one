<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset your AJANGO MEDIA admin password')
            ->view('emails.reset-password', [
                'url' => $url,
                'name' => $notifiable->name,
                'count' => config('auth.passwords.users.expire', 60),
            ])
            ->text('emails.reset-password-plain', [
                'url' => $url,
                'name' => $notifiable->name,
                'count' => config('auth.passwords.users.expire', 60),
            ]);
    }
}
