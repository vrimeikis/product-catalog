<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetAdminPasswordNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     */
    public function __construct(string $token) {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(CanResetPassword $notifiable) {
        return (new MailMessage)
            ->subject('Reset Admin Password Notification')
            ->line('You are receiving this email because we received a password reset request for your admin account.')
            ->action(
                'Reset password',
                url(route('admin.password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()])))
            ->line('This password reset link will expire in ' .
                config('auth.passwords.admins.expire') .
                    ' minutes'
            )
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable) {
        return [
            //
        ];
    }

}
