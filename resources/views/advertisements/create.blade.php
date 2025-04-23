<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Выберите тариф рекламы</h1>
    </div>

    <div class="pb-6">
        <!-- Active subscription note -->
        <div class="mb-6 p-4 bg-[#191919] rounded-xl flex items-start gap-4">
            <div class="flex-shrink-0 text-yellow-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-medium">У вас активна подписка "Стандарт"</p>
                <p class="text-[#C2C2C2] text-sm">Срок действия: до 10.11.2023 (осталось 15 дней)</p>
            </div>
        </div>

        <!-- Tariff cards grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Standard Tariff -->
            <div class="bg-[#191919] rounded-2xl p-6 border border-gray-700 hover:border-[#6340FF] transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold mb-1">Стандарт</h3>
                        <p class="text-[#C2C2C2]">Базовая реклама</p>
                    </div>
                    <span class="bg-green-900 text-green-300 text-xs px-2 py-1 rounded-full">Популярный</span>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6340FF]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Размещение в каталоге</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6340FF]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Стандартный показ в результатах</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span>Выделение записи</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span>Приоритетная поддержка</span>
                    </div>
                </div>
                
                <div class="flex items-baseline justify-between mt-4">
                    <div>
                        <span class="text-2xl font-bold">1000 ₽</span>
                        <span class="text-[#C2C2C2] text-sm">/месяц</span>
                    </div>
                    <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-md transition">
                        Подключить
                    </button>
                </div>
            </div>
            
            <!-- Premium Tariff -->
            <div class="bg-[#191919] rounded-2xl p-6 border border-[#6340FF] shadow-[0_0_20px_rgba(99,64,255,0.2)]">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold mb-1">Премиум</h3>
                        <p class="text-[#C2C2C2]">Расширенная реклама</p>
                    </div>
                    <span class="bg-purple-900 text-purple-300 text-xs px-2 py-1 rounded-full">Рекомендуем</span>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6340FF]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Размещение в каталоге</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6340FF]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Приоритетный показ в поиске</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6340FF]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Выделение записи</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span>Приоритетная поддержка</span>
                    </div>
                </div>
                
                <div class="flex items-baseline justify-between mt-4">
                    <div>
                        <span class="text-2xl font-bold">2500 ₽</span>
                        <span class="text-[#C2C2C2] text-sm">/месяц</span>
                    </div>
                    <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-md transition">
                        Подключить
                    </button>
                </div>
            </div>
            
            <!-- VIP Tariff -->
            <div class="bg-[#191919] rounded-2xl p-6 border border-gray-700 hover:border-[#6340FF] transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold mb-1">VIP</h3>
                        <p class="text-[#C2C2C2]">Максимальная видимость</p>
                    </div>
                    <span class="bg-blue-900 text-blue-300 text-xs px-2 py-1 rounded-full">Лучшее</span>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6340FF]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Размещение в каталоге</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6340FF]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>ТОП показ в результатах</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6340FF]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Выделение записи</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6340FF]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Приоритетная поддержка 24/7</span>
                    </div>
                </div>
                
                <div class="flex items-baseline justify-between mt-4">
                    <div>
                        <span class="text-2xl font-bold">5000 ₽</span>
                        <span class="text-[#C2C2C2] text-sm">/месяц</span>
                    </div>
                    <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-md transition">
                        Подключить
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Additional info -->
        <div class="mt-8 p-6 bg-[#191919] rounded-xl">
            <h3 class="text-lg font-bold mb-4">О рекламе на площадке</h3>
            <div class="space-y-4 text-[#C2C2C2]">
                <p>Реклама на нашей платформе - это эффективный способ продвижения ваших услуг и привлечения новых клиентов. Мы предлагаем различные тарифы, чтобы каждый мог выбрать оптимальный вариант для своего бюджета и целей.</p>
                <p>При подключении рекламы, ваш профиль будет показываться в соответствующих разделах и выделяться среди других. Чем выше тариф, тем больше преимуществ вы получаете.</p>
                <p>По всем вопросам о рекламе вы можете обратиться в <a href="#" class="text-[#6340FF] hover:underline">поддержку</a>.</p>
            </div>
        </div>
    </div>
</x-app-layout> 