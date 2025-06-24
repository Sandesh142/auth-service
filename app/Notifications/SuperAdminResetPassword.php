<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuperAdminResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url("http://localhost:5173/reset-password/{$this->token}?email={$notifiable->email}");

        return (new MailMessage)
            ->subject('Reset Your Super Admin Password')
            ->line('You requested a password reset.')
            ->action('Reset Password', $url)
            ->line('If you didn’t request this, ignore this email.');
    }
}
