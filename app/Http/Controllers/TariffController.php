<?php

namespace App\Http\Controllers;

use App\Models\AdTariff;
use App\Models\AdTariffCharge;
use App\Models\Profile;
use App\Models\ProfileAdTariff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TariffController extends Controller
{
    /**
     * Display a listing of the tariffs.
     */
    public function index()
    {
        // Получаем все активные тарифы
        $tariffs = AdTariff::where('is_active', true)->get();
        
        // Получаем активные тарифы текущего пользователя
        $userActiveTariffs = [];
        $nextAvailableVipDate = null;
        
        if (Auth::check()) {
            $userProfiles = Auth::user()->profiles;
            $profileIds = $userProfiles->pluck('id')->toArray();
            
            $userActiveTariffs = ProfileAdTariff::whereIn('profile_id', $profileIds)
                ->where('is_active', true)
                ->where(function($query) {
                    $query->where('expires_at', '>', now())
                          ->orWhereNull('expires_at');
                })
                ->with(['adTariff', 'profile'])
                ->get();
        }
        
        // Проверяем количество активных VIP тарифов
        $activeVipCount = ProfileAdTariff::whereHas('adTariff', function ($query) {
            $query->where('slug', 'vip');
        })
        ->where('is_active', true)
        ->where('is_paused', false)
        ->where('expires_at', '>', now())
        ->where('queue_position', 0) // Только активные VIP, не в очереди
        ->count();
        
        // Если все 3 VIP слота заняты, определяем ближайшую доступную дату
        if ($activeVipCount >= 3) {
            // Находим VIP с самой ранней датой истечения
            $earliestExpiringVip = ProfileAdTariff::whereHas('adTariff', function ($query) {
                $query->where('slug', 'vip');
            })
            ->where('is_active', true)
            ->where('is_paused', false)
            ->where('queue_position', 0)
            ->orderBy('expires_at', 'asc')
            ->first();
            
            if ($earliestExpiringVip) {
                // Проверяем, есть ли профили в очереди ожидания VIP
                $queuedVipsCount = ProfileAdTariff::whereHas('adTariff', function ($query) {
                    $query->where('slug', 'vip');
                })
                ->where('is_active', true)
                ->where('queue_position', '>', 0)
                ->count();
                
                // Если есть профили в очереди, нужно учесть их при расчете даты
                if ($queuedVipsCount > 0) {
                    // Получаем максимальную позицию в очереди
                    $maxQueuePosition = ProfileAdTariff::whereHas('adTariff', function ($query) {
                        $query->where('slug', 'vip');
                    })
                    ->where('is_active', true)
                    ->max('queue_position');
                    
                    // Находим все активные VIP тарифы, отсортированные по дате истечения
                    $activeVips = ProfileAdTariff::whereHas('adTariff', function ($query) {
                        $query->where('slug', 'vip');
                    })
                    ->where('is_active', true)
                    ->where('is_paused', false)
                    ->where('queue_position', 0)
                    ->orderBy('expires_at', 'asc')
                    ->get();
                    
                    // Если пользователь будет последним в очереди, ему нужно ждать освобождения
                    // нескольких слотов, поэтому берем соответствующий VIP по порядку
                    $vipIndex = min($maxQueuePosition, $activeVips->count() - 1);
                    if (isset($activeVips[$vipIndex])) {
                        $nextAvailableVipDate = $activeVips[$vipIndex]->expires_at;
                    } else {
                        $nextAvailableVipDate = $earliestExpiringVip->expires_at;
                    }
                } else {
                    $nextAvailableVipDate = $earliestExpiringVip->expires_at;
                }
            }
        }
        
        return view('tariffs.index', compact(
            'tariffs',
            'userActiveTariffs',
            'activeVipCount',
            'nextAvailableVipDate'
        ));
    }
    

    /**
     * Activate a tariff for a profile.
     */
    public function activate(Request $request)
    {
        $validated = $request->validate([
            'tariff_type' => 'required|string|in:basic,priority,vip',
            'profile_id' => 'required|exists:profiles,id',
            'priority_level' => 'nullable|integer|min:1|max:20',
            'vip_duration' => 'nullable|string|in:1_day,1_week,1_month',
        ]);

        // Проверяем, что профиль принадлежит текущему пользователю
        $profile = Profile::findOrFail($validated['profile_id']);
        if ($profile->user_id !== Auth::id()) {
            return back()->with('error', 'Профиль не принадлежит вам.');
        }

        // Получаем тариф по типу
        $tariff = AdTariff::where('slug', $validated['tariff_type'])->firstOrFail();
        
        // Начинаем транзакцию
        return DB::transaction(function () use ($validated, $profile, $tariff) {
            $user = Auth::user();
            $now = Carbon::now();
            $dailyCharge = 0;
            $expiresAt = null;
            
            // Рассчитываем стоимость и дату окончания в зависимости от типа тарифа
            switch ($tariff->slug) {
                case 'basic':
                    $dailyCharge = 1; // 1 рубль в день
                    // Базовый тариф не имеет даты окончания, списывается ежедневно
                    break;
                    
                case 'priority':
                    $priorityLevel = $validated['priority_level'] ?? 1;
                    $dailyCharge = 1 + $priorityLevel; // Базовая цена + уровень приоритета
                    // Приоритетный тариф не имеет даты окончания, списывается ежедневно
                    break;
                    
                case 'vip':
                    $duration = $validated['vip_duration'] ?? '1_day';
                    
                    // Рассчитываем стоимость и дату окончания для VIP
                    // VIP тарифы имеют фиксированную стоимость, а не ежедневную плату
                    $dailyCharge = 0; // VIP не списывается ежедневно
                    
                    switch ($duration) {
                        case '1_day':
                            $totalCost = $tariff->fixed_price; // *** рублей за день
                            $expiresAt = $now->copy()->addDay();
                            break;
                        case '1_week':
                            $totalCost = $tariff->weekly_price; // *** рублей за неделю
                            $expiresAt = $now->copy()->addWeek();
                            break;
                        case '1_month':
                            $totalCost = $tariff->monthly_price; // **** рублей за месяц
                            $expiresAt = $now->copy()->addMonth();
                            break;
                    }
                    
                    // Проверяем количество активных VIP тарифов (не в очереди)
                    $activeVipCount = ProfileAdTariff::whereHas('adTariff', function ($query) {
                        $query->where('slug', 'vip');
                    })->where('is_active', true)
                      ->where('is_paused', false)
                      ->where('expires_at', '>', $now)
                      ->where('queue_position', 0)
                      ->count();
                    
                    // Определяем позицию в очереди
                    if ($activeVipCount >= 3) {
                        // Если все слоты заняты, находим максимальную позицию в очереди
                        $maxQueuePosition = ProfileAdTariff::whereHas('adTariff', function ($query) {
                            $query->where('slug', 'vip');
                        })
                        ->where('is_active', true)
                        ->where('queue_position', '>', 0)
                        ->max('queue_position') ?? 0;
                        
                        // Назначаем следующую позицию в очереди
                        $queuePosition = $maxQueuePosition + 1;
                    } else {
                        // Если есть свободные слоты, позиция в очереди = 0 (активный VIP)
                        $queuePosition = 0;
                    }
                    break;
            }
            
            // Проверяем баланс пользователя
            if ($tariff->slug === 'vip') {
                // Для VIP тарифа уже рассчитана полная стоимость выше
                // totalCost уже установлен в соответствующем блоке switch
            } else {
                // Для basic и priority тарифов проверяем только дневную плату
                $totalCost = $dailyCharge;
            }
            
            if ($user->balance < $totalCost) {
                return back()->with('error', 'Недостаточно средств на балансе. Пополните баланс.');
            }
            
            // Создаем запись о тарифе для профиля
            $profileTariff = ProfileAdTariff::create([
                'profile_id' => $profile->id,
                'ad_tariff_id' => $tariff->id,
                'starts_at' => $now,
                'expires_at' => $expiresAt,
                'is_active' => true,
                'is_paused' => false,
                'priority_level' => $validated['priority_level'] ?? null,
                'queue_position' => $queuePosition ?? null,
                'daily_charge' => $dailyCharge,
            ]);

            //set profile active
            $profile->update(['is_active' => true]);
            
            // Для VIP тарифа списываем всю сумму сразу
            if ($tariff->slug === 'vip') {
                // Получаем свежий экземпляр модели User и списываем средства с баланса
                $freshUser = User::find($user->id);
                $freshUser->balance -= $totalCost;
                $freshUser->save();
                
            //set profile active
            $profile->update(['is_vip' => true]);

                // Создаем запись о списании
                AdTariffCharge::create([
                    'profile_ad_tariff_id' => $profileTariff->id,
                    'user_id' => $freshUser->id,
                    'amount' => $totalCost,
                    'charged_at' => $now,
                ]);
                
                // Создаем транзакцию
                $freshUser->transactions()->create([
                    'amount' => -$totalCost,
                    'type' => 'purchase',
                    'status' => 'completed',
                    'reference_id' => 'vip_tariff_' . $profileTariff->id,
                ]);
            }
            
            return redirect()->route('user.advert.index')
                ->with('success', 'Тариф успешно активирован для вашего профиля.');
        });
    }

    /**
     * Pause a tariff.
     */
    public function pause(Request $request, $id)
    {
        $profileTariff = ProfileAdTariff::findOrFail($id);
        
        // Проверяем, что тариф принадлежит текущему пользователю
        if ($profileTariff->profile->user_id !== Auth::id()) {
            return back()->with('error', 'Тариф не принадлежит вам.');
        }
        
        // VIP тарифы нельзя приостанавливать
        if ($profileTariff->isVip()) {
            return back()->with('error', 'VIP тарифы нельзя приостанавливать.');
        }
        
        $profileTariff->pause();
        
        return back()->with('success', 'Тариф успешно приостановлен.');
    }

    /**
     * Resume a tariff.
     */
    public function resume(Request $request, $id)
    {
        $profileTariff = ProfileAdTariff::findOrFail($id);
        
        // Проверяем, что тариф принадлежит текущему пользователю
        if ($profileTariff->profile->user_id !== Auth::id()) {
            return back()->with('error', 'Тариф не принадлежит вам.');
        }
        
        // Проверяем баланс пользователя
        $user = Auth::user();
        if ($user->balance < $profileTariff->daily_charge) {
            return back()->with('error', 'Недостаточно средств на балансе для возобновления тарифа.');
        }
        
        $profileTariff->resume();
        
        return back()->with('success', 'Тариф успешно возобновлен.');
    }

    /**
     * Cancel a tariff.
     */
    public function cancel(Request $request, $id)
    {
        $profileTariff = ProfileAdTariff::findOrFail($id);
        
        // Проверяем, что тариф принадлежит текущему пользователю
        if ($profileTariff->profile->user_id !== Auth::id()) {
            return back()->with('error', 'Тариф не принадлежит вам.');
        }
        
        // VIP тарифы нельзя отменять
        if ($profileTariff->isVip()) {
            return back()->with('error', 'VIP тарифы нельзя отменять.');
        }
        
        $profileTariff->deactivate();
        
        return back()->with('success', 'Тариф успешно отменен.');
    }
}