@extends('second-layout')

@section('content')
    <div class="p-6 space-y-8 mx-auto text-white max-w-screen-2xl">

        <div class="text-sm text-gray-400 mb-4 pl-4 flex justify-between">
            <nav class="text-sm text-[#A0A0A0] mb-4" aria-label="Breadcrumb">
                <ol class="list-reset flex items-center space-x-1">
                    <li>
                        <a href="{{route('user.profiles.index')}}" class="hover:text-[#A0A0A0] text-[#636363]">Главная</a>
                    </li>
                    <li><span>/</span></li>
                    <li class="text-[#6340FF] font-medium" aria-current="page">Добавить анкету</li>
                </ol>
            </nav>

        </div>

        <form method="POST" action="{{ route('user.profiles.update', $profile) }}" enctype="multipart/form-data" class="space-y-8"
            id="profileForm">
            @csrf
            @method('PUT')

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
                        value="{{ old('name', $profile->name) }}">

                    <div class="flex items-center gap-4 lg:col-span-3">
                        <label class="flex items-center gap-2 text-[#FFFFFFCC]">
                            <input type="radio" name="profile_type" value="individual" 
                                {{ old('profile_type', $profile->profile_type) == 'individual' ? 'checked' : '' }}
                                class="w-6 h-6 bg-transparent text-[#6340FF] accent-[#6340FF]">
                            Индивидуалка
                        </label>
                        <label class="flex items-center gap-2 text-[#FFFFFFCC]">
                            <input type="radio" name="profile_type" value="salon"
                                {{ old('profile_type', $profile->profile_type) == 'salon' ? 'checked' : '' }}
                                class="w-6 h-6 text-[#6340FF] bg-transparent accent-[#6340FF]">
                            Интим-салон
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-2 py-4">
                    <input type="number" name="age" placeholder="Возраст/лет"
                        class="block w-full px-4 py-3 rounded-xl border-0 text-white bg-[#191919] placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('age', $profile->age) }}">
                    <input type="number" name="weight" placeholder="Вес/кг"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919]  border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('weight', $profile->weight) }}">
                    <input type="number" name="height" placeholder="Рост/см"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919]  border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('height', $profile->height) }}">
                    <input type="text" name="size" placeholder="Грудь/размер"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919]  border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('size', $profile->size) }}">
                    <select name="tattoo" required
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919]  border-0 text-[#FFFFFF66] placeholder-[#FFFFFF66] focus:ring-0">
                        <option class=" text-[#FFFFFF66]" value="" disabled {{ old('tattoo', $profile->tattoo) ? '' : 'selected' }}>Тату/есть или нет</option>
                        <option value="есть" {{ old('tattoo', $profile->tattoo) == 'есть' ? 'selected' : '' }}>Есть</option>
                        <option value="нет" {{ old('tattoo', $profile->tattoo) == 'нет' ? 'selected' : '' }}>Нет</option>
                    </select>
                </div>

            </div>

            {{-- Описание/О себе --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">О себе</h2>
                <textarea name="description" placeholder="Расскажите о себе..." rows="4" required
                    class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0">{{ old('description', $profile->description) }}</textarea>
            </div>

            {{-- Цвет волос --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">Цвет волос</h2>
                <div class="flex flex-wrap gap-6">
                    @foreach (['Русая', 'Рыжая', 'Шатенка', 'Брюнетка', 'Блондинка'] as $color)
                        <label class="flex items-center gap-2 text-[#FFFFFFCC]">
                            <input type="radio" name="hair_color" value="{{ $color }}"
                                class="w-6 p-1 h-6 rounded-full border text-[#6340FF] bg-transparent"
                                {{ old('hair_color', $profile->hair_color) == $color ? 'checked' : '' }} required>
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
                            {{ old('has_telegram', $profile->has_telegram) ? 'checked' : '' }}>
                        <span class="w-24 hidden md:block text-[#FFFFFFCC]">Телеграм</span>
                        <input name="telegram" id="telegram"
                            class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                            type="text" placeholder="Введите ник Telegram" value="{{ old('telegram', $profile->telegram) }}">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="has_viber" value="0">
                        <input type="checkbox" name="has_viber" id="has_viber" value="1"
                            class="w-6 h-6 hidden md:block text-[#6340FF] bg-transparent focus:ring-[#6340FF]"
                            {{ old('has_viber', $profile->has_viber) ? 'checked' : '' }}>
                        <span class="w-24 hidden md:block text-[#FFFFFFCC]">Viber</span>
                        <input name="viber" id="viber"
                            class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                            type="text" placeholder="Введите номер Viber" value="{{ old('viber', $profile->viber) }}">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="has_whatsapp" value="0">
                        <input type="checkbox" name="has_whatsapp" id="has_whatsapp" value="1"
                            class="w-6 h-6 hidden md:block text-[#6340FF] bg-transparent focus:ring-[#6340FF]"
                            {{ old('has_whatsapp', $profile->has_whatsapp) ? 'checked' : '' }}>
                        <span class="w-24 hidden md:block text-[#FFFFFFCC]">WhatsApp</span>
                        <input name="whatsapp" id="whatsapp"
                            class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                            type="text" placeholder="Введите номер WhatsApp" value="{{ old('whatsapp', $profile->whatsapp) }}">
                    </div>
                    <input name="phone" required
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        type="text" placeholder="+7 (__) ___-__-__" value="{{ old('phone', $profile->phone) }}">
                    <input name="email"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        type="email" placeholder="Email" value="{{ old('email', $profile->email) }}">
                </div>
            </div>

            {{-- Фото и видео --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">Фото и видео</h2>
                <p class="text-sm text-[#FFFFFF99] mb-2">* Требуется хотя бы одно фото</p>
                
                {{-- Фотографии профиля --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-4">
                                        {{-- Видео --}}
                                        @if($profile->video)
                                        <div class="relative video-container">
                                            <video src="{{ asset('storage/' . $profile->video->path) }}" 
                                                class="aspect-[64/100] object-cover rounded-xl" controls></video>
                                            <div class="absolute top-2 right-2">
                                                <label class="flex items-center justify-center w-6 h-6 bg-red-500 rounded-full cursor-pointer">
                                                    <input type="checkbox" name="delete_video" value="1" class="hidden">
                                                    <span class="text-white text-sm">×</span>
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="video-upload-container video-container">
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
                                    @endif

                    {{-- Текущие фото --}}
                    @foreach($profile->images as $image)
                        <div class="photo-upload-container">
                            <div class="relative aspect-[64/100] sm:aspect-[64/100] md:aspect-[81/100] lg:aspect-[64/100] rounded-xl overflow-hidden">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Фото профиля" 
                                    class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2">
                                    <label class="flex items-center justify-center w-6 h-6 bg-red-500 rounded-full cursor-pointer existing-photo-delete-btn">
                                        <input type="checkbox" name="delete_photos[]" value="{{ $image->id }}" class="hidden"> 
                                        <span class="text-white text-sm w-full h-full flex items-center justify-center">×</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    {{-- Добавить новые фото --}}
                    @for ($i = 0; $i < max(0, 10 - $profile->images->count()); $i++)
                        <div class="photo-upload-container">
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
                selectedDistrictIds: {{ json_encode($profile->neighborhoods->pluck('id')) }},
                selectedStationIds: {{ json_encode($profile->metroStations->pluck('id')) }},
                allDistricts: {{ Js::from($neighborhoods->pluck('name', 'id')) }},
                allStations: {{ Js::from($metroStations->pluck('name', 'id')) }},
                selectedDistricts: [],
                selectedStations: [],
                init() {
                    // Initialize selected districts and stations with names instead of IDs
                    this.selectedDistricts = this.selectedDistrictIds.map(id => this.allDistricts[id]);
                    this.selectedStations = this.selectedStationIds.map(id => this.allStations[id]);
                }
            }">
                <h2 class="text-xl font-semibold mb-4">Локация</h2>
            
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Districts select -->
                    <div class="input bg-[#191919] w-full cursor-pointer px-4 py-3 rounded-xl"
                        :class="showDistricts ? 'bg-[#6340FF]' : ''"
                        @click="showDistricts = !showDistricts; showStations = false">
                        <template x-if="selectedDistricts.length">
                            <span x-text="selectedDistricts.join(', ')"></span>
                        </template>
                        <template x-if="!selectedDistricts.length">
                            <span class="text-[#FFFFFF66]">Выберите район</span>
                        </template>
                    </div>
            
                    <!-- Stations select -->
                    <div class="input bg-[#191919] w-full cursor-pointer px-4 py-3 rounded-xl"
                        :class="showStations ? 'bg-[#6340FF]' : ''"
                        @click="showStations = !showStations; showDistricts = false">
                        <template x-if="selectedStations.length">
                            <span x-text="selectedStations.join(', ')"></span>
                        </template>
                        <template x-if="!selectedStations.length">
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
                                    :checked="selectedDistrictIds.includes(parseInt(id))"
                                    @change="if($event.target.checked) {
                                        selectedDistrictIds.push(parseInt(id));
                                        selectedDistricts.push(district);
                                    } else {
                                        selectedDistrictIds = selectedDistrictIds.filter(d => d !== parseInt(id));
                                        selectedDistricts = selectedDistricts.filter(d => d !== district);
                                    }">
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
                                    :checked="selectedStationIds.includes(parseInt(id))"
                                    @change="if($event.target.checked) {
                                        selectedStationIds.push(parseInt(id));
                                        selectedStations.push(station);
                                    } else {
                                        selectedStationIds = selectedStationIds.filter(s => s !== parseInt(id));
                                        selectedStations = selectedStations.filter(s => s !== station);
                                    }">
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
          
            {{-- Карта для выбора местоположения --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">Выберите ваше местоположение на карте</h2>
                <div id="map-edit" style="width: 100%; height: 400px;" class="rounded-xl"></div>
                <p id="map-address-edit-form" class="text-white mt-2 mb-2"></p>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $profile->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $profile->longitude) }}">
                <p class="text-sm text-[#FFFFFF99] mt-2">Кликните на карту, чтобы указать ваше точное местоположение. Вы можете перетаскивать маркер.</p>
            </div>

            {{-- price --}}
            <div x-data="{
                rentalOptions: ['Выезд', 'Апартаменты'],
                selectedRentals: [
                    {{ old('vyezd', $profile->vyezd) ? 'true' : 'false' }},
                    {{ old('appartamenti', $profile->appartamenti) ? 'true' : 'false' }}
                ]
            }" class="p-6 rounded-xl mt-6">
                <h3 class="text-lg font-semibold mb-4 text-white">Стоимость</h3>

                <div class="space-y-6">
                    <!-- Выезд -->
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <label class="flex items-center gap-2 text-[#FFFFFFCC] min-w-[150px]">
                            <input type="checkbox" name="vyezd" value="1"
                                class="pricing-checkbox w-4 h-4 bg-transparent text-[#6340FF] focus:ring-0"
                                :checked="selectedRentals[0]"
                                @change="selectedRentals[0] = $event.target.checked">
                            <span>Выезд</span>
                        </label>

                        <div class="flex flex-col sm:flex-row gap-2 flex-1">
                            <input type="text" name="vyezd_1hour" placeholder="Цена за 1 час"
                                class="pricing-input w-full bg-[#191919] focus:ring-0 border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                value="{{ old('vyezd_1hour', $profile->vyezd_1hour) }}" />
                            <input type="text" name="vyezd_2hours" placeholder="Цена за 2 часа"
                                class="pricing-input w-full bg-[#191919] border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                value="{{ old('vyezd_2hours', $profile->vyezd_2hours) }}" />
                            <input type="text" name="vyezd_night" placeholder="Цена за ночь"
                                class="pricing-input w-full bg-[#191919] border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                value="{{ old('vyezd_night', $profile->vyezd_night) }}" />
                        </div>
                    </div>

                    <!-- Апартаменты -->
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <label class="flex items-center gap-2 text-[#FFFFFFCC] min-w-[150px]">
                            <input type="checkbox" name="appartamenti" value="1"
                                class="pricing-checkbox w-4 h-4 bg-transparent text-[#6340FF] focus:ring-0"
                                :checked="selectedRentals[1]"
                                @change="selectedRentals[1] = $event.target.checked">
                            <span>Апартаменты</span>
                        </label>

                        <div class="flex flex-col sm:flex-row gap-2 flex-1">
                            <input type="text" name="appartamenti_1hour" placeholder="Цена за 1 час"
                                class="pricing-input w-full bg-[#191919] focus:ring-0 border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                value="{{ old('appartamenti_1hour', $profile->appartamenti_1hour) }}" />
                            <input type="text" name="appartamenti_2hours" placeholder="Цена за 2 часа"
                                class="pricing-input w-full bg-[#191919] border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                value="{{ old('appartamenti_2hours', $profile->appartamenti_2hours) }}" />
                            <input type="text" name="appartamenti_night" placeholder="Цена за ночь"
                                class="pricing-input w-full bg-[#191919] border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                value="{{ old('appartamenti_night', $profile->appartamenti_night) }}" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Услуги --}}
            <div class="bg-transparent p-6 rounded-xl mt-6">
                <h3 class="text-lg font-semibold mb-4 text-white">Услуги</h3>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($services as $service)
                        <label class="flex items-center gap-2 text-[#FFFFFFCC]">
                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                class="w-4 h-4 text-[#6340FF] bg-transparent focus:ring-[#6340FF]"
                                {{ in_array($service->id, old('services', $profile->services->pluck('id')->toArray())) ? 'checked' : '' }}>
                            {{ $service->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Платные услуги --}}
            <div class="bg-transparent p-6 rounded-xl mt-6" x-data="{
                selectedWithPrice: {{ json_encode(old('paid_services', $profile->paidServices->pluck('id')->toArray())) }}
            }">
                <h3 class="text-lg font-semibold mb-4 text-white">Платные услуги</h3>

                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-y-6 space-y-4">
                    @foreach($paidServices as $paidService)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <label class="flex items-center gap-2 text-[#FFFFFFCC] min-w-[200px]">
                                <input type="checkbox" name="paid_services[]" value="{{ $paidService->id }}"
                                    class="w-4 h-4 text-[#6340FF] bg-transparent focus:ring-[#6340FF]"
                                    @change="selectedWithPrice.includes({{ $paidService->id }}) ? 
                                        selectedWithPrice = selectedWithPrice.filter(id => id !== {{ $paidService->id }}) : 
                                        selectedWithPrice.push({{ $paidService->id }})"
                                    {{ in_array($paidService->id, old('paid_services', $profile->paidServices->pluck('id')->toArray())) ? 'checked' : '' }}>
                                {{ $paidService->name }}
                            </label>
                            <input type="number" name="paid_service_prices[{{ $paidService->id }}]" placeholder="Цена"
                                class="w-full sm:w-40 bg-[#191919] border-0 placeholder-[#FFFFFF66] text-white px-3 py-2 rounded-lg"
                                value="{{ old('paid_service_prices.' . $paidService->id, $profile->paidServices->find($paidService->id)->pivot->price ?? '') }}"
                                x-bind:required="selectedWithPrice.includes({{ $paidService->id }})"
                                min="0">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex justify-center md:mt-12">
                <button type="submit"
                    class="w-full md:w-[300px] h-12 bg-[#6340FF] text-white rounded-[12px] px-[10px] py-[10px] text-center text-sm font-semibold hover:bg-[#5032cc] transition">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>

            <!-- Footer -->
            <footer class="pb-6 mt-auto">
                <div class="max-w-screen-2xl mx-auto px-6">
                    <div class="flex  border-t border-[#363636] pt-6 justify-between items-center ">
                        <div class="mb-4 sm:mb-0">
                            <a href="/" class="text-xl font-bold">
                                <img src="{{ asset($logoSetting->logo) }}" 
                                alt="SHEMK logo" 
                                class="h-12 w-auto object-contain"
                                loading="lazy">
                            </a>
                        </div>
    
                        <div class="flex space-x-4">
                            @guest
                            
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 bg-transparent text-white border border-white-700 rounded-lg text-sm">
                                Добавить анкету
                            </a>
                            <a href="{{ route('login') }}"
                               class="px-4 py-2 bg-transparent text-white rounded-md text-sm">
                                Войти
                            </a>
                        @else
                       
                            @if(Auth::user()->is_admin)
                            <a href="{{ route('admin') }}" class="relative p-2 mr-2 hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </a>
                            @endif
                            <a href="{{ route('user.profiles.index') }}"
                               class="px-4 py-2 bg-transparent text-white border border-white-700 rounded-lg text-sm">
                                Мой кабинет
                            </a>
                        @endguest
                        </div>
                    </div>
                </div>
            </footer>
@endsection

@push('scripts')
@once
    <script src="https://api-maps.yandex.ru/2.1/?apikey={{$yandexApiKey}}&lang=ru_RU" type="text/javascript" id="yandex-maps-api-script-edit"></script>
@endonce
<script type="text/javascript">
    function initEditFormMap() {
        if (document.getElementById('map-edit') && document.getElementById('map-edit').dataset.mapInitialized === 'true') {
            return; // Map already initialized
        }
        if (typeof ymaps === 'undefined' || typeof ymaps.Map === 'undefined') {
            // Yandex Maps API not loaded yet, try again after a short delay
            setTimeout(initEditFormMap, 100);
            return;
        }

        document.getElementById('map-edit').dataset.mapInitialized = 'true';
        var latitudeInput = document.getElementById('latitude');
        var longitudeInput = document.getElementById('longitude');
        var initialCoords = [{{ old('latitude', $profile->latitude) ?: 59.9343 }}, {{ old('longitude', $profile->longitude) ?: 30.3351 }}]; // Default to Saint Petersburg or existing


        var myMap = new ymaps.Map('map-edit', {
            center: initialCoords,
            zoom: 10
        });

        var myPlacemark;

        function geocodeAndSetAddress(coords) {
            ymaps.geocode(coords).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0);
                var addressElement = document.getElementById('map-address-edit-form');
                if (firstGeoObject) {
                    var addressLine = firstGeoObject.getAddressLine();
                    if (addressElement) {
                        addressElement.textContent = addressLine;
                    }
                } else {
                    if (addressElement) {
                        addressElement.textContent = 'Адрес не определен.';
                    }
                }
            }).catch(function(err) {
                console.warn('Ошибка геокодирования:', err.message);
                var addressElement = document.getElementById('map-address-edit-form');
                if (addressElement) {
                    addressElement.textContent = 'Ошибка при определении адреса.';
                }
            });
        }

        // If coordinates are present, create a placemark and geocode
        if (parseFloat(latitudeInput.value) && parseFloat(longitudeInput.value)) {
            var currentCoords = [parseFloat(latitudeInput.value), parseFloat(longitudeInput.value)];
            myPlacemark = createPlacemark(currentCoords);
            myMap.geoObjects.add(myPlacemark);
            geocodeAndSetAddress(currentCoords); // Geocode initial position
            myPlacemark.events.add('dragend', function () {
                var coords = myPlacemark.geometry.getCoordinates();
                latitudeInput.value = coords[0];
                longitudeInput.value = coords[1];
                geocodeAndSetAddress(coords);
            });
        }

        myMap.events.add('click', function (e) {
            var coords = e.get('coords');
            latitudeInput.value = coords[0];
            longitudeInput.value = coords[1];
            geocodeAndSetAddress(coords);

            if (myPlacemark) {
                myPlacemark.geometry.setCoordinates(coords);
            } else {
                myPlacemark = createPlacemark(coords);
                myMap.geoObjects.add(myPlacemark);
                myPlacemark.events.add('dragend', function () {
                    var newCoords = myPlacemark.geometry.getCoordinates();
                    latitudeInput.value = newCoords[0];
                    longitudeInput.value = newCoords[1];
                    geocodeAndSetAddress(newCoords);
                });
            }
        });

        function createPlacemark(coords) {
            return new ymaps.Placemark(coords, {
                hintContent: 'Ваше выбранное местоположение',
                balloonContent: 'Перетащите, чтобы изменить'
            }, {
                preset: 'islands#violetDotIconWithCaption',
                draggable: true
            });
        }
        myMap.behaviors.disable('scrollZoom');
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        if (typeof ymaps !== 'undefined') {
            ymaps.ready(initEditFormMap);
        } else {
            const scriptTag = document.getElementById('yandex-maps-api-script-edit');
            if (scriptTag) {
                scriptTag.onload = () => ymaps.ready(initEditFormMap);
                scriptTag.onerror = () => console.error('Yandex Maps API script failed to load for edit form.');
            } else {
                 console.error('Yandex Maps API script tag not found for edit form.');
            }
        }
    } else {
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof ymaps !== 'undefined') {
                ymaps.ready(initEditFormMap);
            } else {
                const scriptTag = document.getElementById('yandex-maps-api-script-edit');
                if (scriptTag) {
                    scriptTag.onload = () => ymaps.ready(initEditFormMap);
                    scriptTag.onerror = () => console.error('Yandex Maps API script failed to load for edit form.');
                } else {
                    console.error('Yandex Maps API script tag not found for edit form.');
                }
            }
        });
    }
