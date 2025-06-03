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
                ->where(function ($query) {
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

        $user = Auth::user();
        // Проверяем, что профиль принадлежит текущему пользователю
        $profile = Profile::findOrFail($validated['profile_id']);
        if ($profile->user_id != $user->id) {
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
            $isAdmin = ($user->id == 1); // Проверяем, является ли пользователь администратором
            $totalCost = 0; // Инициализируем переменную для общей стоимости

            // Рассчитываем стоимость и дату окончания в зависимости от типа тарифа
            switch ($tariff->slug) {
                case 'basic':
                    $dailyCharge = $tariff->base_price; // 1 рубль в день
                    // Базовый тариф не имеет даты окончания, списывается ежедневно
                    break;

                case 'priority':
                    $priorityLevel = $validated['priority_level'] ?? 1;
                    $dailyCharge = $tariff->base_price * $priorityLevel; // Базовая цена + уровень приоритета

                    // Проверяем, есть ли уже активный приоритетный тариф для этого профиля
                    $existingPriorityTariff = ProfileAdTariff::where('profile_id', $profile->id)
                        ->whereHas('adTariff', function ($query) {
                            $query->where('slug', 'priority');
                        })
                        ->where('is_active', true)
                        ->where('is_paused', false)
                        ->first();

                    // Если уже есть активный приоритетный тариф, обновляем его вместо создания нового
                    if ($existingPriorityTariff) {
                        // Обновляем уровень приоритета и дневную плату
                        $oldPriorityLevel = $existingPriorityTariff->priority_level;

                        // If the new priority level is the same as the old one, return an error
                        if ($oldPriorityLevel == $priorityLevel) {
                            return redirect()->back()->with('error', 'Выбранный уровень приоритета уже активен.');
                        }

                        $existingPriorityTariff->priority_level = $priorityLevel;
                        $existingPriorityTariff->daily_charge = $dailyCharge;
                        $existingPriorityTariff->save();

                        // Создаем запись о списании (пропускаем для администратора)
                        if (!$isAdmin) {

                            if($oldPriorityLevel < $priorityLevel)
                            {
                                $priorityUser = User::find($user->id);

                                $charge = $tariff->base_price * ($priorityLevel - $oldPriorityLevel);
                                $priorityUser->balance -= $charge;
                                $priorityUser->save();
                
                                AdTariffCharge::create([
                                    'profile_ad_tariff_id' => $existingPriorityTariff->id,
                                    'user_id' => $priorityUser->id,
                                    'amount' => $charge,
                                    'charged_at' => $now,
                                ]);
    
                                // Создаем транзакцию с подробным описанием
                                $description = "Изменение уровня приоритета с {$oldPriorityLevel} на {$existingPriorityTariff->priority_level} для профиля ID№{$profile->id}";
    
                                $priorityUser->transactions()->create([
                                    'amount' => -$charge,
                                    'type' => 'purchase',
                                    'status' => 'completed',
                                    'reference_id' => $tariff->slug . '_tariff_' . $existingPriorityTariff->id,
                                    'description' => $description,
                                ]);
    
                            }
                        } else {
                            // Для администратора просто логируем информацию без финансовых операций
                            info("Admin (ID: 1) activated {$tariff->slug} tariff for profile ID№{$profile->id} without charge.");
                        }
                        // Возвращаем успешный результат
                        return redirect()->route('user.advert.index')
                            ->with('success', 'Уровень приоритета успешно обновлен для вашего профиля.');
                    }
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

                    // Для администратора устанавливаем срок действия VIP на 1 год
                    // if ($isAdmin) {
                    //     $expiresAt = $now->copy()->addYear();
                    // }

                    // Проверяем, есть ли у данного профиля уже активный VIP-слот
                    $profileHasActiveVip = ProfileAdTariff::where('profile_id', $profile->id)
                        ->whereHas('adTariff', function ($query) {
                            $query->where('slug', 'vip');
                        })
                        ->where('is_active', true)
                        ->where('is_paused', false)
                        ->where('queue_position', 0)
                        ->where('expires_at', '>', $now)
                        ->exists();

                    // Проверяем количество УНИКАЛЬНЫХ профилей с активными VIP тарифами (не в очереди)
                    $activeVipProfilesCount = ProfileAdTariff::whereHas('adTariff', function ($query) {
                        $query->where('slug', 'vip');
                    })
                        ->where('is_active', true)
                        ->where('is_paused', false)
                        ->where('expires_at', '>', $now)
                        ->where('queue_position', 0)
                        ->distinct('profile_id')
                        ->count('profile_id');

                    // Определяем позицию в очереди
                    if ($profileHasActiveVip || $activeVipProfilesCount >= 3) {
                        // Если у профиля уже есть активный VIP или все 3 слота заняты другими профилями, ставим в очередь
                        $maxQueuePosition = ProfileAdTariff::whereHas('adTariff', function ($query) {
                            $query->where('slug', 'vip');
                        })
                            ->where('is_active', true) // Учитываем и те, что в очереди, но активны
                            // ->where('is_paused', false) // Пауза не должна влиять на позицию в очереди
                            ->where('queue_position', '>', 0)
                            ->max('queue_position') ?? 0;

                        $queuePosition = $maxQueuePosition + 1;
                    } else {
                        // Если у профиля нет активного VIP и есть свободные слоты, позиция в очереди = 0 (активный VIP)
                        $queuePosition = 0;
                    }
                    break;
            }

            // Проверяем баланс пользователя (пропускаем для администратора)
            if ($tariff->slug == 'vip') {
                // Для VIP тарифа уже рассчитана полная стоимость выше
                // totalCost уже установлен в соответствующем блоке switch
            } else {
                // Для basic и priority тарифов проверяем только дневную плату
                $totalCost = $dailyCharge;
            }

            // Пропускаем проверку баланса для администратора
            if (!$isAdmin && $user->balance < $totalCost) {
                return back()->with('error', 'Недостаточно средств на балансе. Пополните баланс.');
            }

            // If creating a priority ad, deactivate any existing basic ads
            if ($tariff->slug == 'priority') {
                // Find and deactivate any active basic ads for this profile
                $basicAds = ProfileAdTariff::whereHas('adTariff', function ($query) {
                    $query->where('slug', 'basic');
                })
                    ->where('profile_id', $profile->id)
                    ->where('is_active', true)
                    ->get();

                foreach ($basicAds as $basicAd) {
                    $basicAd->deactivateBasic();
                }
            }

            // Создаем запись о тарифе для профиля
            $startsAt = $now; // По умолчанию начинается сейчас

            // Если это VIP тариф и он в очереди, устанавливаем starts_at на время истечения соответствующего активного VIP
            if ($tariff->slug == 'vip' && isset($queuePosition) && $queuePosition > 0) {
                // Находим все активные VIP тарифы, отсортированные по дате истечения
                $activeVips = ProfileAdTariff::whereHas('adTariff', function ($query) {
                    $query->where('slug', 'vip');
                })
                    ->where('is_active', true)
                    ->where('is_paused', false)
                    ->where('queue_position', 0)
                    ->where('expires_at', '>', $now)
                    ->orderBy('expires_at', 'asc')
                    ->get();

                // Определяем, какой VIP будет заменен этим (на основе позиции в очереди)
                $vipIndex = min($queuePosition - 1, $activeVips->count() - 1);
                if (isset($activeVips[$vipIndex])) {
                    $startsAt = $activeVips[$vipIndex]->expires_at;


                    switch ($duration) {
                        case '1_day':
                            $expiresAt = $startsAt->copy()->addDay();
                            break;
                        case '1_week':
                            $expiresAt = $startsAt->copy()->addWeek();
                            break;
                        case '1_month':
                            $expiresAt = $startsAt->copy()->addMonth();
                            break;
                    }
                }
            }

            $profileTariff = ProfileAdTariff::create([
                'profile_id' => $profile->id,
                'ad_tariff_id' => $tariff->id,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'is_active' => true,
                'is_paused' => false,
                'priority_level' => $validated['priority_level'] ?? null,
                'queue_position' => $queuePosition ?? null,
                'daily_charge' => $dailyCharge,
            ]);


            // Получаем свежий экземпляр модели User для списания средств с баланса
            $freshUser = User::find($user->id);

            // Пропускаем списание средств для администратора
            if (!$isAdmin) {
                $freshUser->balance -= $totalCost;
                $freshUser->save();
            }

            // Для VIP тарифа устанавливаем статус VIP
            if ($tariff->slug == 'vip' && isset($queuePosition) && $queuePosition == 0) {
                $profile->is_vip = true;
                $profile->is_active = true;
                $profile->save();
            }

            if ($tariff->slug == 'basic' || $tariff->slug == 'priority') {
                $profile->is_active = true;
                $profile->save();
            }

            // Создаем запись о списании (пропускаем для администратора)
            if (!$isAdmin) {
                AdTariffCharge::create([
                    'profile_ad_tariff_id' => $profileTariff->id,
                    'user_id' => $freshUser->id,
                    'amount' => $totalCost,
                    'charged_at' => $now,
                ]);

                // Создаем транзакцию с подробным описанием
                $description = '';
                switch ($tariff->slug) {
                    case 'basic':
                        $description = "Приобретение базового тарифа для профиля ID№{$profile->id}";
                        break;
                    case 'priority':
                        $description = "Приоритетное приобретение тарифов (уровень {$profileTariff->priority_level}) для профиля ID№{$profile->id}";
                        break;
                    case 'vip':
                        $description = "VIP-покупка для профиля ID№{$profile->id}";
                        break;
                }

                $freshUser->transactions()->create([
                    'amount' => -$totalCost,
                    'type' => 'purchase',
                    'status' => 'completed',
                    'reference_id' => $tariff->slug . '_tariff_' . $profileTariff->id,
                    'description' => $description,
                ]);
            } else {
                // Для администратора просто логируем информацию без финансовых операций
                info("Admin (ID: 1) activated {$tariff->slug} tariff for profile ID№{$profile->id} without charge.");
            }


            return redirect()->route('user.advert.index')->with('success', 'Тариф успешно активирован для вашего профиля.');
        });
    }

    /**
     * Pause a tariff.
     */
    public function pause(Request $request, $id)
    {
        $profileTariff = ProfileAdTariff::findOrFail($id);

        // Проверяем, что тариф принадлежит текущему пользователю
        if ($profileTariff->profile->user_id != Auth::id()) {
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
        if ($profileTariff->profile->user_id != Auth::id()) {
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
        if ($profileTariff->profile->user_id != Auth::id()) {
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
