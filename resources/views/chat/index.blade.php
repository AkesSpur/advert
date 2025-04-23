<x-app-layout>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Сообщения</h1>
    </div>
    <div class="pb-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-gray-900 dark:text-gray-100">
                <div class="w-full flex flex-col md:flex-row gap-4">
                    <!-- Main Chat Area -->
                    <div class="w-full bg-[#191919]  border border-[#2B2B2B] md:w-3/4 rounded-lg flex flex-col">
                        <!-- Chat header -->
                        <div class="p-4 border-b border-[#2B2B2B] dark:border-gray-700">
                            <h3 class="font-semibold">Администрация</h3>
                        </div>

                        <!-- Chat messages -->
                        <div class="flex-1 p-4 overflow-y-auto space-y-4 pr-2 max-h-[51vh] hide-scrollbar">
                            <!-- Admin message -->
                            <div class="flex items-start gap-2">
                                <div class="max-w-[75%] bg-[#272727] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Добро пожаловать в чат поддержки! Чем мы можем вам помочь
                                        сегодня?</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:30</div>
                                </div>
                            </div>

                            <!-- User message -->
                            <div class="flex items-start gap-2 justify-end">
                                <div
                                    class="max-w-[75%] bg-[#6340FF] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Здравствуйте! У меня возникли вопросы по поводу регистрации
                                        профиля арендодателя.</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:32</div>
                                </div>
                            </div>

                            <!-- Admin message -->
                            <div class="flex items-start gap-2">
                                <div class="max-w-[75%] bg-[#272727] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Конечно, я буду рад помочь вам с этим. Какие именно у вас
                                        возникли вопросы по регистрации?</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:33</div>
                                </div>
                            </div>

                            <!-- User message -->
                            <div class="flex items-start gap-2 justify-end">
                                <div
                                    class="max-w-[75%] bg-[#6340FF] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Здравствуйте! У меня возникли вопросы по поводу регистрации
                                        профиля арендодателя.</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:32</div>
                                </div>
                            </div>

                            <!-- Admin message -->
                            <div class="flex items-start gap-2">
                                <div class="max-w-[75%] bg-[#272727] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Конечно, я буду рад помочь вам с этим. Какие именно у вас
                                        возникли вопросы по регистрации?</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:33</div>
                                </div>
                            </div>


                            <!-- User message -->
                            <div class="flex items-start gap-2 justify-end">
                                <div
                                    class="max-w-[75%] bg-[#6340FF] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Здравствуйте! У меня возникли вопросы по поводу регистрации
                                        профиля арендодателя.</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:32</div>
                                </div>
                            </div>

                            <!-- Admin message -->
                            <div class="flex items-start gap-2">
                                <div class="max-w-[75%] bg-[#272727] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Конечно, я буду рад помочь вам с этим. Какие именно у вас
                                        возникли вопросы по регистрации?</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:33</div>
                                </div>
                            </div>


                            <!-- User message -->
                            <div class="flex items-start gap-2 justify-end">
                                <div
                                    class="max-w-[75%] bg-[#6340FF] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Здравствуйте! У меня возникли вопросы по поводу регистрации
                                        профиля арендодателя.</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:32</div>
                                </div>
                            </div>

                            <!-- Admin message -->
                            <div class="flex items-start gap-2">
                                <div class="max-w-[75%] bg-[#272727] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Конечно, я буду рад помочь вам с этим. Какие именно у вас
                                        возникли вопросы по регистрации?</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:33</div>
                                </div>
                            </div>

                            <!-- User message -->
                            <div class="flex items-start gap-2 justify-end">
                                <div
                                    class="max-w-[75%] bg-[#6340FF] p-3 rounded-lg rounded-lg">
                                    <p class="text-sm">Я пытаюсь загрузить фотографии моего объекта, но система выдает
                                        ошибку о превышении размера файла. Какие ограничения на размер фотографий?</p>
                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">10:35</div>
                                </div>
                            </div>
                        </div>

                        <!-- Message input -->
                        <div class="p-4 border-t border-[#2B2B2B] dark:border-gray-700">
                            <div class="flex items-end gap-2">
                                {{-- <button
                                    class="p-2 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                    </svg>
                                </button> --}}
                                 <!-- Message input -->
                                 <input
                                   rows="1"
                                   class="flex-1 text-white bg-[#191919] p-2 rounded-lg border-none resize-none focus:outline-none focus:ring-0 placeholder-gray-500"
                                   placeholder="Введите ваше сообщение..."
                                 ></input>
                               
                                 <!-- Text button, visible md+ -->
                                 <button
                                   type="button"
                                   class="hidden md:inline-flex hover:bg-[#6340FF] items-center bg-[#2B2B2B] text-white px-10 py-2 rounded-lg transition"
                                 >
                                 Отправить
                                 </button>
                               
                                <button class="p-2 inline-flex md:hidden bg-[#2B2B2B] text-white rounded-lg hover:bg-[#6340FF]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
