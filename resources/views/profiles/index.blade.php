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
        @if (!$profile->is_archived)
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
            <span class="font-medium text-white capitalize">{{$profile->name}}, {{$profile->age}}</span>
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
                          <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099" font-family="Arial, sans-serif">M</text>
                        </svg>
                        @foreach ($profile->neighborhoods->take(2) as $key => $neighborhood)
                          {{ $neighborhood->name}}
                          @if ($key < 1 && count($profile->neighborhoods) > 1 )
                            ,
                          @endif
                        @endforeach
                        @if (count($profile->neighborhoods) > 2 && count($profile->neighborhoods->take(2)) < count($profile->neighborhoods))
                          ...
                        @endif
                      </div>
                      <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                          <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                          <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099" font-family="Arial, sans-serif">M</text>
                        </svg>
                        @foreach ($profile->metroStations->take(2) as $key => $metroStation)
                          м. {{ $metroStation->name }}
                          @if ($key < 1 && count($profile->metroStations) > 1 )
                            ,
                          @endif
                        @endforeach
                        @if (count($profile->metroStations) > 2 && count($profile->metroStations->take(2)) < count($profile->metroStations))
                          ...
                        @endif
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
              @if ($profile->is_vip)
              <a  href="{{route('user.advert.index')}}" class="px-2 py-1 text-sm text-gray-400">
                Премиум
              </a>
              @else          
              <a  href="{{route('user.advert.index')}}" class="px-2 py-1 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded text-xs">
                Рекламировать
              </a>
              @endif
              
              <button onclick="document.getElementById('adStatsModal-{{ $profile->id }}').classList.remove('hidden')" class="text-[#C2C2C2] hover:text-white p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </button>
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
                    
                    <a href="{{ route('user.profiles.archive', $profile->id) }}" class="block px-4 py-2 text-sm text-[#C2C2C2] hover:bg-gray-800 hover:text-white">
                      Архивировать
                    </a>
                    
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
        @endif

    @endforeach
      </tbody>
      </table>

      <!-- Archived Profiles Section -->
      @if($archivedCount > 0)
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
              <th class="w-1/6 p-4 text-sm text-white text-left">Реклама</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($profiles as $profile)
              @if ($profile->is_archived)
              <tr class="border-b border-gray-800 opacity-70 hover:opacity-100 transition-opacity">
                <!-- Profile cell -->
                <td class="p-4 align-top">
                  <div class="flex items-center">
                  <div class="w-12 h-12 rounded-full overflow-hidden mr-3 bg-gray-800">
                  <img src="{{ asset('storage/' . $profile->primaryImage->path) }}" alt="Profile"
                  class="w-full h-full object-cover">
                  </div>
                  <div>
                  <div class="flex items-center gap-2">
                  <span class="font-medium text-white capitalize">{{$profile->name}}, {{$profile->age}}</span>
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
                                <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099" font-family="Arial, sans-serif">M</text>
                              </svg>
                              @foreach ($profile->neighborhoods->take(2) as $key => $neighborhood)
                                {{ $neighborhood->name}}
                                @if ($key < 1 && count($profile->neighborhoods) > 1 )
                                  ,
                                @endif
                              @endforeach
                              @if (count($profile->neighborhoods) > 2 && count($profile->neighborhoods->take(2)) < count($profile->neighborhoods))
                                ...
                              @endif
                            </div>
                            <div class="flex items-center">
                              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                                <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099" font-family="Arial, sans-serif">M</text>
                              </svg>
                              @foreach ($profile->metroStations->take(2) as $key => $metroStation)
                                м. {{ $metroStation->name }}
                                @if ($key < 1 && count($profile->metroStations) > 1 )
                                  ,
                                @endif
                              @endforeach
                              @if (count($profile->metroStations) > 2 && count($profile->metroStations->take(2)) < count($profile->metroStations))
                                ...
                              @endif
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
                    @if ($profile->is_vip)
                    <a  href="{{route('user.advert.index')}}" class="px-2 py-1 text-sm text-gray-400">
                      Премиум
                    </a>
                    @else          
                    <a  href="{{route('user.advert.index')}}" class="px-2 py-1 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded text-xs">
                      Рекламировать
                    </a>
                    @endif
                    
                    <button onclick="document.getElementById('adStatsModal-{{ $profile->id }}').classList.remove('hidden')" class="text-[#C2C2C2] hover:text-white p-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                      </svg>
                    </button>
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
                          
                            <form action="{{ route('user.profiles.restore', $profile->id) }}" method="POST">
                              @csrf
                              <button type="submit" class="block px-4 py-2 text-sm text-left text-[#C2C2C2] hover:bg-gray-800 hover:text-white w-full">
                                Разархивировать
                              </button>
                            </form>        
                          
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
              @endif
      
          @endforeach
          </tbody>
        </table>
      </div>
      </div>
      @endif
    </div>
    </div>
    
  <!-- Ad Stats Modals -->
  @if(count($profiles) > 0)
    @foreach ($profiles as $profile)
      <div id="adStatsModal-{{ $profile->id }}" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
          <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity"></div>
          <div class="relative bg-[#191919] rounded-lg max-w-3xl w-full mx-auto shadow-xl overflow-hidden">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-700">
              <h3 class="text-lg font-medium text-white capitalize">Статистика расходов на рекламу - {{ $profile->name }}</h3>
              <button onclick="document.getElementById('adStatsModal-{{ $profile->id }}').classList.add('hidden')" class="text-gray-400 hover:text-white">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div class="p-6">
              @if($profile->tariffs_count > 0)
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                  <div class="bg-[#222222] p-4 rounded-lg">
                    <div class="flex items-center">
                      <div class="bg-[#6340FF] p-3 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <div>
                        <div class="text-sm text-gray-400">Всего потрачено</div>
                        <div class="text-xl font-bold text-white">{{ number_format($totalSpent, 0, '.', ' ') }} ₽</div>
                      </div>
                    </div>
                  </div>
                  <div class="bg-[#222222] p-4 rounded-lg">
                    <div class="flex items-center">
                      <div class="bg-green-500 p-3 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        </svg>
                      </div>
                      <div>
                        <div class="text-sm text-gray-400">Базовая реклама</div>
                        <div class="text-xl font-bold text-white">{{ number_format($basicSpent, 0, '.', ' ') }} ₽</div>
                      </div>
                    </div>
                  </div>
                  <div class="bg-[#222222] p-4 rounded-lg">
                    <div class="flex items-center">
                      <div class="bg-yellow-500 p-3 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                      </div>
                      <div>
                        <div class="text-sm text-gray-400">Приоритет</div>
                        <div class="text-xl font-bold text-white">{{ number_format($prioritySpent, 0, '.', ' ') }} ₽</div>
                      </div>
                    </div>
                  </div>
                  <div class="bg-[#222222] p-4 rounded-lg">
                    <div class="flex items-center">
                      <div class="bg-red-500 p-3 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                      </div>
                      <div>
                        <div class="text-sm text-gray-400">VIP</div>
                        <div class="text-xl font-bold text-white">{{ number_format($vipSpent, 0, '.', ' ') }} ₽</div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Detailed Tariff History -->
                <h4 class="text-lg font-medium text-white mb-4">История рекламных тарифов</h4>
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                      <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Тип</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Дата активации</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Дата окончания</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Длительность</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Статус</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Сумма</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                      @foreach($profile->tariffs->sortByDesc('created_at') as $tariff)
                        @php
                          $tariffCharges = $tariff->charges->sum('amount');
                          $duration = $tariff->charges->count();
                          if ($tariff->is_active) {
                            $status = 'Активен';
                            $statusClass = 'bg-green-500';
                          } elseif ($tariff->is_paused) {
                            $status = 'Приостановлен';
                            $statusClass = 'bg-yellow-500';
                          } else {
                            $status = 'Завершен';
                            $statusClass = 'bg-gray-500';
                          }
                        @endphp
                        <tr>
                          <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-white">{{ $tariff->adTariff->name }}</div>
                            @if($tariff->isPriority() && $tariff->priority_level)
                              <div class="text-xs text-gray-400">Уровень: {{ $tariff->priority_level }}</div>
                            @endif
                          </td>
                          <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">{{ $tariff->created_at->format('d.m.Y') }}</td>
                          <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">{{ $tariff->expires_at ? $tariff->expires_at->format('d.m.Y') : 'Бессрочно' }}</td>
                          <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">{{ $duration }} дней</td>
                          <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }} text-white">
                              {{ $status }}
                            </span>
                          </td>
                          <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">{{ number_format($tariffCharges, 0, '.', ' ') }} ₽</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="text-center py-10">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2zm3-9V3a2 2 0 00-2-2H5a2 2 0 00-2 2v4M7 7h10" />
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-white">Нет данных о рекламных кампаниях</h3>
                  <p class="mt-1 text-sm text-gray-500">
                    Для данного профиля отсутствуют активные или завершенные рекламные тарифы. Статистика будет доступна после запуска рекламной кампании.
                  </p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    @endforeach
  @endif

    <!-- Profiles list - Mobile -->
    <div class="lg:hidden space-y-4 md:space-y-0 md:grid md:grid-cols-2 md:gap-4">
    @foreach ($profiles as $profile)
      @if (!$profile->is_archived)
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
        <span class="font-medium text-lg capitalize">{{$profile->name}}, {{$profile->age}}</span>
        @if ($profile->is_active)
      <span class="text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
    @else
    <span class="text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
  @endif
        </div>
        <div class="text-xs text-gray-400">Анкета добавлена {{$profile->created_at->format('d.m.Y')}}</div>
      </div>
      </div>
      <!-- Ad Stats Button -->
      <button onclick="document.getElementById('adStatsModal-{{ $profile->id }}').classList.remove('hidden')" class="text-[#C2C2C2] hover:text-white p-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
      </button>
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
      @if ($profile->is_vip)
          <a href="{{route('user.advert.index')}}" class="text-sm text-gray-400">
            Премиум
          </a>
          @else          
          <a href="{{route('user.advert.index')}}" class="px-2 py-1 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded text-xs">
            Рекламировать
          </a>
          @endif
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
      
      <a href="{{ route('user.profiles.archive', $profile->id) }}" class="block w-full py-3 text-[#C2C2C2] hover:text-white text-center">
        Архивировать анкету
      </a>
      
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
      
      @endif
    @endforeach
    </div>

    <!-- Archived Profiles Section - Mobile -->
    @if($archivedCount > 0)
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
        @foreach ($profiles as $profile)
        @if ($profile->is_archived)
      <div class="bg-[#191919] rounded-2xl overflow-hidden opacity-70 hover:opacity-100 transition-opacity">
        <div class="p-5">
        <div class="flex items-start justify-between">
        <div class="flex items-center">
        <div class="w-16 h-16 rounded-full overflow-hidden mr-3 bg-gray-800">
          <img src="{{ asset('storage/' . $profile->primaryImage->path) }}" alt="Profile"
          class="w-full h-full object-cover">
        </div>
        <div>
          <div class="flex items-center gap-2">
          <span class="font-medium text-lg capitalize">{{$profile->name}}, {{$profile->age}}</span>
          @if ($profile->is_active)
        <span class="text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
      @else
      <span class="text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
    @endif
          </div>
          <div class="text-xs text-gray-400">Анкета добавлена {{$profile->created_at->format('d.m.Y')}}</div>
        </div>
        </div>
        <!-- Ad Stats Button -->
        <button onclick="document.getElementById('adStatsModal-{{ $profile->id }}').classList.remove('hidden')" class="text-[#C2C2C2] hover:text-white p-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </button>
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
        @if ($profile->is_vip)
            <a href="{{route('user.advert.index')}}" class="text-sm text-gray-400">
              Премиум
            </a>
            @else          
            <a href="{{route('user.advert.index')}}" class="px-2 py-1 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded text-xs">
              Рекламировать
            </a>
            @endif
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
        
        <form action="{{ route('user.profiles.restore', $profile->id) }}" method="POST">
          @csrf
          <button type="submit" class="block w-full py-3 hover:text-gray-400 rounded-xl text-white transition text-center">
            Разархивировать
          </button>
        </form>
        
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
        @endif
      @endforeach

      </div>
    </div>
    @endif
    
  @endif

<script>
  // Close modal when clicking outside
  document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('[id^="adStatsModal-"]');
    
    modals.forEach(modal => {
      const modalContent = modal.querySelector('.relative');
      if (modal.classList.contains('hidden') === false && !modalContent.contains(event.target) && event.target !== modalContent) {
        // Check if the click is outside the modal content and not on a button that opens the modal
        if (!event.target.closest('button[onclick*="adStatsModal"]')) {
          modal.classList.add('hidden');
        }
      }
    });
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      const modals = document.querySelectorAll('[id^="adStatsModal-"]');
      modals.forEach(modal => {
        if (modal.classList.contains('hidden') === false) {
          modal.classList.add('hidden');
        }
      });
    }
  });
</script>

</x-app-layout>