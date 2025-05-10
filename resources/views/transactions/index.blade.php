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
                    {{-- <th class="px-4 py-3 text-sm font-medium">Чек</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr class="border-b border-gray-800 text-[#C2C2C2]">
                    <td class="px-4 py-3 text-sm">№{{ $transaction->id }}</td>
                    <td class="px-4 py-3 text-sm">{{ $transaction->created_at->format('d.m.Y') }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($transaction->type == 'purchase')
                            @if(isset($transaction->description))
                                {{ $transaction->description }}
                            @else
                                Оплата рекламы
                            @endif
                        @else
                            Пополнение
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($transaction->status == 'completed')
                        <span class="inline-flex px-4 py-2 text-xs rounded-lg bg-[#A6FF6A99] text-black">
                            Выполнено
                        </span>
                        @elseif($transaction->status == 'pending')
                        <span class="inline-flex px-5 py-2 text-xs rounded-lg bg-[#FFE66699] text-black">
                            Ожидаем
                        </span>
                        @else
                        <span class="inline-flex px-4 py-2 text-xs rounded-lg bg-[#FF6C6C99] text-black">
                            Отклонено
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">{{ number_format($transaction->amount, 0, '.', ' ') }} р.</td>
                    {{-- <td class="px-4 py-3 text-sm">
                        @if($transaction->status == 'completed')
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </a>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        @endif
                    </td> --}}
                </tr>
                @empty
                <tr class="border-b border-gray-800">
                    <td colspan="6" class="px-4 py-6 text-center text-gray-400">У вас пока нет транзакций</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Cards for small screens -->
    <div class="md:hidden space-y-4">
        @forelse($transactions as $transaction)
        <div class="bg-[#1C1C1C] rounded-xl p-4 mb-4 text-white relative">
            <div class="text-xl font-semibold mb-4">{{ number_format($transaction->amount, 0, '.', ' ') }} р.</div>
            @if($transaction->status == 'completed')
            <span class="absolute top-4 right-4 bg-[#A6FF6A99] text-black text-sm px-3 py-2 rounded-lg">Выполнено</span>
            @elseif($transaction->status == 'pending')
            <span class="absolute top-4 right-4 bg-[#FFE66699] text-black text-sm px-3 py-2 rounded-lg">Ожидаем</span>
            @else
            <span class="absolute top-4 right-4 bg-[#FF6C6C99] text-black text-sm px-3 py-2 rounded-lg">Отклонено</span>
            @endif
            <hr class="border-gray-700 my-4">
            <div class="text-sm text-[#C2C2C2]">№{{ $transaction->id }}</div>
            <div class="text-sm text-[#C2C2C2]">{{ $transaction->created_at->format('d.m.Y') }}</div>
            <div class="text-sm text-[#C2C2C2] mb-4">
                @if($transaction->type == 'purchase')
                    @if(isset($transaction->description))
                        {{ $transaction->description }}
                    @else
                        Оплата рекламы
                    @endif
                @else
                    Пополнение
                @endif
            </div>
            {{-- @if($transaction->status == 'completed')
            <a href="#" class="block w-full bg-[#7A5FFF] hover:bg-[#6a52e6] text-white py-2 rounded-xl text-sm font-medium text-center">Получить чек</a>
            @else
            <button disabled class="w-full bg-gray-700 text-gray-400 py-2 rounded-xl text-sm font-medium cursor-not-allowed">Получить чек</button>
            @endif --}}
        </div>
        @empty
        <div class="bg-[#1C1C1C] rounded-xl p-6 text-center text-gray-400">
            У вас пока нет транзакций
        </div>
        @endforelse
    </div>

</x-app-layout>