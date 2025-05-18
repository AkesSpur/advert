<?php

namespace App\Providers;

use App\Models\EmailSetting;
use App\Models\GeneralSetting;
use App\Models\LogoSetting;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
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

        $generalSetting = GeneralSetting::first();
        $logoSetting = LogoSetting::first();
        $mailSetting = EmailSetting::first();

        /** Set Mail Config */
        Config::set('mail.mailers.smtp.host', $mailSetting->host);
        Config::set('mail.mailers.smtp.port', $mailSetting->port);
        Config::set('mail.mailers.smtp.encryption', $mailSetting->encryption);
        Config::set('mail.mailers.smtp.username', $mailSetting->username);
        Config::set('mail.mailers.smtp.password', $mailSetting->password);

        // Override the default ResetPassword notification with our custom one
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new CustomResetPasswordNotification($token))->toMail($notifiable);
        });

        /** Share variable at all view */
        View::composer('*', function ($view) use ($generalSetting, $logoSetting) {
            $yandexApiKey = $generalSetting->yandex_api_key ?? null;
            $view->with('yandexApiKey', $yandexApiKey);
            $view->with([
                'settings' => $generalSetting,
                'logoSetting' => $logoSetting,
            ]);
        });
    }
}
