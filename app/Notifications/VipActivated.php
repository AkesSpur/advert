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
     * The date when VIP status expires.
     *
     * @var \DateTime
     */
    protected $expiresAt;

    /**
     * Create a new notification instance.
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->expiresAt = now()->addDays(30); // Default 30 days, adjust as needed
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
            ->subject('VIP статус активирован')
            ->view('emails.custom-notification', [
                'title' => 'VIP статус активирован',
                'greeting' => 'Поздравляем!',
                'introLines' => [
                    'VIP статус для вашего профиля "' . $this->profile->name . '" успешно активирован.',
                    'Ваше объявление теперь будет отображаться в топе списка и получит специальную отметку.',
                    'Срок действия VIP статуса: ' . $this->expiresAt->format('d.m.Y')
                ],
                'actionText' => 'Просмотреть профиль',
                'actionUrl' => url('/user/profiles'),
                'outroLines' => [
                    'Спасибо за использование нашего сервиса!'
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
            'profile_id' => $this->profile->id,
            'profile_name' => $this->profile->name,
            'message' => 'Ваш профиль "' . $this->profile->name . '" теперь имеет статус VIP.'
        ];
    }
}