<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        body {
            background-color: #121212;
        }

       
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #353535;
        border-radius: 10px;
        border: 2px solid #222222;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #222222;
    }

    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #353535 #222222;
    }


        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="font-sans antialiased text-white min-h-screen overflow-x-hidden">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="">
            <div class="max-w-screen-2xl  mx-auto px-6 py-6 flex items-center justify-between">
                <a href="/" class="text-xl font-bold">Logo</a>
                <div class="flex items-center gap-4">
                    <div class="text-white">
                        <span>Баланс: </span>
                        <span class="font-medium">123 000</span>
                        <a href="#" class="text-white underline ml-2 text-sm">Пополнить</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Filter Form -->
        <div class="px-4 py-4" x-data="{
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
            services: [
                { name: 'Классика', count: 5 },
                { name: 'Бандаж', count: 1 },
                { name: 'Лесби шоу', count: 1 },
                { name: 'Проф. массаж', count: 1 },
                { name: 'Трамплинг', count: 1 },
                { name: 'Игрушки', count: 1 },
                { name: 'Золотой дождь', count: 1 },
                { name: 'Анальный секс', count: 2 },
                { name: 'Глубокий минет', count: 3 },
                { name: 'Госпожа', count: 1 },
                { name: 'Доминация', count: 2 },
                { name: 'Массаж простаты', count: 1 },
                { name: 'Окончание в рот', count: 3 },
                { name: 'Окончание на грудь', count: 2 },
                { name: 'Окончание на лицо', count: 1 },
                { name: 'Ролевые игры', count: 2 },
                { name: 'Страпон', count: 1 },
                { name: 'Фетиш', count: 2 },
                { name: 'Фистинг', count: 1 }
            ],
            metroStations: {
                'А': [
                    { name: 'Автово', count: 3 },
                    { name: 'Адмиралтейская', count: 2 },
                    { name: 'Академическая', count: 4 },
                ],
                'Б': [
                    { name: 'Балтийская', count: 2 },
                    { name: 'Беговая', count: 1 },
                    { name: 'Бухарестская', count: 3 },
                ],
                'В': [
                    { name: 'Василеостровская', count: 5 },
                    { name: 'Владимирская', count: 3 },
                    { name: 'Волковская', count: 2 },
                    { name: 'Выборгская', count: 3 },
                ],
                'Г': [
                    { name: 'Горьковская', count: 4 },
                    { name: 'Гостиный двор', count: 6 },
                    { name: 'Гражданский проспект', count: 2 },
                ],
                'Д': [
                    { name: 'Девяткино', count: 1 },
                    { name: 'Достоевская', count: 3 },
                ],
                'Е': [
                    { name: 'Елизаровская', count: 2 },
                ],
                'З': [
                    { name: 'Звёздная', count: 4 },
                    { name: 'Звенигородская', count: 3 },
                ],
                'К': [
                    { name: 'Кировский завод', count: 2 },
                    { name: 'Комендантский проспект', count: 4 },
                    { name: 'Крестовский остров', count: 3 },
                ],
                'Л': [
                    { name: 'Ладожская', count: 4 },
                    { name: 'Ленинский проспект', count: 3 },
                    { name: 'Лесная', count: 2 },
                ],
                'М': [
                    { name: 'Маяковская', count: 7 },
                    { name: 'Международная', count: 3 },
                    { name: 'Московская', count: 5 },
                ],
                'Н': [
                    { name: 'Нарвская', count: 2 },
                    { name: 'Невский проспект', count: 8 },
                ]
            },
            prices: [
                { range: 'От 2000 до 3000 руб', count: 5 },
                { range: 'От 3000 до 4000 руб', count: 8 },
                { range: 'От 5000 до 6000 руб', count: 12 },
                { range: 'От 7000 до 10000 руб', count: 7 },
                { range: 'От 10000 до 15000 руб', count: 4 },
                { range: 'От 15000 руб', count: 2 }
            ],
            ages: [
                { range: '18-21', count: 7 },
                { range: '22-25', count: 15 },
                { range: '26-30', count: 10 },
                { range: '31-35', count: 8 },
                { range: '36-40', count: 5 },
                { range: '40+', count: 3 }
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
            },
            selectMetro(station) {
                this.selectedMetro = station;
                this.showMetro = false;
            },
            selectPrice(price) {
                this.selectedPrice = price;
                this.showPrice = false;
            },
            selectAge(age) {
                this.selectedAge = age;
                this.showAge = false;
            },
            resetFilters() {
                this.selectedService = '';
                this.selectedMetro = '';
                this.selectedPrice = '';
                this.selectedAge = '';
            }
        }">
        <!-- Component HTML continues here -->
        
            <!-- Filter Bar - All Screen Sizes -->
            <div class="flex w-full gap-2 flex-wrap">
                <!-- Services Filter -->
                <div class="hidden lg:block flex-1 min-w-[210px] relative">
                    <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                         :class="showServices ? 'bg-[#6340FF]' : ''"
                         @click="toggleFilter('services')">
                        <span x-text="selectedService ? selectedService : 'Услуги'"
                              :class="selectedService ? 'text-white' : 'text-[#FFFFFF66]'"></span>
                        <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20"><path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
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
                         :class="showMetro ? 'bg-[#6340FF]' : ''"
                         @click="toggleFilter('metro')">
                        <span x-text="selectedMetro ? selectedMetro : 'Метро'"
                              :class="selectedMetro ? 'text-white' : 'text-[#FFFFFF66]'"></span>
                        <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20"><path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
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
                         :class="showPrice ? 'bg-[#6340FF]' : ''"
                         @click="toggleFilter('price')">
                        <span x-text="selectedPrice ? selectedPrice : 'Цена'"
                              :class="selectedPrice ? 'text-white' : 'text-[#FFFFFF66]'"></span>
                        <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20"><path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
                    </div>
                    
                    <!-- Price Options Dropdown -->
                    <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-full shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
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
                                    <span x-text="'(' + price.count + ')'"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            
                <!-- Age Filter -->
                <div class="hidden lg:block flex-1 min-w-[210px] relative">
                    <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                         :class="showAge ? 'bg-[#6340FF]' : ''"
                         @click="toggleFilter('age')">
                        <span x-text="selectedAge ? selectedAge : 'Возраст'"
                              :class="selectedAge ? 'text-white' : 'text-[#FFFFFF66]'"></span>
                        <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20"><path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
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
                                    <span x-text="'(' + age.count + ')'"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            
                <!-- Search Button -->
                <div class="hidden lg:block flex-1 min-w-[224px]">
                    <button class="w-full h-[50px] px-4 py-3 rounded-xl bg-[#6340FF] text-white">
                        Искать
                    </button>
                </div>
            </div>
            
            
            
            <!-- Modal Trigger for Smaller Screens -->
            <div class="w-full  mt-2 lg:hidden">
                <button @click="showModal = true"
                        class="w-full px-4 py-3 rounded-xl bg-[#6340FF] text-white">
                    Подобрать анкету
                </button>
            </div>
            
            
            
            
            <!-- Mobile Full Screen Modal -->
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
                    <!-- First row: Services select -->
                    <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                         :class="activeFilter === 'services' ? 'bg-[#6340FF]' : ''"
                         @click="activeFilter = activeFilter === 'services' ? null : 'services'">
                        <div class="flex justify-between items-center">
                            <span x-text="selectedService ? selectedService : 'Услуги'" 
                                  :class="selectedService ? 'text-white' : 'text-[#FFFFFF66]'"></span>
                            <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                                <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Services Options (if selected) -->
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
                    
                    <!-- Metro select -->
                    <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                         :class="activeFilter === 'metro' ? 'bg-[#6340FF]' : ''"
                         @click="activeFilter = activeFilter === 'metro' ? null : 'metro'">
                        <div class="flex justify-between items-center">
                            <span x-text="selectedMetro ? selectedMetro : 'Метро'" 
                                  :class="selectedMetro ? 'text-white' : 'text-[#FFFFFF66]'"></span>
                            <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                                <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Metro Options (if selected) -->
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
                    
                    <!-- Last row: Price and Age in one row -->
                    <div class="flex space-x-4">
                        <!-- Price select -->
                        <div class="w-1/2 cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                             :class="activeFilter === 'price' ? 'bg-[#6340FF]' : ''"
                             @click="activeFilter = activeFilter === 'price' ? null : 'price'">
                            <div class="flex justify-between items-center">
                                <span x-text="selectedPrice ? selectedPrice : 'Цена'" 
                                      :class="selectedPrice ? 'text-white' : 'text-[#FFFFFF66]'"></span>
                                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Age select -->
                        <div class="w-1/2 cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                             :class="activeFilter === 'age' ? 'bg-[#6340FF]' : ''"
                             @click="activeFilter = activeFilter === 'age' ? null : 'age'">
                            <div class="flex justify-between items-center">
                                <span x-text="selectedAge ? selectedAge : 'Возраст'" 
                                      :class="selectedAge ? 'text-white' : 'text-[#FFFFFF66]'"></span>
                                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price Options (if selected) -->
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
                    
                    <!-- Age Options (if selected) -->
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
                    
                    <!-- Filter Buttons -->
                    <div class="flex gap-4 mt-6">
                        <button @click="resetFilters()" 
                                class="w-1/2 px-4 py-3 rounded-xl border border-[#6340FF] text-white">
                            Сбросить
                        </button>
                        <button @click="showModal = false" 
                                class="w-1/2 px-4 py-3 rounded-xl bg-[#6340FF] text-white">
                            Применить
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-ad-card />
        
        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="pb-6">
            <div class="max-w-screen-2xl mx-auto px-6">
                <div class="flex flex-col sm:flex-row border-t border-[#363636] pt-6 justify-between items-center ">
                    <div class="mb-4 sm:mb-0">
                        <a href="/" class="text-xl font-bold">Logo</a>
                    </div>

                    <div class="flex space-x-4">
                        <a href="#"
                            class="px-4 py-2 bg-transparent text-white border border-white-700 rounded-lg text-sm">
                            Добавить анкету
                        </a>
                        <a href="#" class="px-4 py-2 bg-transparent text-white rounded-md text-sm">
                            Войти
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>

</html> 