</script>
@endpush

@push('scripts')
       {{-- Include edit form validation script --}}
       <script src="{{ asset('js/edit-form-validation.js') }}"></script>
    
       <script>
           document.addEventListener('DOMContentLoaded', function() {
               // Photo upload preview is handled in edit-form-validation.js
               // We're removing this code to prevent duplicate event handlers
               // that could cause the file explorer to reopen automatically
               
               // Add event listeners for remove photo buttons
               const removePhotoButtons = document.querySelectorAll('.remove-photo');
               removePhotoButtons.forEach(button => {
                   button.addEventListener('click', function(e) {
                       e.preventDefault();
                       const container = this.closest('.photo-upload-container');
                       const input = container.querySelector('.photo-input');
                       const preview = container.querySelector('.photo-preview');
                       const placeholder = container.querySelector('.photo-placeholder');
                       
                       input.value = '';
                       preview.classList.add('hidden');
                       placeholder.classList.remove('hidden');
                   });
               });
               
               // Video upload preview
               const videoInput = document.querySelector('.video-input');
               if (videoInput) {
                   videoInput.addEventListener('change', function() {
                       const container = this.closest('.video-upload-container');
                       const preview = container.querySelector('.video-preview video');
                       const placeholder = container.querySelector('.video-placeholder');
                       const previewContainer = container.querySelector('.video-preview');
                       
                       if (this.files && this.files[0]) {
                           const url = URL.createObjectURL(this.files[0]);
                           preview.src = url;
                           placeholder.classList.add('hidden');
                           previewContainer.classList.remove('hidden');
                       }
                   });
               }
               
               // Add event listener for remove video button
               const removeVideoButton = document.querySelector('.remove-video');
               if (removeVideoButton) {
                   removeVideoButton.addEventListener('click', function(e) {
                       e.preventDefault();
                       const container = this.closest('.video-upload-container');
                       const input = container.querySelector('.video-input');
                       const preview = container.querySelector('.video-preview');
                       const placeholder = container.querySelector('.video-placeholder');
                       
                       input.value = '';
                       preview.classList.add('hidden');
                       placeholder.classList.remove('hidden');
                   });
               }
               
               // Delete photo checkboxes styling
               const deletePhotoCheckboxes = document.querySelectorAll('input[name="delete_photos[]"]');
               deletePhotoCheckboxes.forEach(checkbox => {
                   const label = checkbox.closest('label');
                   checkbox.addEventListener('change', function() {
                       if (this.checked) {
                           label.classList.add('bg-red-500');
                       } else {
                           label.classList.remove('bg-red-500');
                       }
                   });
               });
               
               // Delete video checkbox styling
               const deleteVideoCheckbox = document.querySelector('input[name="delete_video"]');
               if (deleteVideoCheckbox) {
                   const label = deleteVideoCheckbox.closest('label');
                   deleteVideoCheckbox.addEventListener('change', function() {
                       if (this.checked) {
                           label.classList.add('bg-red-500');
                       } else {
                           label.classList.remove('bg-red-500');
                       }
                   });
               }
               
               // Add event listeners to paid service checkboxes to show/hide price inputs
               const paidServiceCheckboxes = document.querySelectorAll('input[name="paid_services[]"]');
               paidServiceCheckboxes.forEach(checkbox => {
                   checkbox.addEventListener('change', function() {
                       const serviceId = this.value;
                       const priceInput = document.querySelector(`input[name="paid_service_prices[${serviceId}]"]`);
                       
                       if (priceInput) {
                           if (this.checked) {
                               priceInput.required = true;
                           } else {
                               priceInput.required = false;
                               priceInput.value = '';
                           }
                       }
                   });
                   
                   // Initialize on page load
                   if (checkbox.checked) {
                       const serviceId = checkbox.value;
                       const priceInput = document.querySelector(`input[name="paid_service_prices[${serviceId}]"]`);
                       if (priceInput) {
                           priceInput.required = true;
                       }
                   }
               });
           });
       </script>
@endpush
