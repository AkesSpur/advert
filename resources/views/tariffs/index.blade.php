<x-app-layout>
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Рекламные тарифы</h1>
        <div class="mt-3 sm:mt-0">
            <a href="#" class="inline-flex items-center px-4 py-2 bg-[#191919] hover:bg-[#252525] text-white rounded-md transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Помощь по тарифам
            </a>
        </div>
    </div>

       <!-- Tariffs Grid -->
       <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-6 mb-6">
        @php
            $userProfiles = auth()->user()?->profiles()->with(['metroStations', 'primaryImage'])->get() ?? collect();
        @endphp
        
        <!-- Базовый тариф -->
        @foreach ($tariffs as $type)
            @if ($type->slug === 'basic')
            <div x-data="{ showModal: false }" class="bg-[#191919] rounded-2xl overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl border-b border-[#363636] font-medium mb-6 pb-4">Базовая активация</h3>
                    <p class="text-[#C2C2C2] mb-6 text-sm font-medium">
                       {{$type->description}}
                    </p>
                    <div class="flex justify-between items-center mt-auto">
                        <span class="text-white">от {{round($type->base_price, 0)}} ₽/день</span>
                        <button @click="showModal = true"
                                class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                            Подключить
                        </button>
                    </div>
                </div>
                
                <!-- Modal -->
                <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-black opacity-75"></div>
                        </div>
                        
                        <div class="inline-block align-bottom bg-[#191919] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-2xl font-bold">Выберите анкету</h3>
                                    <button @click="showModal = false" class="text-white text-2xl">&times;</button>
                                </div>
                                
                                <form method="POST" action="{{ route('user.advert.activate') }}">
                                    @csrf
                                    <input type="hidden" name="tariff_type" value="basic">
                                    
                                    <div class="space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar">
                                        @foreach ($userProfiles as $profile)
                                            @if ($profile->is_active == false)
                                            <label class="flex items-start space-x-3 p-3 rounded cursor-pointer">
                                                <input type="radio" name="profile_id" value="{{ $profile->id }}" required class="my-auto w-6 h-6 text-[#6340FF] bg-transparent accent-[#6340FF]">
                                                <div class="flex">
                                                    <div class="flex ml-3 items-center space-x-2">
                                                        <img src="{{asset('storage/' . $profile->primaryImage->path) }}" class="w-12 h-12 rounded-full object-cover" />
                                                        <div>
                                                            <div class="text-white font-lg">
                                                                {{ $profile->name }}, {{$profile->age}}
                                                                @if ($profile->is_active == true)
                                                                <span class="ml-2 text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
                                                              @else
                                                              <span class="ml-2 text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
                                                            @endif
                                                            </div>
                                                            <div class="text-xs mt-1 text-[#636363]">Анкета добавлена {{ $profile->created_at->format('d.m.Y') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="ml-5 text-sm text-[#C2C2C2] hidden md:block">
                                                        <div class="flex mt-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                                                                <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                                                                <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
                                                                font-family="Arial, sans-serif">M</text>
                                                                </svg>
                                                                <span class="pl-1">
                                                                    {{ $profile->neighborhoods->pluck('name')->join(', ') }}
                                                                </span>
                                                        </div>
                                                        
                                                        <div class="flex mt-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                                                                <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                                                                <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
                                                                font-family="Arial, sans-serif">M</text>
                                                                </svg>
                                                                <span class="pl-1">
                                                                    {{ $profile->metroStations->pluck('name')->join(', ') }}
                                                                </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-6">
                                        <button type="submit" class="w-full bg-[#6340FF] text-white py-2 rounded-2xl hover:bg-[#5737e7] transition">
                                            Подключить
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
            @endif
        @endforeach

        @foreach ($tariffs as $type)
        @if ($type->slug === 'priority')
                    <!-- Приоритетный тариф -->
        <div x-data="{ showModal: false }" class="bg-[#191919] rounded-2xl overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl border-b border-[#363636] font-medium mb-6 pb-4">Приоритетное объявление</h3>
                <p class="text-[#C2C2C2] mb-6 text-sm font-medium">
                    {{$type->description}}
                </p>
                <div class="flex justify-between items-center mt-auto">
                    <span class="text-white">от {{round($type->base_price, 0)}} ₽/день</span>
                    <button @click="showModal = true"
                            class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                        Подключить
                    </button>
                </div>
            </div>
            
            <!-- Modal -->
            <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-black opacity-75"></div>
                    </div>
                    
                    <div class="inline-block align-bottom bg-[#191919] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-2xl font-bold">Выберите анкету</h3>
                                <button @click="showModal = false" class="text-white text-2xl">&times;</button>
                            </div>
                            
                            <form method="POST" action="{{ route('user.advert.activate') }}">
                                @csrf
                                <input type="hidden" name="tariff_type" value="priority">
                                
                                <!-- Priority Level with Range Slider -->
                                <div x-data="{ priorityLevel: 1 }">
                                    <label class="block text-sm text-white mb-1">Уровень приоритета: <span x-text="priorityLevel" class="font-bold"></span></label>
                                    <div class="flex items-center space-x-2">
                                        <input type="range" name="priority_level" min="1" max="20" x-model="priorityLevel" 
                                        class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gradient-to-r from-[#6340FF] to-[#1f1f1f]"
                                        :style="`background: linear-gradient(to right, #6340FF 0%, #6340FF ${(priorityLevel - 1) * 5}%, #1f1f1f ${(priorityLevel - 1) * 5}%, #1f1f1f 100%)`"
                                    >
                                        <span x-text="priorityLevel" class="text-white font-bold text-lg min-w-[30px] text-center"></span>
                                    </div>
                                    <p class="text-xs text-[#C2C2C2] mt-1">Стоимость: <span x-text="1 + parseInt(priorityLevel) + ' ₽/день'"></span></p>
                                </div>
                                
                                <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar mt-4">
                                    @foreach ($userProfiles as $profile)
                                        <label class="flex items-start space-x-3 p-3 rounded cursor-pointer">
                                            <input type="radio" name="profile_id" value="{{ $profile->id }}" required class="my-auto w-6 h-6 text-[#6340FF] bg-transparent accent-[#6340FF]">
                                            <div class="flex">
                                                <div class="flex ml-3 items-center space-x-2">
                                                    <img src="{{asset('storage/' . $profile->primaryImage->path) }}" class="w-12 h-12 rounded-full object-cover" />
                                                    <div>
                                                        <div class="text-white font-lg">
                                                            {{ $profile->name }}, {{$profile->age}}
                                                            @if ($profile->is_active == true)
                                                            <span class="ml-2 text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
                                                          @else
                                                          <span class="ml-2 text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
                                                        @endif
                                                        </div>
                                                        <div class="text-xs mt-1 text-[#636363]">Анкета добавлена {{ $profile->created_at->format('d.m.Y') }}</div>
                                                    </div>
                                                </div>
                                                <div class="ml-5 text-sm text-[#C2C2C2] hidden md:block">
                                                    <div class="flex mt-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                                                            <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
                                                            font-family="Arial, sans-serif">M</text>
                                                            </svg>
                                                            <span class="pl-1">
                                                                {{ $profile->neighborhoods->pluck('name')->join(', ') }}
                                                            </span>
                                                    </div>
                                                    
                                                    <div class="flex mt-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                                                            <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
                                                            font-family="Arial, sans-serif">M</text>
                                                            </svg>
                                                            <span class="pl-1">
                                                                {{ $profile->metroStations->pluck('name')->join(', ') }}
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                @endforeach
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" class="w-full bg-[#6340FF] text-white py-2 rounded-2xl hover:bg-[#5737e7] transition">
                                        Подключить
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endif
        @endforeach    


        @foreach ($tariffs as $type)
        @if ($type->slug === 'vip')
                    <!-- VIP тариф -->
        <div x-data="{ showModal: false }" class="bg-[#191919] rounded-2xl overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl border-b border-[#363636] font-medium mb-6 pb-4">VIP анкета</h3>
                <p class="text-[#C2C2C2] mb-6 text-sm font-medium">
                    {{$type->description}}
                </p>
                <div class="flex justify-between items-center mt-auto">
                    <span class="text-white">от {{round($type->fixed_price,0)}} ₽/день</span>
                    <button @click="showModal = true"
                            class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                        Подключить
                    </button>
                </div>
            </div>
            
            <!-- Modal -->
            <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-black opacity-75"></div>
                    </div>
                    
                    <div class="inline-block align-bottom bg-[#191919] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-2xl font-bold">Выберите анкету</h3>
                                <button @click="showModal = false" class="text-white text-2xl">&times;</button>
                            </div>
                            
                            <form method="POST" action="{{ route('user.advert.activate') }}">
                                @csrf
                                <input type="hidden" name="tariff_type" value="vip">
                                
                                <!-- VIP Availability Information -->
                                <div class="mb-4 p-3 rounded-lg bg-[#1f1f1f]">
                                    @if(isset($activeVipCount))
                                        @if($activeVipCount < 3)
                                            <div class="flex items-center text-green-400 mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                <span>Доступно VIP мест: {{ 3 - $activeVipCount }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center text-yellow-400 mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <span>Все VIP места заняты</span>
                                            </div>
                                            @if(isset($nextAvailableVipDate))
                                                <p class="text-sm text-[#C2C2C2] ml-7 mb-2">Следующее освобождение: {{ Carbon\Carbon::parse($nextAvailableVipDate)->format('d.m.Y') }}</p>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                
                                <!-- VIP Duration with Radio Buttons -->
                                <div class="mb-4">
                                    <label class="block text-sm text-white mb-2">Срок размещения</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="flex flex-col items-center justify-center bg-[#1f1f1f] p-3 rounded cursor-pointer border-2 border-transparent hover:border-[#6340FF] transition-all">
                                            <input type="radio" name="vip_duration" value="1_day" checked class="sr-only peer">
                                            <div class="w-full h-full flex flex-col items-center peer-checked:text-[#6340FF] peer-checked:font-bold">
                                                <span class="text-lg font-medium">1 день</span>
                                                <span class="text-sm mt-1">{{round($type->fixed_price, 0)}} ₽</span>
                                            </div>
                                        </label>
                                        <label class="flex flex-col items-center justify-center bg-[#1f1f1f] p-3 rounded cursor-pointer border-2 border-transparent hover:border-[#6340FF] transition-all">
                                            <input type="radio" name="vip_duration" value="1_week" class="sr-only peer">
                                            <div class="w-full h-full flex flex-col items-center peer-checked:text-[#6340FF] peer-checked:font-bold">
                                                <span class="text-lg font-medium">1 неделя</span>
                                                <span class="text-sm mt-1">{{round($type->weekly_price, 0)}} ₽</span>
                                            </div>
                                        </label>
                                        <label class="flex flex-col items-center justify-center bg-[#1f1f1f] p-3 rounded cursor-pointer border-2 border-transparent hover:border-[#6340FF] transition-all">
                                            <input type="radio" name="vip_duration" value="1_month" class="sr-only peer">
                                            <div class="w-full h-full flex flex-col items-center peer-checked:text-[#6340FF] peer-checked:font-bold">
                                                <span class="text-lg font-medium">1 месяц</span>
                                                <span class="text-sm mt-1">{{round($type->monthly_price, 0)}} ₽</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar mt-4">
                                    @foreach ($userProfiles as $profile)
                                    <label class="flex items-start space-x-3 p-3 rounded cursor-pointer">
                                        <input type="radio" name="profile_id" value="{{ $profile->id }}" required class="my-auto w-6 h-6 text-[#6340FF] bg-transparent accent-[#6340FF]">
                                        <div class="flex">
                                            <div class="flex ml-3 items-center space-x-2">
                                                <img src="{{asset('storage/' . $profile->primaryImage->path) }}" class="w-12 h-12 rounded-full object-cover" />
                                                <div>
                                                    <div class="text-white font-lg">
                                                        {{ $profile->name }}, {{$profile->age}}
                                                        @if ($profile->is_active == true)
                                                        <span class="ml-2 text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
                                                      @else
                                                      <span class="ml-2 text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
                                                    @endif
                                                    </div>
                                                    <div class="text-xs mt-1 text-[#636363]">Анкета добавлена {{ $profile->created_at->format('d.m.Y') }}</div>
                                                </div>
                                            </div>
                                            <div class="ml-5 text-sm text-[#C2C2C2] hidden md:block">
                                                <div class="flex mt-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                                                        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
                                                        font-family="Arial, sans-serif">M</text>
                                                        </svg>
                                                        <span class="pl-1">
                                                            {{ $profile->neighborhoods->pluck('name')->join(', ') }}
                                                        </span>
                                                </div>
                                                
                                                <div class="flex mt-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
                                                        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
                                                        font-family="Arial, sans-serif">M</text>
                                                        </svg>
                                                        <span class="pl-1">
                                                            {{ $profile->metroStations->pluck('name')->join(', ') }}
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" class="w-full bg-[#6340FF] text-white py-2 rounded-2xl hover:bg-[#5737e7] transition">
                                        Подключить
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endif
        @endforeach
    </div>

    @if(isset($userActiveTariffs) && $userActiveTariffs->count() > 0)
    <div class="bg-[#191919] rounded-xl p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Ваши активные тарифы</h2>
    
        <div class="space-y-4">
            @foreach($userActiveTariffs as $index => $activeTariff)
            <div class="border-b border-[#363636] pb-4 last:border-0 last:pb-0">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <!-- Profile Info -->
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('storage/' . $activeTariff->profile->primaryImage->path) }}" class="w-12 h-12 rounded-full object-cover" />
                        <div>
                            <div class="text-white font-medium">
                                {{ $activeTariff->profile->name }}, {{ $activeTariff->profile->age }}
                                @if($activeTariff->profile->is_active)
                                <span class="ml-2 text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
                                @else
                                <span class="ml-2 text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
                                @endif
                            </div>
                            <div class="text-xs mt-0.5 text-[#636363]">Анкета добавлена {{ $activeTariff->profile->created_at->format('d.m.Y') }}</div>
                        </div>
                    </div>
    
                    <!-- Ad Details + Actions -->
                    <div class="mt-4 md:mt-0 flex flex-col md:flex-row md:items-center md:space-x-6 w-full justify-between">
                        <div class="text-sm text-[#C2C2C2]">
                            <div>
                                <span class="text-white font-medium">{{ $activeTariff->adTariff->name }}</span>
                                @if($activeTariff->isVip() && !$activeTariff->profile->is_vip)
                                    <span class="ml-2 px-2 py-1 bg-yellow-900 text-yellow-300 text-xs rounded-full">Ожидает</span>
                                @elseif($activeTariff->is_paused)
                                    <span class="ml-2 px-2 py-1 bg-yellow-900 text-yellow-300 text-xs rounded-full">Приостановлен</span>
                                @else
                                    <span class="ml-2 px-2 py-1 bg-green-900 text-green-300 text-xs rounded-full">Активен</span>
                                @endif
                            </div>
                            @if($activeTariff->expires_at)
                                <div class="mt-1">Действует до: {{ $activeTariff->expires_at->format('d.m.Y') }} ({{ $activeTariff->getRemainingDays() }} дней)</div>
                            @else
                                <div class="mt-1">Ежедневное списание: {{ $activeTariff->daily_charge }} ₽</div>
                            @endif
                            @if($activeTariff->isPriority() && $activeTariff->priority_level)
                                <div class="mt-1">Уровень приоритета: {{ $activeTariff->priority_level }}</div>
                            @elseif($activeTariff->isVip() && $activeTariff->queue_position > 0)
                                <div class="mt-1">Очередь VIP: {{ $activeTariff->queue_position }}</div>
                            @endif
                        </div>
    
                        @if(!$activeTariff->isVip())
                        <div class="flex space-x-3 mt-4 md:mt-0">
                            @if($activeTariff->is_paused)
                            <!-- Resume button (▶️) -->
                            <form action="{{ route('user.advert.resume', $activeTariff->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 hover:bg-[#5737e7] bg-[#6340FF] text-white rounded-full transition" title="Возобновить">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </button>
                            </form>
                            @else
                            <!-- Pause button (⏸️) -->
                            <form x-data @submit.prevent="if (confirm('Вы уверены, что хотите приостановить показ рекламы?')) $el.submit()" action="{{ route('user.advert.pause', $activeTariff->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 bg-[#323232] hover:bg-[#3d3d3d] text-white rounded-full transition" title="Приостановить">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <rect x="6" y="5" width="4" height="14" />
                                        <rect x="14" y="5" width="4" height="14" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                            
                            @if($activeTariff->isPriority())
                            <!-- Change Priority button -->
                            <button @click="$dispatch('open-modal', 'change-priority-{{ $activeTariff->id }}')" class="p-2 bg-[#323232] hover:bg-[#3d3d3d] text-white rounded-full transition" title="Изменить приоритет">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                </svg>
                            </button>
                            
                            <!-- Priority Change Modal -->
                            <div
                                x-data="{ isOpen: false, priority: {{ $activeTariff->priority_level ?? 17 }} }"
                                x-show="isOpen"
                                x-on:open-modal.window="if ($event.detail === 'change-priority-{{ $activeTariff->id }}') { isOpen = true }"
                                x-on:keydown.escape.window="isOpen = false"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-90"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-90"
                                class="fixed inset-0 z-50 overflow-y-auto" 
                                style="display: none;"
                            >
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div class="fixed inset-0 bg-black opacity-50" x-on:click="isOpen = false"></div>
                                    <div class="relative bg-[#191919] rounded-lg max-w-md w-full mx-auto p-6 shadow-xl">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-xl font-bold">Изменить приоритет</h3>
                                            <button @click="isOpen = false" class="text-white text-2xl">&times;</button>
                                        </div>
                                        
                                        <form action="{{ route('user.advert.update-priority', $activeTariff->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium mb-2">Уровень приоритета</label>
                                                <input 
                                                    type="number" 
                                                    name="priority_level" 
                                                    x-model="priority"
                                                    min="1" 
                                                    class="w-full bg-[#121212] border border-gray-700 rounded-lg px-4 py-2 text-white"
                                                >
                                                <p class="text-sm text-gray-400 mt-2">Стоимость: <span x-text="priority"></span> руб./день</p>
                                            </div>
                                            <div class="flex justify-end space-x-3">
                                                <button type="button" @click="isOpen = false" class="px-4 py-2 border border-gray-600 rounded-lg text-white">Отмена</button>
                                                <button type="submit" class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] rounded-lg text-white">Сохранить</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        
                            <!-- Deactivate button (⏹️) -->
                            <form x-data @submit.prevent="if (confirm('Вы уверены, что хотите остановить рекламу?')) $el.submit()" action="{{ route('user.advert.cancel', $activeTariff->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 bg-red-700 hover:bg-red-900 text-white rounded-full transition" title="Отменить">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <rect x="6" y="6" width="12" height="12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endif
                              
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    

</x-app-layout>