<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Добро пожаловать!')
            ->view('emails.custom-notification', [
                'title' => 'Добро пожаловать!',
                'greeting' => 'Здравствуйте!',
                'introLines' => [
                    'Спасибо за регистрацию в нашем сервисе.',
                    'Мы рады приветствовать вас в нашем сообществе.'
                ],
                'actionText' => 'Перейти в личный кабинет',
                'actionUrl' => url('/user/profiles'),
                'outroLines' => [
                    'Если у вас возникнут вопросы, не стесняйтесь обращаться к нам.'
                ]
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}