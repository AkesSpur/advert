@extends('second-layout')

@section('content')
    <div class="p-6 space-y-8 mx-auto text-white max-w-screen-2xl">
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
                    <input type="text" name="tattoo" placeholder="Тату/есть или нет"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919]  border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                        value="{{ old('tattoo', $profile->tattoo) }}">
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
                            type="text" placeholder="Введите свой ник" value="{{ old('telegram', $profile->telegram) }}">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="has_viber" value="0">
                        <input type="checkbox" name="has_viber" id="has_viber" value="1"
                            class="w-6 h-6 hidden md:block text-[#6340FF] bg-transparent focus:ring-[#6340FF]"
                            {{ old('has_viber', $profile->has_viber) ? 'checked' : '' }}>
                        <span class="w-24 hidden md:block text-[#FFFFFFCC]">Viber</span>
                        <input name="viber" id="viber"
                            class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                            type="text" placeholder="Введите свой ник" value="{{ old('viber', $profile->viber) }}">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="has_whatsapp" value="0">
                        <input type="checkbox" name="has_whatsapp" id="has_whatsapp" value="1"
                            class="w-6 h-6 hidden md:block text-[#6340FF] bg-transparent focus:ring-[#6340FF]"
                            {{ old('has_whatsapp', $profile->has_whatsapp) ? 'checked' : '' }}>
                        <span class="w-24 hidden md:block text-[#FFFFFFCC]">WhatsApp</span>
                        <input name="whatsapp" id="whatsapp"
                            class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0"
                            type="text" placeholder="Введите свой ник" value="{{ old('whatsapp', $profile->whatsapp) }}">
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
                                        <div class="relative">
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
                                        <div class="video-upload-container">
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
                                    <label class="flex items-center justify-center w-6 h-6 bg-red-500 rounded-full cursor-pointer">
                                        <input type="checkbox" name="delete_photos[]" value="{{ $image->id }}" class="hidden"> 
                                        <button type="button" class="text-white text-sm w-full h-full flex items-center justify-center">×</button>
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
@endsection

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