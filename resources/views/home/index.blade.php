@extends('second-layout')

@section('content')
    
    <div class="py-6">
        
                    <div class="px-4 py-4" 
    x-data="{ 
        showModal: false,
        activeFilter: null,
        showServices: false,
        showMetro: false,
        showPrice: false,
        showAge: false,
        selectedService: '',
        selectedMetro: '',
        selectedPrice: '',
        selectedAge: '',
        services: {{ Js::from($services ?? []) }},
        metroStations: {{ Js::from($metroStations ?? []) }},
        prices: [
            { range: 'От 2000 до 3000 руб' },
            { range: 'От 3000 до 5000 руб' },
            { range: 'От 5000 до 7000 руб' },
            { range: 'От 7000 до 10000 руб' },
            { range: 'От 10000 руб' }
        ],
        ages: [
            { range: '18-20 лет' },
            { range: '20-25 лет' },
            { range: '25-30 лет' },
            { range: '30-35 лет' },
            { range: '35-40 лет' },
            { range: '40+ лет' }
        ],
        toggleFilter(filter) {
            const toggles = {
                services: 'showServices',
                metro: 'showMetro',
                price: 'showPrice',
                age: 'showAge'
            };
            for (const key in toggles) {
                this[toggles[key]] = (key === filter) ? !this[toggles[key]] : false;
            }
        },
        openModal(filter) {
            this.activeFilter = filter;
            this.showModal = true;
        },
        selectService(service) {
            this.selectedService = service;
            this.showServices = false;
            window.location.href = '{{ route("home") }}?service=' + encodeURIComponent(service);
        },
        selectMetro(station) {
            this.selectedMetro = station;
            this.showMetro = false;
            window.location.href = '{{ route("home") }}?metro=' + encodeURIComponent(station);
        },
        selectPrice(price) {
            this.selectedPrice = price;
            this.showPrice = false;
            window.location.href = '{{ route("home") }}?price=' + encodeURIComponent(price);
        },
        selectAge(age) {
            this.selectedAge = age;
            this.showAge = false;
            window.location.href = '{{ route("home") }}?age=' + encodeURIComponent(age);
        },
        resetFilters() {
            this.selectedService = '';
            this.selectedMetro = '';
            this.selectedPrice = '';
            this.selectedAge = '';
        }
    }"
