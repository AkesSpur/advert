<?php

namespace App\Console\Commands;

use App\Models\AdTariff;
use App\Models\ProfileAdTariff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VipActivated;
use App\Notifications\VipExpired;
use App\Notifications\LowBalanceWarning;

class ManageVipTariffs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tariffs:manage-vip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage VIP tariffs: deactivate expired, activate next in queue, and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting VIP tariffs management...');
        
        // Process in a transaction to ensure data consistency
        DB::transaction(function () {
            $now = Carbon::now();
            
            // 1. Deactivate expired VIPs
            $expiredVips = ProfileAdTariff::whereHas('adTariff', function ($query) {
                $query->where('slug', 'vip');
            })
            ->where('is_active', true)
            ->where('expires_at', '<=', $now)
            ->get();
            
            foreach ($expiredVips as $expiredVip) {
                // Получаем пользователя профиля
                $user = $expiredVip->profile->user;
                
                // // Пропускаем деактивацию VIP для профилей администратора (user_id = 1)
                // if ($user->id == 1) {
                //     // Продлеваем срок действия VIP на месяц для профилей администратора
                //     $expiredVip->expires_at = Carbon::now()->addMonth();
                //     $expiredVip->save();
                //     $this->info("Extended VIP status for admin profile {$expiredVip->profile->id} for another month.");
                //     continue;
                // }
                
                // Use the deactivate method to properly update both the tariff and profile status
                $expiredVip->deactivate();
                
                // Update the profile's is_vip status to false
                $expiredVip->profile->is_vip = false;
                $expiredVip->profile->save();
                $this->info("Updated is_vip status to false for profile {$expiredVip->profile->id}.");
                
                // Notify user about VIP expiration
                try {
                    Notification::send($user, new VipExpired($expiredVip->profile));
                    $this->info("Notification sent to user {$user->id} about VIP expiration.");
                } catch (\Exception $e) {
                    $this->error("Failed to send VIP expiration notification to user {$user->id}: {$e->getMessage()}");
                }
            }
            
            $this->info("Deactivated {$expiredVips->count()} expired VIP tariffs.");
            
            // 2. Activate next VIPs in queue
            $activeVipCount = ProfileAdTariff::whereHas('adTariff', function ($query) {
                $query->where('slug', 'vip');
            })
            ->where('is_active', true)
            ->where('is_paused', false)
            ->where('queue_position', '<=', 0)
            ->where('expires_at', '>', $now)
            ->count();
            $this->info("{$activeVipCount} VIP is active.");
            
            $slotsAvailable = 3 - $activeVipCount;

            if ($slotsAvailable > 0) {
                $this->info("{$slotsAvailable} VIP slots available for activation.");
                
                // Get next VIPs in queue
                $nextVips = ProfileAdTariff::whereHas('adTariff', function ($query) {
                    $query->where('slug', 'vip');
                })
                ->where('is_active', true)
                ->where('queue_position', '>', 0)
                ->orderBy('queue_position', 'asc')
                ->limit($slotsAvailable)
                ->get();
                
                foreach ($nextVips as $nextVip) {
                    $nextVip->queue_position = 0;
                    $nextVip->save();
                    
                    // Update the profile's is_vip status to true
                    $nextVip->profile->is_vip = true;
                    $nextVip->profile->save();
                    $this->info("Updated is_vip status to true for profile {$nextVip->profile->id}.");
                    
                    // Notify user about VIP activation
                    $user = $nextVip->profile->user;
                    try {
                        Notification::send($user, new VipActivated($nextVip->profile));
                        $this->info("Notification sent to user {$user->id} about VIP activation.");
                    } catch (\Exception $e) {
                        $this->error("Failed to send VIP activation notification to user {$user->id}: {$e->getMessage()}");
                    }
                }
                
                $this->info("Activated {$nextVips->count()} VIPs from queue.");
                
                // Update remaining queue positions to ensure proper FIFO queue system
                $queuedVips = ProfileAdTariff::whereHas('adTariff', function ($query) {
                    $query->where('slug', 'vip');
                })
                ->where('is_active', true)
                ->where('queue_position', '>', 0)
                ->orderBy('queue_position', 'asc')
                ->get();
                
                // Reindex queue positions to maintain FIFO order
                $position = 1;
                foreach ($queuedVips as $queuedVip) {
                    // Only update if the position has changed
                    if ($queuedVip->queue_position !== $position) {
                        $queuedVip->queue_position = $position;
                        $queuedVip->save();
                    }
                    $position++;
                }
                
                $this->info("Updated queue positions for {$queuedVips->count()} VIPs in FIFO order.");
            }
            
            // Note: Low balance checks and daily charges for Basic and Priority tariffs
            // are now handled by the ProcessDailyTariffCharges command
        });
        
        $this->info('VIP tariffs management completed successfully.');
        
        return 0;
    }
}