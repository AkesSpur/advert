<?php

namespace App\Providers;

use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        // Override the default ResetPassword notification with our custom one
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new CustomResetPasswordNotification($token))->toMail($notifiable);
        });
    }
}
