@extends('second-layout')

@section('content')
    <div class="p-6 space-y-8 mx-auto text-white max-w-screen-2xl">
        <form method="POST" action="{{ route('user.profiles.store') }}" enctype="multipart/form-data" class="space-y-8"
            id="profileForm">
            @csrf

            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Основная информация --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">Основная информация</h2>
                <div class="grid grid-cols-1 lg:grid-cols-6 gap-4">
                    <input type="text" name="name" placeholder="Введите имя" required
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0 lg:col-span-3"
                        value="{{ old('name') }}">

                    <div class="flex items-center gap-4 lg:col-span-3">
                        <label class="flex items-center gap-2 text-[#FFFFFFCC]">
                            <input type="radio" name="profile_type" value="individual" checked
                                class="w-6 h-6 bg-transparent text-[#6340FF] accent-[#6340FF]">
                            Индивидуалка
                        </label>
                        <label class="flex items-center gap-2 text-[#FFFFFFCC]">
                            <input type="radio" name="profile_type" value="salon"
                                class="w-6 h-6 text-[#6340FF] bg-transparent accent-[#6340FF]">
                            Интим-салон
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-2 py-4">
                    <input type="number" name="age" placeholder="Возраст/лет" min="18"
                        class="block w-full px-4 py-3 rounded-xl border-0 text-white bg-[#191919] placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('age') }}" required>
                    <input type="number" name="weight" placeholder="Вес/кг" min="30"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919]  border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('weight') }}" required>
                    <input type="number" name="height" placeholder="Рост/см" min="100"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919]  border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('height') }}" required>
                    <input type="text" name="size" placeholder="Грудь/размер"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919]  border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('size') }}" required>
                    <input type="text" name="tattoo" placeholder="Тату/есть или нет"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919]  border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('tattoo') }}" required>
                </div>

            </div>

            {{-- Описание/О себе --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">О себе</h2>
                <textarea name="description" placeholder="Расскажите о себе..." rows="4" required
                    class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0">{{ old('description') }}</textarea>
            </div>

            {{-- Цвет волос --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">Цвет волос</h2>
                <div class="flex flex-wrap gap-6">
                    @foreach (['Русая', 'Рыжая', 'Шатенка', 'Брюнетка', 'Блондинка'] as $color)
                        <label class="flex items-center gap-2 text-[#FFFFFFCC]">
                            <input type="radio" name="hair_color" value="{{ $color }}"
                                class="w-6 p-1 h-6 rounded-full border text-[#6340FF] bg-transparent"
                                {{ old('hair_color') == $color ? 'checked' : '' }} required>
                            {{ $color }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Контакты --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">Контакты</h2>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="has_telegram" value="0">
                        <input type="checkbox" name="has_telegram" id="has_telegram" value="1"
                            class="w-6 h-6 hidden md:block text-[#6340FF] bg-transparent focus:ring-[#6340FF]"
                            {{ old('has_telegram') ? 'checked' : '' }}>
                        <span class="w-24 hidden md:block text-[#FFFFFFCC]">Телеграм</span>
                        <input name="telegram" id="telegram"
                            class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                            type="text" placeholder="Введите свой ник" value="{{ old('telegram') }}">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="has_viber" value="0">
                        <input type="checkbox" name="has_viber" id="has_viber" value="1"
                            class="w-6 h-6 hidden md:block text-[#6340FF] bg-transparent focus:ring-[#6340FF]"
                            {{ old('has_viber') ? 'checked' : '' }}>
                        <span class="w-24 hidden md:block text-[#FFFFFFCC]">Viber</span>
                        <input name="viber" id="viber"
                            class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                            type="text" placeholder="Введите свой ник" value="{{ old('viber') }}">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="has_whatsapp" value="0">
                        <input type="checkbox" name="has_whatsapp" id="has_whatsapp" value="1"
                            class="w-6 h-6 hidden md:block text-[#6340FF] bg-transparent focus:ring-[#6340FF]"
                            {{ old('has_whatsapp') ? 'checked' : '' }}>
                        <span class="w-24 hidden md:block text-[#FFFFFFCC]">WhatsApp</span>
                        <input name="whatsapp" id="whatsapp" 
                            class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                            type="text" placeholder="Введите свой ник" value="{{ old('whatsapp') }}">
                    </div>
                    <input name="phone" required
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        type="text" placeholder="+7 (__) ___-__-__" value="{{ old('phone') }}">
                    <input name="email"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        type="email" placeholder="Email" value="{{ old('email') }}">
                </div>
            </div>

            {{-- Фото и видео --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">Фото и видео</h2>
                <p class="text-sm text-[#FFFFFF99] mb-2">* Требуется загрузить хотя бы одно фото</p>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    
                    <div class="video-upload-container relative">
                        <label
                            class="cursor-pointer aspect-[64/100] sm:aspect-[64/100] md:aspect-[81/100] lg:aspect-[64/100] bg-neutral-900 rounded-xl flex items-center justify-center text-center text-sm text-[#FFFFFF66] hover:text-white hover:bg-neutral-800 transition video-label">
                            <div class="video-placeholder">
                                <div class="text-3xl mb-1">+</div>
                                Добавить видео
                            </div>
                            <div class="video-preview hidden relative">
                                <video class="w-full h-full object-cover rounded-xl" controls></video>
                                <button type="button"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center remove-video">×</button>
                            </div>
                            <input type="file" name="video" accept="video/*" class="hidden video-input">
                        </label>
                    </div>

                    {{-- Добавляем возможность загрузки неограниченного количества фото --}}
                    @for ($i = 0; $i < 10; $i++)
                        <div class="photo-upload-container relative">
                            <label
                                class="cursor-pointer aspect-[64/100] sm:aspect-[64/100] md:aspect-[81/100] lg:aspect-[64/100] bg-neutral-900 rounded-xl flex items-center justify-center text-center text-sm text-[#FFFFFF66] hover:text-white hover:bg-neutral-800 transition photo-label">
                                <div class="photo-placeholder">
                                    <div class="text-3xl mb-1">+</div>
                                    Добавить фото
                                </div>
                                <div class="photo-preview hidden relative">
                                    <img src="#" alt="Preview" class="w-full h-full object-cover rounded-xl">
                                    <button type="button"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center remove-photo">×</button>
                                </div>
                                <input type="file" name="photos[]" accept="image/*" multiple class="hidden photo-input">
                            </label>
                        </div>
                    @endfor

                </div>
            </div>


            {{-- Локация --}}
            <div x-data="{
                showDistricts: false,
                showStations: false,
                selectedDistrictNames: {{ old('neighborhoods') ? json_encode($neighborhoods->whereIn('id', old('neighborhoods'))->pluck('name')) : '[]' }},
                selectedStationNames: {{ old('metro_stations') ? json_encode($metroStations->whereIn('id', old('metro_stations'))->pluck('name')) : '[]' }},
                allDistricts: {{ Js::from($neighborhoods->pluck('name', 'id')) }},
                allStations: {{ Js::from($metroStations->pluck('name', 'id')) }}
            }">
                <h2 class="text-xl font-semibold mb-4">Локация</h2>

                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Districts select -->
                    <div class="input bg-[#191919] w-full cursor-pointer px-4 py-3 rounded-xl"
                        :class="showDistricts ? 'bg-[#6340FF]' : ''"
                        @click="showDistricts = !showDistricts; showStations = false">
                        <template x-if="selectedDistrictNames.length">
                            <span x-text="selectedDistrictNames.join(', ')"></span>
                        </template>
                        <template x-if="!selectedDistrictNames.length">
                            <span class="text-[#FFFFFF66]">Выберите район</span>
                        </template>
                    </div>

                    <!-- Stations select -->
                    <div class="input bg-[#191919] w-full cursor-pointer px-4 py-3 rounded-xl"
                        :class="showStations ? 'bg-[#6340FF]' : ''"
                        @click="showStations = !showStations; showDistricts = false">
                        <template x-if="selectedStationNames.length">
                            <span x-text="selectedStationNames.join(', ')"></span>
                        </template>
                        <template x-if="!selectedStationNames.length">
                            <span class="text-[#FFFFFF66]">Выберите станцию метро</span>
                        </template>
                    </div>
                </div>

                <!-- District options -->
                <div class="bg-neutral-900 p-6 rounded-xl mt-6" x-show="showDistricts" x-transition>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-y-4">
                        <template x-for="(district, id) in allDistricts" :key="id">
                            <label class="flex items-center gap-2 text-white">
                                <input type="checkbox" name="neighborhoods[]"
                                    class="bg-transparent w-4 h-4 text-[#6340FF] focus:ring-[#6340FF]"
                                    :value="id"
                                    @change="event.target.checked 
                                    ? selectedDistrictNames.push(district) 
                                    : selectedDistrictNames = selectedDistrictNames.filter(d => d !== district)"
                                    :checked="selectedDistrictNames.includes(district)">
                                <span x-text="district" class="text-[#FFFFFFCC]"></span>
                            </label>
                        </template>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="button" @click="showDistricts = false"
                            class="bg-[#6340FF] hover:bg-[#5737e7] px-6 py-2 rounded-lg text-white">
                            Применить
                        </button>
                    </div>
                </div>

                <!-- Metro station options -->
                <div class="bg-neutral-900 p-6 rounded-xl mt-6" x-show="showStations" x-transition>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-y-4">
                        <template x-for="(station, id) in allStations" :key="id">
                            <label class="flex items-center gap-2 text-white">
                                <input type="checkbox" name="metro_stations[]"
                                    class="bg-transparent w-4 h-4 text-[#6340FF] focus:ring-[#6340FF]"
                                    :value="id"
                                    @change="event.target.checked 
                                    ? selectedStationNames.push(station) 
                                    : selectedStationNames = selectedStationNames.filter(s => s !== station)"
                                    :checked="selectedStationNames.includes(station)">
                                <span x-text="station" class="text-[#FFFFFFCC]"></span>
                            </label>
                        </template>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="button" @click="showStations = false"
                            class="bg-[#6340FF] hover:bg-[#5737e7] px-6 py-2 rounded-lg text-white">
                            Применить
                        </button>
                    </div>
                </div>
            </div>
            
   

            {{-- price --}}
            <div x-data="{
                rentalOptions: ['Выезд', 'Апартаменты'],
                selectedRentals: [
                    {{ old('vyezd') ? 'true' : 'false' }},
                    {{ old('appartamenti') ? 'true' : 'false' }}
                ]
            }" class="p-6 rounded-xl mt-6">
                <h3 class="text-lg font-semibold mb-4 text-white">Стоимость</h3>

                <div class="space-y-6">
                    <template x-for="(item, index) in rentalOptions" :key="index">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <!-- Checkbox and label -->
                            <label class="flex items-center gap-2 text-[#FFFFFFCC] min-w-[150px]">
                                <input type="checkbox" 
                                    :name="index === 0 ? 'vyezd' : 'appartamenti'" 
                                    value="1"
                                    class="pricing-checkbox w-4 h-4 bg-transparent text-[#6340FF] focus:ring-0"
                                    :checked="selectedRentals[index]"
                                    @change="selectedRentals[index] = $event.target.checked">
                                <span x-text="item"></span>
                            </label>

                            <!-- Price inputs -->
                            <div class="flex flex-col sm:flex-row gap-2 flex-1">
                                <input type="number" 
                                    :name="index === 0 ? 'vyezd_1hour' : 'appartamenti_1hour'" 
                                    placeholder="Цена за 1 час"
                                    class="pricing-input w-full bg-[#191919] focus:ring-0 border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                    :value="index === 0 ? `{{ old('vyezd_1hour') }}` : `{{ old('appartamenti_1hour') }}`"
                                    min="0"
                                    :data-index="index" />
                                <input type="number" 
                                    :name="index === 0 ? 'vyezd_2hours' : 'appartamenti_2hours'" 
                                    placeholder="Цена за 2 часа"
                                    class="pricing-input w-full bg-[#191919] border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                    :value="index === 0 ? `{{ old('vyezd_2hours') }}` : `{{ old('appartamenti_2hours') }}`"
                                    min="0"
                                    :data-index="index" />
                                <input type="number" 
                                    :name="index === 0 ? 'vyezd_night' : 'appartamenti_night'" 
                                    placeholder="Цена за 3 часа" 
                                    class="pricing-input w-full bg-[#191919] border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                    :value="index === 0 ? `{{ old('vyezd_night') }}` : `{{ old('appartamenti_night') }}`"
                                    min="0"
                                    :data-index="index" />
                            </div>
                            </template>
                        </div>

                        </div>


                        <!-- services -->
                        <div x-data="{
                            selectedWithPrice: [],
                            togglePrice(option) {
                                if (!this.selectedWithPrice.includes(option)) {
                                    this.selectedWithPrice.push(option)
                                } else {
                                    this.selectedWithPrice = this.selectedWithPrice.filter(i => i !== option)
                                }
                            },
                            init() {
                                // Инициализация выбранных услуг с ценами при загрузке страницы
                                const paidServices = {{ Js::from($paidServices) }};
                                const oldPaidServices = {{ old('paid_services') ? json_encode(old('paid_services')) : '[]' }};
                                
                                if (oldPaidServices.length > 0) {
                                    paidServices.forEach(service => {
                                        if (oldPaidServices.includes(service.id.toString())) {
                                            this.selectedWithPrice.push(service);
                                        }
                                    });
                                }
                            }
                        }" x-init="init()">

                            <div class="p-6 rounded-xl mt-6">
                                <h3 class="text-lg font-semibold mb-4 text-white">Услуги</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-4">
                                    <template x-for="(item, id) item in {{ Js::from($services) }}">
                                        <label class="flex items-center gap-2 text-[#FFFFFFCC]">
                                            <input type="checkbox" name="services[]" :value="item.id"
                                                class="w-4 h-4 bg-transparent text-[#6340FF] focus:ring-[#6340FF] service-checkbox">
                                            <span x-text="item.name"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <div class="p-6 rounded-xl mt-6">
                                <h3 class="text-lg font-semibold mb-4 text-white">Услуги за доп. плату <span class="text-red-500">*</span></h3>
                                <p class="text-sm text-[#FFFFFF99] mb-2">* Требуется выбрать хотя бы одну услугу</p>
                                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-y-6">
                                    <template x-for="(item, id) in {{ Js::from($paidServices) }}">
                                        <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between mr-3">
                                            <label class="flex items-center gap-2 text-[#FFFFFFCC]">
                                                <input type="checkbox" name="paid_services[]" :value="item.id"
                                                    @change="togglePrice(item)"
                                                    class="w-4 h-4 text-[#6340FF] focus:ring-[#6340FF] bg-transparent">
                                                <span x-text="item.name"></span>
                                            </label>
                                            <template x-if="selectedWithPrice.includes(item)">
                                                <input type="number" :name="`paid_service_prices[${item.id}]`" 
                                                    placeholder="+ К цена/руб"
                                                    class="input mt-2 md:mt-0 md:ml-4 w-full bg-[#191919] border-0 focus:ring-0 md:w-40 text-white px-3 py-2 rounded-lg">
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                            <div class="mt-8 flex justify-center md:mt-12">
                                <button type="submit"
                                    class="w-full md:w-[300px] h-12 bg-[#6340FF] text-white rounded-[12px] px-[10px] py-[10px] text-center text-sm font-semibold hover:bg-[#5032cc] transition">
                                    Опубликовать анкету
                                </button>
                            </div>
        </form>


    </div>

    {{-- Include form validation script --}}
    <script src="{{ asset('js/form-validation.js') }}"></script>
    @endsection
