<x-app-layout>
  <div class="flex pr-3 pl-3 items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Анкеты</h1>
    <a href="{{route('user.form.index')}}"
      class="px-6 py-2 border border-white-700 rounded-md text-white text-sm transition">
      Добавить анкету
    </a>
  </div>

  @if(count($profiles) == 0)
    <!-- Empty state message -->
    <div class="bg-[#191919] rounded-2xl p-8 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24"
      stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    <h2 class="text-xl font-semibold mb-2">У вас пока нет анкет</h2>
    <p class="text-gray-400 mb-6">Создайте свою первую анкету, чтобы начать получать заказы</p>
    <a href="{{route('user.form.index')}}"
      class="px-6 py-3 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-xl text-sm transition inline-block">
      Создать анкету
    </a>
    </div>
  @else

    <!-- Tabs for desktop - now with pill-like edges -->
    {{-- <div class="bg-[#191919] rounded-2xl mb-6 hidden sm:flex px-2 py-1">
    <a href="#" class="px-8 py-3 text-white bg-[#6340FF] rounded-full mx-1">Основные данные</a>
    <a href="#" class="px-8 py-3 text-[#C2C2C2] hover:bg-gray-800 rounded-full mx-1 transition">Локация</a>
    <a href="#" class="px-8 py-3 text-[#C2C2C2] hover:bg-gray-800 rounded-full mx-1 transition">Просмотры</a>
    <a href="#" class="px-8 py-3 text-[#C2C2C2] hover:bg-gray-800 rounded-full mx-1 transition">Клики</a>
    <a href="#" class="px-8 py-3 text-[#C2C2C2] hover:bg-gray-800 rounded-full mx-1 transition">Реклама</a>
    </div> --}}


    <!-- Profiles list - Desktop -->
    <div class="p-3 hidden lg:block">

    <div class=" w-full">
      <table class="min-w-full table-auto">
      <thead>
        <tr class="bg-[#191919]">
        <th class="w-1/3 p-4 text-sm text-white text-left">Основные данные</th>
        <th class="w-1/3 p-4 text-sm text-white text-left">Локация</th>
        <th class="w-1/6 p-4 text-sm text-white text-center">Просмотры</th>
        <th class="w-1/6 p-4 text-sm text-white text-center">Клики</th>
        <th class="w-1/6 p-4 text-sm text-white text-left">Реклама</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($profiles as $profile)

      <tr class="border-b border-gray-800">
      <!-- Profile cell -->
      <td class="p-4 align-top">
        <div class="flex items-center">
        <div class="w-12 h-12 rounded-full overflow-hidden mr-3 bg-gray-800">
        <img src="{{ asset('storage/' . $profile->primaryImage->path) }}" alt="Profile"
        class="w-full h-full object-cover">
        </div>
        <div>
        <div class="flex items-center gap-2">
        <span class="font-medium text-white">{{$profile->name}}, {{$profile->age}}</span>
        @if ($profile->is_active)
      <span class="text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
    @else
    <span class="text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
  @endif
        </div>
        <div class="text-xs text-gray-400">Анкета добавлена {{$profile->created_at->format('d.m.Y')}}</div>
        </div>
        </div>
      </td>

      <!-- Location cell -->
      <td class="p-4 text-sm text-[#C2C2C2] align-top">
        <div class="flex items-center mb-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
        font-family="Arial, sans-serif">M</text>
        </svg>
        @foreach ($profile->neighborhoods as $key => $neighborhood)
      {{ $neighborhood->name}}
      @if ($key < count($profile->neighborhoods) - 1)
      ,
    @endif
    @endforeach
        </div>
        <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
        font-family="Arial, sans-serif">M</text>
        </svg>
        @foreach ($profile->metroStations as $key => $metroStation)
      м. {{ $metroStation->name }}
      @if ($key < count($profile->metroStations) - 1)
      ,
    @endif
    @endforeach
        </div>
      </td>

      <!-- Views cell -->
      <td class="p-4 text-sm text-[#C2C2C2] text-center align-top">
        {{$profile->views_count}}
      </td>

      <!-- Clicks cell -->
      <td class="p-4 text-sm text-[#C2C2C2] text-center align-top">
        {{$profile->clicks_count}}
        
        <!-- Ad Statistics Button -->
        <button type="button" class="text-xs text-white bg-[#121212] p-2 rounded hover:bg-gray-800" onclick="document.getElementById('adStatsModal-{{ $profile->id }}').classList.remove('hidden')">
          <div class="font-medium text-white">Расходы на рекламу</div>
          @php
            $activationTotal = $profile->is_active ? ($profile->active_days ?? 0) : 0;
            $priorityTotal = ($profile->priority_level ?? 0) > 0 ? (($profile->priority_level ?? 0) * ($profile->priority_days ?? 0)) : 0;
            $vipTotal = $profile->is_vip ? (500 * ($profile->vip_days ?? 0)) : 0;
            $totalSpent = $activationTotal + $priorityTotal + $vipTotal;
          @endphp
          <div class="mt-1 pt-1 border-t border-gray-700 flex justify-between font-medium">
            <span>Всего:</span> <span>{{$totalSpent}} руб.</span>
          </div>
        </button>
        
        <!-- Ad Stats Modal for this profile -->
        <div class="modal fade" id="adStatsModal-{{ $profile->id }}" tabindex="-1" role="dialog" aria-labelledby="adStatsModalLabel-{{ $profile->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bg-[#191919] text-white">
              <div class="modal-header border-gray-700">
                <h5 class="modal-title" id="adStatsModalLabel-{{ $profile->id }}">Статистика расходов на рекламу - {{ $profile->name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                @php
                  $activationCost = $profile->is_active ? '1 руб./день' : '0 руб.';
                  $activationDays = $profile->is_active ? $profile->active_days ?? 0 : 0;
                  $activationTotal = $profile->is_active ? $activationDays : 0;
                  
                  $priorityLevel = $profile->priority_level ?? 0;
                  $priorityCost = $priorityLevel > 0 ? $priorityLevel . ' руб./день' : 'не подключено';
                  $priorityDays = $priorityLevel > 0 ? $profile->priority_days ?? 0 : 0;
                  $priorityTotal = $priorityLevel > 0 ? ($priorityLevel * $priorityDays) : 0;
                  
                  $vipCost = $profile->is_vip ? '500 руб./день' : 'не подключено';
                  $vipDays = $profile->is_vip ? $profile->vip_days ?? 0 : 0;
                  $vipTotal = $profile->is_vip ? (500 * $vipDays) : 0;
                  
                  $totalSpent = $activationTotal + $priorityTotal + $vipTotal;
                @endphp
                
                <div class="grid grid-cols-3 gap-4 mb-4">
                  <div class="bg-[#121212] p-3 rounded">
                    <div class="text-center mb-2">Активация</div>
                    <div class="text-center text-lg font-bold">{{ number_format($activationTotal, 0, '.', ' ') }} ₽</div>
                    <div class="text-center text-xs text-gray-400">{{ $activationCost }}</div>
                  </div>
                  <div class="bg-[#121212] p-3 rounded">
                    <div class="text-center mb-2">Приоритет</div>
                    <div class="text-center text-lg font-bold">{{ number_format($priorityTotal, 0, '.', ' ') }} ₽</div>
                    <div class="text-center text-xs text-gray-400">{{ $priorityCost }}</div>
                  </div>
                  <div class="bg-[#121212] p-3 rounded">
                    <div class="text-center mb-2">VIP</div>
                    <div class="text-center text-lg font-bold">{{ number_format($vipTotal, 0, '.', ' ') }} ₽</div>
                    <div class="text-center text-xs text-gray-400">{{ $vipCost }}</div>
                  </div>
                </div>
                
                <div class="mt-4 p-3 bg-[#121212] rounded">
                  <div class="flex justify-between items-center border-b border-gray-700 pb-2 mb-2">
                    <span class="font-medium">Детали расходов</span>
                    <span class="font-medium">{{ number_format($totalSpent, 0, '.', ' ') }} ₽</span>
                  </div>
                  
                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span>Активация:</span> <span>{{$activationCost}}</span>
                    </div>
                    @if($profile->is_active && $activationDays > 0)
                      <div class="text-gray-500 text-right">({{$activationDays}} дн./{{$activationTotal}} руб.)</div>
                    @endif
                    
                    <div class="flex justify-between">
                      <span>Приоритет:</span> <span>{{$priorityCost}}</span>
                    </div>
                    @if($priorityLevel > 0 && $priorityDays > 0)
                      <div class="text-gray-500 text-right">({{$priorityDays}} дн./{{$priorityTotal}} руб.)</div>
                    @endif
                    
                    <div class="flex justify-between">
                      <span>VIP профиль:</span> <span>{{$vipCost}}</span>
                    </div>
                    @if($profile->is_vip && $vipDays > 0)
                      <div class="text-gray-500 text-right">({{$vipDays}} дн./{{$vipTotal}} руб.)</div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="modal-footer border-gray-700">
                <button type="button" class="px-4 py-2 bg-gray-800 text-white rounded" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
          </div>
        </div>
      </td>

      <!-- Actions cell -->
      <td class="p-4 align-top">
        <div class="flex items-center justify-end space-x-3">
          @if ($profile->is_vip)
          <a  href="{{route('profiles.view', $profile->id)}}" class="px-2 py-1 text-sm text-gray-400">
            Премиум
          </a>
          @else          
          <a  href="{{route('profiles.view', $profile->id)}}" class="px-2 py-1 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded text-xs">
            Рекламировать
          </a>
          @endif
          
          <!-- Dropdown menu -->
          <div x-data="{ open: false }">
            <button @click="open = !open" class="text-[#C2C2C2] hover:text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
              </svg>
            </button>
            <div x-show="open" x-transition @click.away="open = false" class="absolute right-0 mt-2 mr-8 w-48 bg-[#191919] rounded-md shadow-lg z-50" style="z-index: 9999;">
              <div class="py-1">
                <a href="{{route('user.profiles.edit', $profile->id)}}" class="block px-4 py-2 text-sm text-[#C2C2C2] hover:bg-gray-800 hover:text-white">
                  Редактировать
                </a>
                
                @php
                  $verification = \App\Models\VerificationPhoto::where('profile_id', $profile->id)->first();
                @endphp
                
                @if (!$profile->is_verified)
                  @if(isset($verification) && $verification->status === 'rejected')
                  <a href="{{route('user.profiles.verification.reapply', $profile->id)}}" class="block px-4 py-2 text-sm text-red-500 hover:bg-gray-800 hover:text-white">
                    Повторная верификация
                  </a>
                  @else
                  <a href="{{route('user.profiles.verification.form', $profile->id)}}" class="block px-4 py-2 text-sm text-[#C2C2C2] hover:bg-gray-800 hover:text-white">
                    Верифицировать фото
                  </a>
                  @endif
                @else
                <span class="block px-4 py-2 text-sm text-green-500">
                  Фото верифицировано ✓
                </span>
                @endif
                
                @if (!$profile->is_active)
                <a href="{{ route('user.profiles.archive', $profile->id) }}" class="block px-4 py-2 text-sm text-[#C2C2C2] hover:bg-gray-800 hover:text-white">
                  Архивировать
                </a>
                @endif
                
                <form action="{{ route('user.profiles.destroy', $profile->id) }}" method="POST" class="block"
                  x-data="{}"
                  @submit.prevent="if (confirm('Вы уверены, что хотите удалить этот профиль?')) $el.submit();">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-800 hover:text-red-400">
                    Удалить
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </td>
      </tr>

    @endforeach
      </tbody>
      </table>

      <!-- Archived Profiles Section -->
      @if(count($archivedProfiles) > 0)
      <div  x-data="{ showArchive: false }" class="mt-5"> 
        <a href="#" 
     @click.prevent="showArchive = !showArchive" 
     class="text-[#C2C2C2] hover:text-white text-sm underline">
    Архив анкет ({{ $archivedCount }})
  </a>
  <div 
  x-show="showArchive" 
  x-transition:enter="transition-opacity duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition-opacity duration-300"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
  class="mt-8">
    <table class="min-w-full table-auto">
          <thead>
            <tr class="bg-[#191919]">
              <th class="w-1/3 p-4 text-sm text-white text-left">Основные данные</th>
              <th class="w-1/3 p-4 text-sm text-white text-left">Локация</th>
              <th class="w-1/6 p-4 text-sm text-white text-center">Просмотры</th>
              <th class="w-1/6 p-4 text-sm text-white text-center">Клики</th>
              <th class="w-1/6 p-4 text-sm text-white text-left">Действия</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($archivedProfiles as $profile)
              <tr class="border-b border-gray-800 opacity-70 hover:opacity-100 transition-opacity">
                <!-- Profile cell -->
                <td class="p-4 align-top">
                  <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full overflow-hidden mr-3 bg-gray-800">
                      <img src="{{ asset('storage/' . $profile->primaryImage->path) }}" alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <div>
                      <div class="flex items-center gap-2">
                        <span class="font-medium text-white">{{$profile->name}}, {{$profile->age}}</span>
                        <span class="text-xs bg-gray-700 text-white px-2 py-0.5 rounded-md">Архив</span>
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Location cell -->
                <td class="p-4 text-sm text-[#C2C2C2] align-top">
                  <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                      <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                      <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099" font-family="Arial, sans-serif">M</text>
                    </svg>
                    @foreach ($profile->neighborhoods as $key => $neighborhood)
                      {{ $neighborhood->name}}
                      @if ($key < count($profile->neighborhoods) - 1)
                        ,
                      @endif
                    @endforeach
                  </div>
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                      <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                      <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099" font-family="Arial, sans-serif">M</text>
                    </svg>
                    @foreach ($profile->metroStations as $key => $metroStation)
                      м. {{ $metroStation->name }}
                      @if ($key < count($profile->metroStations) - 1)
                        ,
                      @endif
                    @endforeach
                  </div>
                </td>

                <!-- Views cell -->
                <td class="p-4 text-sm text-[#C2C2C2] text-center align-top">
                  {{$profile->views_count}}
                </td>

                <!-- Clicks cell -->
                <td class="p-4 text-sm text-[#C2C2C2] text-center align-top">
                  {{$profile->clicks_count}}
                </td>

                <!-- Actions cell -->
                <td class="p-4 align-top">
                  <div class="flex items-center justify-end space-x-3">
                    <form action="{{ route('user.profiles.restore', $profile->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="px-2 py-1 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded text-xs">
                        Восстановить
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      </div>
      @endif
    </div>
    </div>


    <!-- Profiles list - Mobile -->
    <div class="lg:hidden space-y-4 md:space-y-0 md:grid md:grid-cols-2 md:gap-4">

    @foreach ($profiles as $profile)

    <div class="bg-[#191919] rounded-2xl overflow-hidden">
      <div class="p-5">
      <div class="flex items-start justify-between">
      <div class="flex items-center">
      <div class="w-16 h-16 rounded-full overflow-hidden mr-3 bg-gray-800">
        <img src="{{ asset('storage/' . $profile->primaryImage->path) }}" alt="Profile"
        class="w-full h-full object-cover">
      </div>
      <div>
        <div class="flex items-center gap-2">
        <span class="font-medium text-lg">{{$profile->name}}, {{$profile->age}}</span>
        @if ($profile->is_active)
      <span class="text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
    @else
    <span class="text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
  @endif
        </div>
        <div class="text-xs text-gray-400">Анкета добавлена {{$profile->created_at->format('d.m.Y')}}</div>
      </div>
      </div>
      </div>

      <div class="mt-4 space-y-2 text-sm">
      <div class="flex items-center text-[#C2C2C2]">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
        font-family="Arial, sans-serif">M</text>
      </svg>
      @foreach ($profile->neighborhoods as $key => $neighborhood)
     <span>
      {{$neighborhood->name}}{{$key < count($profile->neighborhoods) - 1 ? ',' : '' ;}}
     </span>
    @endforeach
      </div>
      <div class="flex items-center text-[#C2C2C2]">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
        font-family="Arial, sans-serif">M</text>
      </svg>
      @foreach ($profile->metroStations as $key => $metroStation)
      м. {{ $metroStation->name }}
      @if ($key < count($profile->metroStations) - 1)
      ,
    @endif
    @endforeach
      </div>
      </div>

      <!-- Stats -->
      <div class="text-center mt-5 pb-5 border-b border-gray-800">
      <div class="flex justify-between mb-2">
      <div class="text-white">Просмотры</div>
      <div class="text-sm text-gray-400">{{$profile->views_count}}</div>
      </div>
      <div class="flex justify-between mb-2">
      <div class="text-white">Клики</div>
      <div class="text-sm text-gray-400">{{$profile->clicks_count}}</div>
      </div>
      <div class="flex justify-between mb-2">
      <div class="text-white">Реклама</div>
      <div class="flex items-center">
        @if ($profile->is_vip)
          <a href="{{route('profiles.view', $profile->id)}}" class="text-sm text-gray-400 mr-2">
            Премиум
          </a>
        @else          
          <a href="{{route('profiles.view', $profile->id)}}" class="px-2 py-1 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded text-xs mr-2">
            Рекламировать
          </a>
        @endif
        
        <!-- Ad Stats Icon with Tooltip -->
        <div x-data="{showTooltip: false}" class="relative">
          <button @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" class="text-gray-400 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </button>
          
          <div x-show="showTooltip" 
               x-transition:enter="transition ease-out duration-200" 
               x-transition:enter-start="opacity-0 scale-95" 
               x-transition:enter-end="opacity-100 scale-100" 
               x-transition:leave="transition ease-in duration-150" 
               x-transition:leave-start="opacity-100 scale-100" 
               x-transition:leave-end="opacity-0 scale-95"
               class="absolute right-0 bottom-full mb-2 w-64 bg-[#191919] shadow-lg rounded-md p-3 text-xs z-50">
            @php
              $activationCost = $profile->is_active ? '1 руб./день' : '0 руб.';
              $activationDays = $profile->is_active ? $profile->active_days ?? 0 : 0;
              $activationTotal = $profile->is_active ? $activationDays : 0;
              
              $priorityLevel = $profile->priority_level ?? 0;
              $priorityCost = $priorityLevel > 0 ? $priorityLevel . ' руб./день' : 'не подключено';
              $priorityDays = $priorityLevel > 0 ? $profile->priority_days ?? 0 : 0;
              $priorityTotal = $priorityLevel > 0 ? ($priorityLevel * $priorityDays) : 0;
              
              $vipCost = $profile->is_vip ? '500 руб./день' : 'не подключено';
              $vipDays = $profile->is_vip ? $profile->vip_days ?? 0 : 0;
              $vipTotal = $profile->is_vip ? (500 * $vipDays) : 0;
              
              $totalSpent = $activationTotal + $priorityTotal + $vipTotal;
            @endphp
            
            <div class="font-medium text-white mb-2">Расходы на рекламу:</div>
            <div class="flex justify-between"><span>Активация:</span> <span>{{$activationCost}}</span></div>
            @if($profile->is_active && $activationDays > 0)
              <div class="text-gray-500 text-right">({{$activationDays}} дн./{{$activationTotal}} руб.)</div>
            @endif
            
            <div class="flex justify-between"><span>Приоритет:</span> <span>{{$priorityCost}}</span></div>
            @if($priorityLevel > 0 && $priorityDays > 0)
              <div class="text-gray-500 text-right">({{$priorityDays}} дн./{{$priorityTotal}} руб.)</div>
            @endif
            
            <div class="flex justify-between"><span>VIP профиль:</span> <span>{{$vipCost}}</span></div>
            @if($profile->is_vip && $vipDays > 0)
              <div class="text-gray-500 text-right">({{$vipDays}} дн./{{$vipTotal}} руб.)</div>
            @endif
            
            <div class="mt-1 pt-1 border-t border-gray-700 flex justify-between font-medium">
              <span>Всего:</span> <span>{{$totalSpent}} руб.</span>
            </div>
          </div>
        </div>
      </div>
      </div>
      </div>


      <!-- Actions -->
      <div class="">
        @php
        $verification = \App\Models\VerificationPhoto::where('profile_id', $profile->id)->first();
      @endphp
      <!-- Verification status/button -->
      @if ($profile->is_verified)
      <div class="w-full py-3 text-center text-green-500 mb-2">
        <span>Фото верифицировано ✓</span>
      </div>
      @else
      @if(isset($verification) && $verification->status === 'rejected')
      <a href="{{route('user.profiles.verification.reapply', $profile->id)}}" class="block w-full py-3 transition hover:text-gray-400 text-red-500 mb-2 text-center">
        Повторная верификация
      </a>
      @else
      <a href="{{route('user.profiles.verification.form', $profile->id)}}"
        class="block w-full py-3 transition hover:text-gray-400 text-white mb-2 text-center">
          Верифицировать фото
        </a>
      @endif
      @endif
      
      <a href="{{route('user.profiles.edit', $profile->id)}}"
      class="block w-full py-3 bg-[#6340FF] hover:bg-[#5737e7] rounded-xl text-white transition text-center mb-2">
      Редактировать
      </a>
      
      @if (!$profile->is_active)
      <a href="{{ route('user.profiles.archive', $profile->id) }}" class="block w-full py-3 text-[#C2C2C2] hover:text-white text-center">
        Архивировать анкету
      </a>
      @endif
      
      <form action="{{ route('user.profiles.destroy', $profile->id) }}" method="POST" class="w-full" x-data="{}"
      @submit.prevent="if (confirm('Вы уверены, что хотите удалить этот профиль?')) $el.submit();">
      @csrf
      @method('DELETE')
      <button type="submit" class="w-full py-3 text-red-500 hover:text-red-400">
        Удалить анкету
      </button>
      </form>
      </div>
      </div>
    </div>

  @endforeach

    </div>

    <!-- Archived Profiles Section - Mobile -->
    @if(count($archivedProfiles) > 0)
    <div  x-data="{ showArchive: false }" class="mt-5 lg:hidden"> 
      <a href="#" 
   @click.prevent="showArchive = !showArchive" 
   class="text-[#C2C2C2] hover:text-white text-sm underline">
  Архив анкет ({{ $archivedCount }})
</a>
<div 
x-show="showArchive" 
x-transition:enter="transition-opacity duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
class="mt-8 lg:hidden">
      <div class="space-y-4 md:space-y-0 md:grid md:grid-cols-2 md:gap-4">
        @foreach ($archivedProfiles as $profile)
        <div class="bg-[#191919] rounded-2xl overflow-hidden opacity-70 hover:opacity-100 transition-opacity">
          <div class="p-5">
            <div class="flex items-start justify-between">
              <div class="flex items-center">
                <div class="w-16 h-16 rounded-full overflow-hidden mr-3 bg-gray-800">
                  <img src="{{ asset('storage/' . $profile->primaryImage->path) }}" alt="Profile" class="w-full h-full object-cover">
                </div>
                <div>
                  <div class="flex items-center gap-2">
                    <span class="font-medium text-lg">{{$profile->name}}, {{$profile->age}}</span>
                    <span class="text-xs bg-gray-700 text-white px-2 py-0.5 rounded-md">Архив</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-4 space-y-2 text-sm">
              <div class="flex items-center text-[#C2C2C2]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                  <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099" font-family="Arial, sans-serif">M</text>
                </svg>
                @foreach ($profile->neighborhoods as $key => $neighborhood)
                <span>
                  {{$neighborhood->name}}{{$key < count($profile->neighborhoods) - 1 ? ',' : '' ;}}
                </span>
                @endforeach
              </div>
              <div class="flex items-center text-[#C2C2C2]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                  <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099" font-family="Arial, sans-serif">M</text>
                </svg>
                @foreach ($profile->metroStations as $key => $metroStation)
                м. {{ $metroStation->name }}
                @if ($key < count($profile->metroStations) - 1)
                ,
                @endif
                @endforeach
              </div>
            </div>

            <!-- Stats -->
            <div class="text-center mt-5 pb-5 border-b border-gray-800">
              <div class="flex justify-between mb-2">
                <div class="text-white">Просмотры</div>
                <div class="text-sm text-gray-400">{{$profile->views_count}}</div>
              </div>
              <div class="flex justify-between mb-2">
                <div class="text-white">Клики</div>
                <div class="text-sm text-gray-400">{{$profile->clicks_count}}</div>
              </div>
            </div>

            <!-- Actions -->
            <div class="py-3">
              <form action="{{ route('user.profiles.restore', $profile->id) }}" method="POST">
                @csrf
                <button type="submit" class="block w-full py-3 bg-[#6340FF] hover:bg-[#5737e7] rounded-xl text-white transition text-center">
                  Восстановить
                </button>
              </form>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif
  @endif

</x-app-layout>