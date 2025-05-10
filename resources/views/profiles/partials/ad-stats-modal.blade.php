<!-- Ad Statistics Modal -->
@if(auth()->check() && $profile->user_id === auth()->id() && $profile->tariffs->count() > 0)
<div id="adStatsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="adStatsModalOverlay"></div>

    <!-- Modal panel -->
    <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
      <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
              Статистика расходов на рекламу
            </h3>
            
            @php
              // Group tariffs by type
              $basicTariffs = $profile->tariffs->filter(function($tariff) {
                return $tariff->adTariff && $tariff->adTariff->slug === 'basic';
              });
              
              $priorityTariffs = $profile->tariffs->filter(function($tariff) {
                return $tariff->adTariff && $tariff->adTariff->slug === 'priority';
              });
              
              $vipTariffs = $profile->tariffs->filter(function($tariff) {
                return $tariff->adTariff && $tariff->adTariff->slug === 'vip';
              });
              
              // Calculate spending by type
              $basicSpent = $basicTariffs->flatMap->charges->sum('amount');
              $prioritySpent = $priorityTariffs->flatMap->charges->sum('amount');
              $vipSpent = $vipTariffs->flatMap->charges->sum('amount');
              $totalSpent = $basicSpent + $prioritySpent + $vipSpent;
            @endphp
            
            <!-- Summary Cards -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
              <div class="bg-white dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                      <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                          Всего потрачено
                        </dt>
                        <dd>
                          <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ number_format($totalSpent, 0, '.', ' ') }} ₽
                          </div>
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="bg-white dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                      <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                      </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                          Базовая реклама
                        </dt>
                        <dd>
                          <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ number_format($basicSpent, 0, '.', ' ') }} ₽
                          </div>
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="bg-white dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                      <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                      </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                          Приоритет
                        </dt>
                        <dd>
                          <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ number_format($prioritySpent, 0, '.', ' ') }} ₽
                          </div>
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="bg-white dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                      <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                      </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                          VIP
                        </dt>
                        <dd>
                          <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ number_format($vipSpent, 0, '.', ' ') }} ₽
                          </div>
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Detailed Tariff History -->
            <div class="mt-6">
              <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">История рекламных тарифов</h4>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-50 dark:bg-gray-600">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Тип</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Дата активации</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Дата окончания</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Длительность</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Статус</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Сумма</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($profile->tariffs->sortByDesc('created_at') as $tariff)
                      @php
                        $tariffCharges = $tariff->charges->sum('amount');
                        $duration = $tariff->expires_at ? $tariff->created_at->diffInDays($tariff->expires_at) : $tariff->created_at->diffInDays(now());
                        if ($tariff->is_active) {
                          $status = 'Активен';
                          $statusClass = 'green';
                        } elseif ($tariff->is_paused) {
                          $status = 'Приостановлен';
                          $statusClass = 'yellow';
                        } else {
                          $status = 'Завершен';
                          $statusClass = 'gray';
                        }
                      @endphp
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                          {{ $tariff->adTariff->name }}
                          @if($tariff->isPriority() && $tariff->priority_level)
                            <div class="text-xs text-gray-500 dark:text-gray-400">Уровень: {{ $tariff->priority_level }}</div>
                          @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $tariff->created_at->format('d.m.Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $tariff->expires_at ? $tariff->expires_at->format('d.m.Y') : 'Бессрочно' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $duration }} дней</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $statusClass }}-100 text-{{ $statusClass }}-800 dark:bg-{{ $statusClass }}-800 dark:text-{{ $statusClass }}-100">
                            {{ $status }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($tariffCharges, 0, '.', ' ') }} ₽</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" id="closeAdStatsModal">
          Закрыть
        </button>
      </div>
    </div>
  </div>
</div>
@endif

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const adStatsModal = document.getElementById('adStatsModal');
    const adStatsButton = document.getElementById('adStatsButton');
    const closeAdStatsModal = document.getElementById('closeAdStatsModal');
    const adStatsModalOverlay = document.getElementById('adStatsModalOverlay');
    
    if (adStatsButton) {
      adStatsButton.addEventListener('click', function() {
        adStatsModal.classList.remove('hidden');
      });
    }
    
    if (closeAdStatsModal) {
      closeAdStatsModal.addEventListener('click', function() {
        adStatsModal.classList.add('hidden');
      });
    }
    
    if (adStatsModalOverlay) {
      adStatsModalOverlay.addEventListener('click', function() {
        adStatsModal.classList.add('hidden');
      });
    }
  });
</script>