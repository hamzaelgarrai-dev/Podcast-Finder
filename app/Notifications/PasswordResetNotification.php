<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe')
            ->greeting('Bonjour ' . $this->user->name )
            ->line('Voici votre code de réinitialisation :')
            ->line('**' . $this->token . '**')
            ->line('Ce code est valable pour une durée limitée.')
            ->line('Si vous n\'avez pas demandé cette action, ignorez simplement ce message.');
    }
}
