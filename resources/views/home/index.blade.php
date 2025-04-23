@extends('second-layout')

@section('content')
    
    <div class="py-6">
        <h2 class="text-4xl max-w-screen-2xl px-6 mx-auto py-4 mb-2 text-white text-center">Escort услуги в Санкт-Петербурге</h2>
        
        <div class="max-w-screen-2xl px-6 mx-auto">
            <!-- Filter and Sort Container - Flex on lg, Column on md/sm -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                <!-- Filter Buttons -->
                <div class="flex overflow-x-auto hide-scrollbar justify-start gap-3 mb-4 lg:mb-0">
                    <a href="#" class="px-4 py-2 bg-[#6340FF] shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors">Все анкеты</a>
                    <a href="#" class="px-4 py-2 bg-[#191919] shrink-0 text-white rounded-lg border border-[#8B8B8B] hover:bg-[#252525] transition-colors">Есть видео</a>
                    <a href="#" class="px-4 py-2 bg-[#191919] shrink-0 text-white rounded-lg border border-[#8B8B8B] hover:bg-[#252525] transition-colors">Новые</a>
                    <a href="#" class="px-4 py-2 bg-[#191919] shrink-0 text-white rounded-lg border border-[#8B8B8B] hover:bg-[#252525] transition-colors">VIP</a>
                    <a href="#" class="px-4 py-2 bg-[#191919] shrink-0 text-white rounded-lg border border-[#8B8B8B] hover:bg-[#252525] transition-colors">Дешевые</a>
                    <a href="#" class="px-4 py-2 bg-[#191919] shrink-0 text-white rounded-lg border border-[#8B8B8B] hover:bg-[#252525] transition-colors">Фото проверены</a>
                </div>
                
                <!-- Sort Dropdown -->
                <div class="relative" x-data="{ showSortOptions: false, selectedSort: 'Сортировать по умолчанию' }">
                    <button @click="showSortOptions = !showSortOptions" 
                            class="flex items-center  w-full px-4 py-2 text-white rounded-lg hover:bg-[#252525] transition-colors">
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
                            <a href="#" @click.prevent="selectedSort = 'Сортировать по умолчанию'; showSortOptions = false" class="block px-4 py-2 text-white hover:bg-[#252525]">Сортировать по умолчанию</a>
                            <a href="#" @click.prevent="selectedSort = 'Дешевые'; showSortOptions = false" class="block px-4 py-2 text-white hover:bg-[#252525]">Дешевые</a>
                            <a href="#" @click.prevent="selectedSort = 'Дорогие'; showSortOptions = false" class="block px-4 py-2 text-white hover:bg-[#252525]">Дорогие</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 px-6 max-w-screen-2xl mx-auto">
            @php
                $names = ['Алина', 'Виктория', 'Екатерина', 'Марина', 'Наташа', 'Ольга', 'София', 'Юлия', 'Анжела', 'Диана'];
                $ages = [20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32];
                $weights = ['50 кг', '52 кг', '54 кг', '56 кг', '58 кг', '60 кг', '62 кг', '64 кг', '66 кг', '68 кг'];
                $heights = ['160 см', '165 см', '168 см', '170 см', '172 см', '175 см', '178 см', '180 см'];
                $sizes = ['1 размер', '2 размер', '3 размер', '4 размер', '5 размер'];
                $metros = [
                    'м. Автово', 'м. Адмиралтейская, м. Автово', 'м. Академическая, Василеостровская', 'м. Балтийская', 
                    'м. Василеостровская', 'м. Владимирская', 'м. Выборгская', 'м. Горьковская', 
                    'м. Достоевска, м. Автово', 'м. Звездная', 'м. Ладожская', 'м. Маяковская', 'м. Невский проспект'
                ];
                $districts = [
                    'р. Адмиралтейский', 'р. Василеостровский', 'р. Выборгский', 'р. Калининский', 
                    'р. Кировский', 'р. Московский', 'р. Невский', 'р. Петроградский', 'р. Приморский', 'р. Центральный'
                ];
                $prices = [
                    [
                        'hour' => '3000 руб.',
                        'two_hours' => '6000 руб.',
                        'night' => '12000 руб.'
                    ],
                    [
                        'hour' => '4000 руб.',
                        'two_hours' => '8000 руб.',
                        'night' => '15000 руб.'
                    ],
                    [
                        'hour' => '5000 руб.',
                        'two_hours' => '10000 руб.',
                        'night' => '20000 руб.'
                    ],
                    [
                        'hour' => '6000 руб.',
                        'two_hours' => '12000 руб.',
                        'night' => '25000 руб.'
                    ],
                    [
                        'hour' => '7000 руб.',
                        'two_hours' => '14000 руб.',
                        'night' => '30000 руб.'
                    ]
                ];
                $phones = [
                    '+7 (931) 123-45-67',
                    '+7 (921) 234-56-78',
                    '+7 (911) 345-67-89',
                    '+7 (981) 456-78-90',
                    '+7 (904) 567-89-01',
                    '+7 (905) 678-90-12',
                    '+7 (906) 789-01-23',
                    '+7 (907) 890-12-34'
                ];
            @endphp
            
            @for ($i = 0; $i < 20; $i++)
                <div class="h-full">
                    <x-ad-card
                        :verified="rand(0, 1)"
                        :name="$names[array_rand($names)]"
                        :age="$ages[array_rand($ages)]"
                        :weight="$weights[array_rand($weights)]"
                        :height="$heights[array_rand($heights)]"
                        :size="$sizes[array_rand($sizes)]"
                        :metro="$metros[array_rand($metros)]"
                        :district="$districts[array_rand($districts)]"
                        :phone="$phones[array_rand($phones)]"
                        :prices="$prices[array_rand($prices)]"
                    />
                </div>
            @endfor
        </div>
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
