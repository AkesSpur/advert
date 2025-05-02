<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VipActivated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The profile that was activated as VIP.
     *
     * @var \App\Models\Profile
     */
    protected $profile;

    /**
     * Create a new notification instance.
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ваш профиль активирован как VIP!')
            ->line('Поздравляем! Ваш профиль "' . $this->profile->name . '" теперь имеет статус VIP.')
            ->line('Ваше объявление будет отображаться в приоритетном порядке.')
            ->action('Посмотреть профиль', url('/profiles/' . $this->profile->id))
            ->line('Спасибо за использование нашего сервиса!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'profile_id' => $this->profile->id,
            'profile_name' => $this->profile->name,
            'message' => 'Ваш профиль "' . $this->profile->name . '" теперь имеет статус VIP.'
        ];
    }
}