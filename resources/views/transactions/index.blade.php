<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Транзакции</h1>
    </div>

    <div class="hidden md:block overflow-x-auto">
        <table class="w-full border-collapse">
            <thead class="bg-[#191919] text-left">
                <tr>
                    <th class="px-4 py-3 text-sm font-medium">№</th>
                    <th class="px-4 py-3 text-sm font-medium">Дата</th>
                    <th class="px-4 py-3 text-sm font-medium">Услуга</th>
                    <th class="px-4 py-3 text-sm font-medium">Статус</th>
                    <th class="px-4 py-3 text-sm font-medium">Стоимость</th>
                    <th class="px-4 py-3 text-sm font-medium">Чек</th>
                </tr>
            </thead>
            <tbody>
                <!-- Transaction 1 -->
                <tr class="border-b border-gray-800 text-[#C2C2C2]">
                    <td class="px-4 py-3 text-sm">№328</td>
                    <td class="px-4 py-3 text-sm">18.02.2025</td>
                    <td class="px-4 py-3 text-sm">Оплата рекламы</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-flex px-4 py-2 text-xs rounded-lg bg-[#A6FF6A99] text-black">
                            Выполнено
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">2000 р.</td>
                    <td class="px-4 py-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </td>
                </tr>
                
                <!-- Transaction 2 -->
                <tr class="border-b border-gray-800 text-[#C2C2C2]">
                    <td class="px-4 py-3 text-sm">№327</td>
                    <td class="px-4 py-3 text-sm">17.02.2025</td>
                    <td class="px-4 py-3 text-sm">Оплата рекламы</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-flex px-5 py-2 text-xs rounded-lg bg-[#FFE66699] text-black">
                            Ожидаем
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">2000 р.</td>
                    <td class="px-4 py-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </td>
                </tr>
                
                <!-- Transaction 3 -->
                <tr class="border-b border-gray-800 text-[#C2C2C2]">
                    <td class="px-4 py-3 text-sm">№326</td>
                    <td class="px-4 py-3 text-sm">16.02.2025</td>
                    <td class="px-4 py-3 text-sm">Оплата рекламы</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-flex px-4 py-2 text-xs rounded-lg bg-[#FF6C6C99] text-black">
                            Отклонено
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">2000 р.</td>
                    <td class="px-4 py-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Card for small screens -->
<div class="md:hidden bg-[#1C1C1C] rounded-xl p-4 mb-4 text-white relative">
   <div class="text-xl font-semibold mb-4">2000 р.</div>
   <span class="absolute top-4 right-4 bg-[#FF6C6C99] text-black text-sm px-3 py-2 rounded-lg">Отклонено</span>
   <hr class="border-gray-700 my-4">
   <div class="text-sm text-[#C2C2C2]">№326</div>
   <div class="text-sm text-[#C2C2C2]">16.02.2025</div>
   <div class="text-sm text-[#C2C2C2] mb-4">Оплата рекламы</div>
   <button class="w-full bg-[#7A5FFF] hover:bg-[#6a52e6] text-white py-2 rounded-xl text-sm font-medium">Получить чек</button>
</div>

</x-app-layout> 