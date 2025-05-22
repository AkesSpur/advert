<?php

namespace App\Console\Commands;

use App\Models\AdTariffCharge;
use App\Models\ProfileAdTariff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowBalanceWarning;

class ProcessDailyTariffCharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tariffs:process-daily-charges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily charges for Basic and Priority tariffs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily tariff charges processing...');
        
        // Process in a transaction to ensure data consistency
        DB::transaction(function () {
            $now = Carbon::now();
            
            // Get all active and non-paused Basic and Priority tariffs
            // Exclude tariffs for profiles that are VIP
            $tariffs = ProfileAdTariff::whereHas('adTariff', function ($query) {
                $query->whereIn('slug', ['basic', 'priority']);
            })
            ->where('is_active', true)
            ->where('is_paused', false)
            ->whereHas('profile', function($query) {
                $query->where('is_vip', false);
            })
            ->get();
            
            $this->info("Found {$tariffs->count()} active Basic and Priority tariffs.");
            
            foreach ($tariffs as $tariff) {
                $user = $tariff->profile->user;
                
                // Пропускаем проверку баланса и списание средств для администратора (user_id = 1)
                if ($user->id === 1) {
                    $this->info("Skipping charge for admin user (ID: 1) for profile {$tariff->profile->id}.");
                    continue;
                }
                
                // Check if user has enough balance
                if ($user->balance < $tariff->daily_charge) {
                    $tariff->pause();
                    
                    // Notify user about low balance
                    try {
                        Notification::send($user, new LowBalanceWarning($tariff->profile, $tariff->daily_charge));
                        $this->info("Notification sent to user {$user->id} about low balance and paused profile.");
                    } catch (\Exception $e) {
                        $this->error("Failed to send low balance notification to user {$user->id}: {$e->getMessage()}");
                    }
                    
                    continue;
                }
                
                // Process the charge
                $user->balance -= $tariff->daily_charge;
                $user->save();
                
                // Record the charge
                AdTariffCharge::create([
                    'profile_ad_tariff_id' => $tariff->id,
                    'user_id' => $user->id,
                    'amount' => $tariff->daily_charge,
                    'charged_at' => $now,
                ]);

                if($tariff->daily_charge > 1){
                    $description = "Ежедневная оплата тарифа (уровень {$tariff->priority_level}) для профиля ID№{$tariff->profile_id}";
                }else
                {
                    $description = "Ежедневная оплата основной тарифа для профиля ID№{$tariff->profile_id}";
                }

                // Create a transaction record for the daily charge
                $user->transactions()->create([
                    'amount' => -$tariff->daily_charge, // Negative amount as it's a charge
                    'type' => 'purchase',
                    'status' => 'completed',
                    'reference_id' => 'daily_charge_' . $tariff->id . '_' . $now->format('Y-m-d'),
                    'description' => $description,
                ]);
                
                $this->info("Charged user {$user->id} {$tariff->daily_charge} for {$tariff->adTariff->name} tariff.");
            
            }
        });
        
        $this->info('Daily tariff charges processing completed successfully.');
        
        return 0;
    }
}