>
    <!-- Rest of the HTML remains unchanged -->
    <!-- Filter Bar - All Screen Sizes -->
    <div class="flex max-w-screen-2xl px-2 2xl:px-4 mx-auto gap-2 flex-wrap">
        <!-- Services Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showServices }"
                 @click="toggleFilter('services')">
                <span x-text="selectedService || 'Услуги'"
                      :class="{ 'text-white': selectedService, 'text-[#FFFFFF66]': !selectedService }"></span>
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Services Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-[300%] shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showServices" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showServices = false"
                 style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-8">
                    <template x-for="service in services" :key="service.name">
                        <div @click="selectService(service.name)" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="service.name"></span>
                            <span x-text="'(' + service.count + ')'"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Metro Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showMetro }"
                 @click="toggleFilter('metro')">
                <span x-text="selectedMetro || 'Метро'"
                      :class="{ 'text-white': selectedMetro, 'text-[#FFFFFF66]': !selectedMetro }"></span>
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Metro Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-[300%] shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showMetro" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showMetro = false"
                 style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <template x-for="(stations, letter) in metroStations" :key="letter">
                        <div>
                            <div class="text-xl font-bold mb-2" x-text="letter"></div>
                            <div class="space-y-2">
                                <template x-for="station in stations" :key="station.name">
                                    <div @click="selectMetro(station.name)" 
                                         class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                                        <span x-text="station.name"></span>
                                        <span x-text="'(' + station.count + ')'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Price Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showPrice }"
                 @click="toggleFilter('price')">
                <span x-text="selectedPrice || 'Цена'"
                      :class="{ 'text-white': selectedPrice, 'text-[#FFFFFF66]': !selectedPrice }"></span>
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Price Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-[100%] shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showPrice" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showPrice = false"
                 style="display: none;">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="price in prices" :key="price.range">
                        <div @click="selectPrice(price.range)" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="price.range"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Age Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showAge }"
                 @click="toggleFilter('age')">
                <span x-text="selectedAge || 'Возраст'"
                      :class="{ 'text-white': selectedAge, 'text-[#FFFFFF66]': !selectedAge }"></span>
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Age Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-full shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showAge" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showAge = false"
                 style="display: none;">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="age in ages" :key="age.range">
                        <div @click="selectAge(age.range)" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="age.range"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Filter Button -->
    <div class="w-full mt-2 lg:hidden">
        <button @click="showModal = true"
                class="w-full px-4 py-3 rounded-xl bg-[#6340FF] text-white">
            Фильтры
        </button>
    </div>

    <!-- Mobile Modal -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 bg-black overflow-auto custom-scrollbar" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-800">
            <div class="text-xl font-bold">Logo</div>
            <button @click="showModal = false" class="text-white text-2xl">&times;</button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-4 space-y-4">
            <!-- Services -->
            <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                 :class="{ 'bg-[#6340FF]': activeFilter === 'services' }"
                 @click="activeFilter = activeFilter === 'services' ? null : 'services'">
                <div class="flex justify-between items-center">
                    <span x-text="selectedService || 'Услуги'" 
                          :class="{ 'text-white': selectedService, 'text-[#FFFFFF66]': !selectedService }"></span>
                    <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                        <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            
            <!-- Services Options -->
            <div x-show="activeFilter === 'services'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <template x-for="service in services" :key="service.name">
                        <div @click="selectService(service.name); activeFilter = null" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="service.name"></span>
                            <span x-text="'(' + service.count + ')'"></span>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Metro -->
            <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                 :class="{ 'bg-[#6340FF]': activeFilter === 'metro' }"
                 @click="activeFilter = activeFilter === 'metro' ? null : 'metro'">
                <div class="flex justify-between items-center">
                    <span x-text="selectedMetro || 'Метро'" 
                          :class="{ 'text-white': selectedMetro, 'text-[#FFFFFF66]': !selectedMetro }"></span>
                    <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                        <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            
            <!-- Metro Options -->
            <div x-show="activeFilter === 'metro'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="(stations, letter) in metroStations" :key="letter">
                        <div>
                            <div class="text-xl font-bold mb-2" x-text="letter"></div>
                            <div class="space-y-2">
                                <template x-for="station in stations" :key="station.name">
                                    <div @click="selectMetro(station.name); activeFilter = null" 
                                         class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                                        <span x-text="station.name"></span>
                                        <span x-text="'(' + station.count + ')'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Price and Age Row -->
            <div class="flex space-x-4">
                <!-- Price -->
                <div class="w-1/2 cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                     :class="{ 'bg-[#6340FF]': activeFilter === 'price' }"
                     @click="activeFilter = activeFilter === 'price' ? null : 'price'">
                    <div class="flex justify-between items-center">
                        <span x-text="selectedPrice || 'Цена'" 
                              :class="{ 'text-white': selectedPrice, 'text-[#FFFFFF66]': !selectedPrice }"></span>
                        <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                            <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Age -->
                <div class="w-1/2 cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                     :class="{ 'bg-[#6340FF]': activeFilter === 'age' }"
                     @click="activeFilter = activeFilter === 'age' ? null : 'age'">
                    <div class="flex justify-between items-center">
                        <span x-text="selectedAge || 'Возраст'" 
                              :class="{ 'text-white': selectedAge, 'text-[#FFFFFF66]': !selectedAge }"></span>
                        <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                            <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Price Options -->
            <div x-show="activeFilter === 'price'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <template x-for="price in prices" :key="price.range">
                        <div @click="selectPrice(price.range); activeFilter = null" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="price.range"></span>
                            <span x-text="'(' + price.count + ')'"></span>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Age Options -->
            <div x-show="activeFilter === 'age'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <template x-for="age in ages" :key="age.range">
                        <div @click="selectAge(age.range); activeFilter = null" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="age.range"></span>
                            <span x-text="'(' + age.count + ')'"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>


        <div class="max-w-screen-2xl px-6 mx-auto">
            <!-- Filter and Sort Container - Flex on lg, Column on md/sm -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                <!-- Filter Buttons -->
                <div class="flex overflow-x-auto hide-scrollbar justify-start gap-3 mb-4 lg:mb-0">
                    <a href="{{ route('home', ['filter' => 'all', 'sort' => request('sort', 'popular')]) }}" 
                       class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'all' || !$filter ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">Все анкеты</a>
                    <a href="{{ route('home', ['filter' => 'video', 'sort' => request('sort', 'popular')]) }}" 
                       class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'video' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">Есть видео</a>
                    <a href="{{ route('home', ['filter' => 'new', 'sort' => request('sort', 'popular')]) }}" 
                       class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'new' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">Новые</a>
                    <a href="{{ route('home', ['filter' => 'vip', 'sort' => request('sort', 'popular')]) }}" 
                       class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'vip' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">VIP</a>
                    <a href="{{ route('home', ['filter' => 'cheap', 'sort' => request('sort', 'popular')]) }}" 
                       class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'cheap' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">Дешевые</a>
                    <a href="{{ route('home', ['filter' => 'verified', 'sort' => request('sort', 'popular')]) }}" 
                       class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'verified' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">Фото проверены</a>
                </div>
                
                <!-- Sort Dropdown -->
                <div class="relative" x-data="{ 
                    showSortOptions: false, 
                    selectedSort: '{{ $sort == "popular" ? "Самые популярные" : ($sort == "cheapest" ? "Дешевые" : ($sort == "expensive" ? "Дорогие" : "Сортировать по умолчанию")) }}' 
                }">
    
                <button @click="showSortOptions = !showSortOptions" 
                            class="flex items-center w-full px-4 py-2 text-white rounded-lg hover:bg-[#252525] transition-colors">
                        <span class="text-white" x-text="selectedSort"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="{'transform rotate-180': showSortOptions}">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="showSortOptions" 
                         @click.away="showSortOptions = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute left-0 md:left-auto md:right-0 lg:right-0 mt-2 w-full md:w-64 bg-[#191919] rounded-lg shadow-lg z-50">
                        <div class="py-1">
                            <a href="{{ route('home', ['filter' => request('filter', 'all'), 'sort' => 'popular']) }}" 
                               @click="selectedSort = 'Самые популярные'; showSortOptions = false" 
                               class="block px-4 py-2 text-white hover:bg-[#252525]">Самые популярные</a>
                            <a href="{{ route('home', ['filter' => request('filter', 'all'), 'sort' => 'cheapest']) }}" 
                               @click="selectedSort = 'Дешевые'; showSortOptions = false" 
                               class="block px-4 py-2 text-white hover:bg-[#252525]">Дешевые</a>
                            <a href="{{ route('home', ['filter' => request('filter', 'all'), 'sort' => 'expensive']) }}" 
                               @click="selectedSort = 'Дорогие'; showSortOptions = false" 
                               class="block px-4 py-2 text-white hover:bg-[#252525]">Дорогие</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 px-6 max-w-screen-2xl mx-auto">
            @forelse ($profiles as $profile)
                <div class="h-full">
                    <x-ad-card
                        :id="$profile->id"
                        :new="$profile->created_at >= now()->subDays(7) ?? false"
                        :vip="$profile->is_vip ?? false"
                        :video="isset($profile->video->path) ?? false "
                        :verified="$profile->is_verified ?? false"
                        :name="$profile->name"
                        :age="$profile->age"
                        :weight="$profile->weight . ' кг'"
                        :height="$profile->height . ' см'"
                        :size="$profile->size . ' размер'"
                        :district="$profile->neighborhoods->isNotEmpty() ? 'р. ' . $profile->neighborhoods->first()->name : ''"
                        :metro="$profile->metroStations->isNotEmpty() ? 'м. ' . $profile->metroStations->pluck('name')->implode(', м. ') : ''"
                        :phone="$profile->phone"
                        :prices="[
                            'hour' => $profile->vyezd_1hour ?? 0,
                            'two_hours' => $profile->vyezd_2hours ?? 0,
                            'night' => $profile->vyezd_night ?? 0,
                        ]"
                        :img="$profile->images"
                    />
                </div>
            @empty
                <div class="col-span-full py-10 text-center">
                    <div class="text-2xl font-bold text-white mb-4">Анкеты не найдены</div>
                    <p class="text-gray-400 mb-6">К сожалению, по вашему запросу не найдено ни одной анкеты.</p>
                    <a href="{{ route('home') }}" class="px-6 py-3 bg-[#6340FF] text-white rounded-lg hover:bg-[#5030EF] transition-colors">
                        Сбросить фильтры
                    </a>
                </div>
            @endforelse
            
        </div>    

        @if($profiles->isNotEmpty())
        <!-- Show More Button (for future implementation) -->
