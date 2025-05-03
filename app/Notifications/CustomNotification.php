<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $greeting;
    protected $introLines;
    protected $actionText;
    protected $actionUrl;
    protected $outroLines;

    /**
     * Create a new notification instance.
     */
    public function __construct($title = null, $greeting = null, array $introLines = [], $actionText = null, $actionUrl = null, array $outroLines = [])
    {
        $this->title = $title;
        $this->greeting = $greeting;
        $this->introLines = $introLines;
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
        $this->outroLines = $outroLines;
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
        $mail = (new MailMessage)
            ->view('emails.custom-notification', [
                'title' => $this->title,
                'greeting' => $this->greeting,
                'introLines' => $this->introLines,
                'actionText' => $this->actionText,
                'actionUrl' => $this->actionUrl,
                'outroLines' => $this->outroLines,
            ]);

        if ($this->actionText && $this->actionUrl) {
            $mail->action($this->actionText, $this->actionUrl);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'greeting' => $this->greeting,
            'intro_lines' => $this->introLines,
            'action_text' => $this->actionText,
            'action_url' => $this->actionUrl,
            'outro_lines' => $this->outroLines,
        ];
    }
}