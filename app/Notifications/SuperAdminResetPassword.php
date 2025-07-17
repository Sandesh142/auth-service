<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

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
        $url = route('superadmin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
                    ->subject(Lang::get('SuperAdmin Password Reset Notification'))
                    ->line(Lang::get('You are receiving this email because we received a password reset request for your SuperAdmin account.'))
                    ->action(Lang::get('Reset Password'), $url)
                    ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.superadmins.expire')]))
                    ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }
}