<div class="mt-6 text-center">
<button id="show-more-btn" class="px-6 py-2 bg-[#6340FF] hover:bg-[#333333] text-white rounded-lg transition" style="">
 Показать еще
</button>
</div>

 <div class="col-span-full mt-8 flex justify-center">
     {{ $profiles->appends(request()->query())->links() }}
 </div>
@endif
        
    </div>


      <!-- Hero Section -->
    <div class="py-8 md:py-12 lg:py-16">
        <div class="max-w-screen-2xl mx-auto px-6">
            <div class="flex flex-col md:flex-col lg:flex-row items-stretch h-full">
                <!-- Text Content -->
                <div class="w-full lg:w-1/2 p-5 text-white bg-[#191919] rounded-tl-3xl lg:rounded-bl-3xl rounded-tr-3xl lg:rounded-tr-none">
                    <h1 class="text-3xl md:text-4xl font-bold mb-6">База анкет проституток, откровенные индивидуалки</h1>
                    <div class="space-y-4 text-[#FFFFFFCC]">
                        <p>Здравствуйте, дорогой гость нашего сайта для взрослых! Если вас интересуют лучшие проститутки из Челябинска, обязательно изучите представленную подборку анкет, и вы сможете подобрать идеальную шлюху для совместных развлечений. Девушки, страницы которых опубликованы в нашем масштабном каталоге, отличаются:</p>
                        
                        <ul class="list-disc pl-5 space-y-1">
                            <li>ослепительно красивой внешностью;</li>
                            <li>шикарными формами;</li>
                            <li>горячим темпераментом;</li>
                            <li>богатым опытом обслуживания мужчин;</li>
                            <li>индивидуальным подходом к каждому клиенту.</li>
                        </ul>
                        
                        <p>Кроме того, нашим женщинам характерен широкий ассортимент практикуемых услуг. Каждая индивидуалка практикует десятки видов интимных процедур, выходя за рамки традиционного секса. Сняв обученную путану, вы сможете заказать любые виды секса, в том числе:</p>
                        
                        <ul class="list-disc pl-5 space-y-1">
                            <li>классику;</li>
                            <li>анал;</li>
                            <li>минет;</li>
                            <li>лесби;</li>
                            <li>БДСМ.</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Image -->
                <div class="w-full lg:w-1/2 relative">
                    <div class="overflow-hidden shadow-xl bg-[#191919] rounded-bl-3xl lg:rounded-bl-none lg:rounded-tr-3xl rounded-br-3xl h-full">
                        <img src="{{asset('assets/images/hero.jpg')}}" alt="Hero image" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
