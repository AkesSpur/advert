<x-app-layout>
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Мои рекламные кампании</h1>
        <a href="{{ route('advertisements.create') }}" class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Создать кампанию
        </a>
    </div>

    <div class="bg-[#111111] overflow-hidden rounded-xl shadow-sm mb-6">
        <!-- Active campaigns table -->
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-[#191919]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#C2C2C2] uppercase tracking-wider">
                            Название
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#C2C2C2] uppercase tracking-wider">
                            Тариф
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#C2C2C2] uppercase tracking-wider">
                            Начало
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#C2C2C2] uppercase tracking-wider">
                            Окончание
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#C2C2C2] uppercase tracking-wider">
                            Статус
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#C2C2C2] uppercase tracking-wider">
                            Действия
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1d1d1d]">
                    <!-- Campaign 1 -->
                    <tr class="bg-[#111111] hover:bg-[#151515]">
                        <td class="px-4 py-4 text-sm">
                            <div class="font-medium">Стандартная кампания</div>
                            <div class="text-[#C2C2C2] text-xs">ID: 54782</div>
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-900 text-purple-300">
                                Премиум
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-[#C2C2C2]">
                            10.10.2023
                        </td>
                        <td class="px-4 py-4 text-sm text-[#C2C2C2]">
                            10.11.2023
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                Активна
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-right space-x-2">
                            <a href="#" class="text-blue-500 hover:text-blue-400" title="Просмотр статистики">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                </svg>
                            </a>
                            <a href="#" class="text-yellow-500 hover:text-yellow-400" title="Продление">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Campaign 2 -->
                    <tr class="bg-[#111111] hover:bg-[#151515]">
                        <td class="px-4 py-4 text-sm">
                            <div class="font-medium">Профессиональная кампания</div>
                            <div class="text-[#C2C2C2] text-xs">ID: 54790</div>
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-300">
                                VIP
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-[#C2C2C2]">
                            05.09.2023
                        </td>
                        <td class="px-4 py-4 text-sm text-[#C2C2C2]">
                            05.12.2023
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                Активна
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-right space-x-2">
                            <a href="#" class="text-blue-500 hover:text-blue-400" title="Просмотр статистики">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                </svg>
                            </a>
                            <a href="#" class="text-yellow-500 hover:text-yellow-400" title="Продление">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Campaign 3 -->
                    <tr class="bg-[#111111] hover:bg-[#151515]">
                        <td class="px-4 py-4 text-sm">
                            <div class="font-medium">Временная акция</div>
                            <div class="text-[#C2C2C2] text-xs">ID: 54801</div>
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                Стандарт
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-[#C2C2C2]">
                            20.10.2023
                        </td>
                        <td class="px-4 py-4 text-sm text-[#C2C2C2]">
                            20.11.2023
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-300">
                                Ожидает оплаты
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-right space-x-2">
                            <a href="#" class="text-indigo-500 hover:text-indigo-400" title="Оплатить">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-red-500 hover:text-red-400" title="Отменить">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Campaign 4 -->
                    <tr class="bg-[#111111] hover:bg-[#151515]">
                        <td class="px-4 py-4 text-sm">
                            <div class="font-medium">Предыдущая кампания</div>
                            <div class="text-[#C2C2C2] text-xs">ID: 54756</div>
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-900 text-purple-300">
                                Премиум
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-[#C2C2C2]">
                            10.08.2023
                        </td>
                        <td class="px-4 py-4 text-sm text-[#C2C2C2]">
                            10.09.2023
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                Завершена
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-right space-x-2">
                            <a href="#" class="text-blue-500 hover:text-blue-400" title="Просмотр статистики">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                </svg>
                            </a>
                            <a href="#" class="text-[#6340FF] hover:text-[#5737e7]" title="Повторить">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Empty state (hidden in this example, but can be shown when there are no campaigns) -->
        <div class="hidden py-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium">У вас нет активных рекламных кампаний</h3>
            <p class="mt-1 text-sm text-[#C2C2C2]">Создайте свою первую рекламную кампанию, чтобы повысить видимость ваших услуг.</p>
            <div class="mt-6">
                <a href="{{ route('advertisements.create') }}" class="inline-flex items-center px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-md transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Создать кампанию
                </a>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="my-6 flex items-center justify-between">
        <div class="text-sm text-[#C2C2C2] hidden md:block">
            Показано <span class="font-medium">4</span> из <span class="font-medium">4</span> кампаний
        </div>
        <div class="flex justify-center md:justify-end space-x-1">
            <span class="px-3 py-2 inline-flex items-center text-sm bg-[#191919] text-[#C2C2C2] rounded-md cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Назад
            </span>
            <span class="px-3 py-2 inline-flex items-center text-sm bg-[#191919] text-[#C2C2C2] rounded-md cursor-not-allowed">
                Вперед
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
        </div>
    </div>

    <!-- Tips section -->
    <div class="bg-[#191919] rounded-xl p-6 mt-6">
        <h3 class="text-lg font-bold mb-4">Советы по рекламе</h3>
        <div class="space-y-4 text-[#C2C2C2]">
            <div class="flex gap-4">
                <div class="flex-shrink-0 text-[#6340FF]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-medium">Оптимизируйте описание услуг</h4>
                    <p class="text-sm">Убедитесь, что ваше описание услуг содержит ключевые слова, которые клиенты могут использовать при поиске.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 text-[#6340FF]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-medium">Добавьте качественные фотографии</h4>
                    <p class="text-sm">Профили с качественными фотографиями получают на 70% больше просмотров и запросов от клиентов.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 text-[#6340FF]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-medium">Обновляйте рекламу регулярно</h4>
                    <p class="text-sm">Периодическое обновление рекламных кампаний помогает поддерживать актуальность и повышает эффективность.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 