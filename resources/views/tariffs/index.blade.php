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

    {{-- <!-- Current Subscription (if any) -->
    <div class="bg-[#191919] rounded-xl p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <h3 class="text-lg font-bold">Текущий тариф: <span class="text-[#6340FF]">Премиум</span></h3>
                    <span class="ml-3 px-2 py-1 bg-green-900 text-green-300 text-xs rounded-full">Активен</span>
                </div>
                <p class="text-[#C2C2C2] text-sm mt-1">Действует до: 22.11.2023 (осталось 20 дней)</p>
                <div class="mt-2 text-sm">
                    <span class="text-white">Лимиты:</span>
                    <span class="text-[#C2C2C2] ml-2">10 активных объявлений, приоритетное размещение</span>
                </div>
            </div>
            <div class="flex space-x-2">
                <button class="px-4 py-2 bg-[#323232] hover:bg-[#3d3d3d] text-white rounded-md transition">
                    Отменить
                </button>
                <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-md transition">
                    Продлить
                </button>
            </div>
        </div>
    </div> --}}

    <!-- Tariffs Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-6 mb-6">
        <!-- Tariff card 1 -->
        <div class="bg-[#191919] rounded-2xl overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl border-b border-[#363636] font-medium mb-6 pb-4">Название тарифа</h3>
                <p class="text-[#C2C2C2] mb-6 text-sm font-medium">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                </p>
                <div class="md:hidden flex flex-col md:flex-row justify-between items-center mt-auto gap-3 sm:gap-0">
                    <span class="w-auto text-white sm:mb-4">1000 р/месяц</span>
                    <button class="w-full md:w-fit px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                        Подключить
                    </button>
                </div>

                <div class="hidden md:flex flex justify-between items-center mt-auto">
                  <span class="text-white">1000 р/месяц</span>
                  <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                      Подключить
                  </button>
                </div>
            </div>
        </div>

        <!-- Tariff card 2 -->
        <div class="bg-[#191919] rounded-2xl overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl border-b border-[#363636] font-medium mb-6 pb-4">Название тарифа</h3>
                <p class="text-[#C2C2C2] mb-6 text-sm font-medium">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                </p>
                <div class="md:hidden flex flex-col md:flex-row justify-between items-center mt-auto gap-3 sm:gap-0">
                  <span class="w-auto text-white sm:mb-4">1000 р/месяц</span>
                  <button class="w-full md:w-fit px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                      Подключить
                  </button>
              </div>

              <div class="hidden md:flex flex justify-between items-center mt-auto">
                <span class="text-white">1000 р/месяц</span>
                <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                    Подключить
                </button>
              </div>
            </div>
        </div>

        <!-- Tariff card 3 -->
        <div class="bg-[#191919] rounded-2xl overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl border-b border-[#363636] font-medium mb-6 pb-4">Название тарифа</h3>
                <p class="text-[#C2C2C2] mb-6 text-sm font-medium">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                </p>
                <div class="md:hidden flex flex-col md:flex-row justify-between items-center mt-auto gap-3 sm:gap-0">
                  <span class="w-auto text-white sm:mb-4">1000 р/месяц</span>
                  <button class="w-full md:w-fit px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                      Подключить
                  </button>
              </div>

              <div class="hidden md:flex flex justify-between items-center mt-auto">
                <span class="text-white">1000 р/месяц</span>
                <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                    Подключить
                </button>
              </div>
            </div>
        </div>

        <!-- Tariff card 4 -->
        <div class="bg-[#191919] rounded-2xl overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl border-b border-[#363636] font-medium mb-6 pb-4">Название тарифа</h3>
                <p class="text-[#C2C2C2] mb-6 text-sm font-medium">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                </p>
                <div class="md:hidden flex flex-col md:flex-row justify-between items-center mt-auto gap-3 sm:gap-0">
                  <span class="w-auto text-white sm:mb-4">1000 р/месяц</span>
                  <button class="w-full md:w-fit px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                      Подключить
                  </button>
              </div>

              <div class="hidden md:flex flex justify-between items-center mt-auto">
                <span class="text-white">1000 р/месяц</span>
                <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                    Подключить
                </button>
              </div>
            </div>
        </div>

        <!-- Tariff card 5 -->
        <div class="bg-[#191919] rounded-2xl overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl border-b border-[#363636] font-medium mb-6 pb-4">Название тарифа</h3>
                <p class="text-[#C2C2C2] mb-6 text-sm font-medium">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                </p>
                <div class="md:hidden flex flex-col md:flex-row justify-between items-center mt-auto gap-3 sm:gap-0">
                  <span class="w-auto text-white sm:mb-4">1000 р/месяц</span>
                  <button class="w-full md:w-fit px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                      Подключить
                  </button>
              </div>

              <div class="hidden md:flex flex justify-between items-center mt-auto">
                <span class="text-white">1000 р/месяц</span>
                <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                    Подключить
                </button>
              </div>
            </div>
        </div>

        <!-- Tariff card 6 -->
        <div class="bg-[#191919] rounded-2xl overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl border-b border-[#363636] font-medium mb-6 pb-4">Название тарифа</h3>
                <p class="text-[#C2C2C2] mb-6 text-sm font-medium">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                </p>
                <div class="md:hidden flex flex-col md:flex-row justify-between items-center mt-auto gap-3 sm:gap-0">
                  <span class="w-auto text-white sm:mb-4">1000 р/месяц</span>
                  <button class="w-full md:w-fit px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                      Подключить
                  </button>
              </div>

              <div class="hidden md:flex flex justify-between items-center mt-auto">
                <span class="text-white">1000 р/месяц</span>
                <button class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-2xl transition">
                    Подключить
                </button>
              </div>
            </div>
        </div>
    </div>

</x-app-layout> 