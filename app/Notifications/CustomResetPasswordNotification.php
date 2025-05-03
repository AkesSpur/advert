<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class CustomResetPasswordNotification extends ResetPassword
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$createUrlCallback) {
            $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        } else {
            $url = url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        }

        return (new MailMessage)
            ->subject('Сброс пароля')
            ->view('emails.custom-notification', [
                'title' => 'Сброс пароля',
                'greeting' => 'Здравствуйте!',
                'introLines' => [
                    'Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.'
                ],
                'actionText' => 'Сбросить пароль',
                'actionUrl' => $url,
                'outroLines' => [
                    'Срок действия ссылки для сброса пароля истекает через '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' минут.',
                    'Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.'
                ]
            ]);
    }
}