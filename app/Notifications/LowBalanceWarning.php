<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowBalanceWarning extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The profile that was paused due to low balance.
     *
     * @var \App\Models\Profile
     */
    protected $profile;

    /**
     * The required daily charge amount.
     *
     * @var float
     */
    protected $requiredAmount;

    /**
     * Create a new notification instance.
     */
    public function __construct(Profile $profile, float $requiredAmount)
    {
        $this->profile = $profile;
        $this->requiredAmount = $requiredAmount;
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
            ->subject('Предупреждение о низком балансе')
            ->view('emails.custom-notification', [
                'title' => 'Низкий баланс',
                'greeting' => 'Здравствуйте!',
                'introLines' => [
                    'Уведомляем вас, что на вашем балансе недостаточно средств для списания ежедневной платы.',
                    'Ваш профиль "' . $this->profile->name . '" был приостановлен.',
                    'Требуемая сумма для возобновления: ' . $this->requiredAmount . ' руб.'
                ],
                'actionText' => 'Пополнить баланс',
                'actionUrl' => url('/user/balance'),
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
            'required_amount' => $this->requiredAmount,
            'message' => 'Ваш профиль "' . $this->profile->name . '" был приостановлен из-за недостатка средств на балансе.'
        ];
    }
}