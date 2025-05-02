<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VipExpired extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The profile that was expired.
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
            ->subject('Срок действия VIP статуса истек')
            ->line('Уведомляем вас, что срок действия VIP статуса для вашего профиля "' . $this->profile->name . '" истек.')
            ->line('Ваше объявление теперь отображается в обычном порядке.')
            ->action('Продлить VIP статус', url('/tariffs'))
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
            'message' => 'Срок действия VIP статуса для вашего профиля "' . $this->profile->name . '" истек.'
        ];
    }
}