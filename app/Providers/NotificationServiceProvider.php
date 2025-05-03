<?php

namespace App\Providers;

use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Override the default ResetPassword notification with our custom one
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new CustomResetPasswordNotification($token))->toMail($notifiable);
        });
    }
}