<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule the VIP tariffs management command to run hourly
        // This will handle:
        // 1. Deactivating expired VIPs based on exact timestamp
        // 2. Activating the next VIP in the booking queue
        // 3. Sending notifications to users about VIP status changes
        $schedule->command('tariffs:manage-vip')->hourly();
        
        // Schedule the daily tariff charges processing command to run daily at midnight
        // This will handle:
        // 1. Processing daily charges for Basic and Priority tariffs
        // 2. Pausing profiles with insufficient balance
        // 3. Sending notifications about low balance
        $schedule->command('tariffs:process-daily-charges')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}