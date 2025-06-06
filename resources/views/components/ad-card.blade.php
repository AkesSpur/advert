@props([
    'id' => 4,
    'slug' => '',
    'vip' => false,
    'new' => false,
    'video' => false,
    'isFirst' => false,
    'verified' => false,
    'name' => 'Анюта',
    'age' => 23,
    'weight' => '66 кг',
    'height' => '175 см',
    'size' => '3 размер',
    'metro_items' => [], // Array of objects: [{name: 'Station Name', slug: 'station-slug'}, ...]
    'district_display' => 'р. Василеостровский', // For display
    'district_slug' => '', // For link
    'phone' => '+7 (931) 632-86-00',
    'prices' => [
        'hour' => '4000 руб.',
        'two_hours' => '8000 руб.',
        'night' => '15000 руб.',
    ],
    'img' => [],
    ])

<a href="{{route('profiles.view', ['slug' => $slug, 'id'=>$id])}}" name="profile">
<div
    class="bg-[#191919] rounded-2xl mb-3 mr-1 overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl hover:scale-[1.02] hover:ring-2 hover:ring-[#6340FF] w-full">
    <!-- Image section with gradient overlay -->
    <div x-data="{
        current: 0,
        isHovering: false,
        touchStartX: 0,
        touchEndX: 0,
        images: [
            @foreach ($img as $image)
               '{{ asset('storage/' .$image->path) }}',
            @endforeach

           
        ],
        nextSlide() {
            this.current = (this.current === this.images.length - 1) ? 0 : this.current + 1;
        },
        prevSlide() {
            this.current = (this.current === 0) ? this.images.length - 1 : this.current - 1;
        },
        handleSwipe() {
            if (this.touchEndX < this.touchStartX - 50) { // Swipe left (next)
                this.nextSlide();
            }
            if (this.touchEndX > this.touchStartX + 50) { // Swipe right (previous)
                this.prevSlide();
            }
        }
    }" 
    @mouseenter="isHovering = true" 
    @mouseleave="isHovering = false"
    @touchstart="touchStartX = $event.changedTouches[0].screenX"
    @touchend="touchEndX = $event.changedTouches[0].screenX; handleSwipe()"
        class="relative cursor-pointer w-full aspect-square md:aspect-[3/4] overflow-hidden">

<!-- Image carousel -->
    <template x-for="(img, index) in images" :key="index">
        <img :src="img" alt="{{ $name }}"
            class="absolute inset-0 w-full h-full object-cover object-top rounded-xl transition-opacity duration-500"
            :class="current === index ? 'opacity-100' : 'opacity-0'"
             loading="{{ $isFirst && $loop->first ? 'eager' : 'lazy'}}" > {{-- Lazy load images in ad cards --}}
    </template>

        <!-- Top badges -->
        <div class="absolute top-3 left-3 flex flex-col items-start gap-2 z-20">
            @if ($video)
            <!-- Video badge -->
            <div class="w-7 h-7 flex items-center justify-center rounded-full bg-pink-500 text-white">
                <img alt="badge" src="{{asset('assets/svg/vid.png')}}" class="w-4 h-3">

            </div>                
            @endif

            @if ($new)
            <div
                class="w-8 h-8 flex items-center justify-center text-xs font-semibold bg-[#4059FF] text-white rounded-full">
                New
            </div>                
            @endif

            @if($vip)
            <div
                class="w-8 h-8 flex items-center justify-center text-xs font-semibold bg-[#D3A25B] text-white rounded-full">
                VIP
            </div>
            @endif

        </div>

        <!-- Like button -->
        <button
            data-profile-id="{{ $id }}" name="like"
            class="like-button absolute top-3 right-3 p-1.5 hover:scale-105 transition z-20 rounded-full 'bg-transparent'"
            aria-label="Like profile">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"
                fill="{{ (Auth::check() && Auth::user()->likedProfiles()->where('profile_id', $id)->exists()) || (!Auth::check() && in_array($id, session('liked_profiles', []))) ? 'red' : 'none' }}"
                stroke="{{ (Auth::check() && Auth::user()->likedProfiles()->where('profile_id', $id)->exists()) || (!Auth::check() && in_array($id, session('liked_profiles', []))) ? 'none' : 'white' }}"
                stroke-width="{{ (Auth::check() && Auth::user()->likedProfiles()->where('profile_id', $id)->exists()) || (!Auth::check() && in_array($id, session('liked_profiles', []))) ? 'none' : '2' }}"
                viewBox="0 0 24 24">
                <path d="M12.1 21.35l-1.45-1.32C5.4 15.36 2 12.28
                     2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41
                     0.81 4.5 2.09C13.09 3.81 14.76 3
                     16.5 3 19.58 3 22 5.42 22 8.5c0
                     3.78-3.4 6.86-8.55 11.54l-1.35 1.31z" />
            </svg>
        </button>

        <!-- Navigation Arrows -->
        <div class="absolute inset-0 flex items-center justify-between z-10 transition-opacity duration-300"
            :class="{ 'opacity-100 pointer-events-auto': isHovering, 'opacity-0 pointer-events-none': !isHovering }">

            <button @click.stop.prevent="prevSlide()" name="prev"
                class="text-[#FFFFFFCC] ml-2 hover:scale-110 transition-transform duration-200" aria-label="Previous image">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                    stroke="white">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button @click.stop.prevent="nextSlide()" name="next"
                class="text-[#FFFFFFCC] mr-2 hover:scale-110 transition-transform duration-200" aria-label="Next image">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                    stroke="white">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Bottom overlay -->
        <div class="absolute inset-x-0 bottom-0 h-28 z-20" @click.stop=""
            style="background: linear-gradient(358.53deg, #131313 8%, rgba(0, 0, 0, 0) 92%);">
            <div class="absolute bottom-3 left-4 right-4">
               <div class="flex items-center gap-2">
                  <h3 class="text-white capitalize text-xl font-semibold flex items-center gap-1">
                      {{ $name }},
                      <span class="relative inline-flex items-center">
                          <span>{{ $age }}</span>
                          @if ($verified)
                              <img src="{{ asset('assets/svg/verified.png') }}"
                                   class="absolute -top-1 -right-4 w-4 h-4" alt="badge">
                          @endif
                      </span>
                  </h3>
              </div>
              

                <div class="flex flex-wrap gap-2 pt-1">
                    <div class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-sm text-white">{{ $weight }}</div>
                    <div class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-sm text-white">{{ $height }}</div>
                    <div class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-sm text-white">{{ $size }}</div>
                </div>
            </div>
        </div>
    </div>
</a>


    <!-- Contact and location info -->
    <div class="py-3 px-4 space-y-2 bg-[#191919]">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" fill="none" stroke="#4059FF" stroke-width="2" />
                <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#4059FF"
                    font-family="Arial, sans-serif">M</text>
            </svg>
            @if(!empty($metro_items))
                <div x-data="{ showAllMetro: false }" class="text-[#FFFFFF]">
                    @foreach($metro_items as $index => $station_item)
                        @if($loop->index < 2)
                            <a href="{{ route('home.metro', ['slug' => $station_item->slug]) }}" class="hover:text-[#6340FF] transition-colors">
                                м. {{ $station_item->name }}</a>@if(!$loop->last && $loop->index < count($metro_items) - 1 && $loop->index < 1), @endif
                        @elseif($loop->index >= 2)
                            <a x-show="showAllMetro" href="{{ route('home.metro', ['slug' => $station_item->slug]) }}" class="hover:text-[#6340FF] transition-colors">
                                м. {{ $station_item->name }}</a>@if(!$loop->last && $loop->index < count($metro_items) - 1), @endif
                        @endif
                    @endforeach
                    @if(count($metro_items) > 2)
                        <button @click="showAllMetro = !showAllMetro" class="text-[#FFFFFFCC] hover:text-[#6340FF] transition-colors text-sm focus:outline-none">
                            <span x-show="!showAllMetro">{{ count($metro_items) - 2 }}+ Еще</span>
                            <span x-show="showAllMetro">Скрыть</span>
                        </button>
                    @endif
                </div>
            @else
                <span class="text-[#FFFFFF66]">Метро не указано</span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#920C0C] flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 2C7.589 2 4 5.589 4 9.995C4 15.4 12 22 12 22C12 22 20 15.4 20 9.995C20 5.589 16.411 2 12 2ZM12 14C9.791 14 8 12.209 8 10C8 7.791 9.791 6 12 6C14.209 6 16 7.791 16 10C16 12.209 14.209 14 12 14Z" />
            </svg>
            @php
                $districts = is_array($district_display) ? $district_display : [];
            @endphp
            @if(!empty($districts))
                <div x-data="{ showAllDistricts: false }" class="text-[#FFFFFF]">
                    @foreach($districts as $index => $district_item)
                         @if($loop->index < 2)
                            <a href="{{ route('home.neighborhood', ['slug' => $district_item['slug']]) }}" class="hover:text-[#6340FF] transition-colors">
                                {{ $district_item['name'] }}</a>@if(!$loop->last && $loop->index < count($districts) - 1 && $loop->index < 1), @endif
                        @elseif($loop->index >= 2)
                            <a x-show="showAllDistricts" href="{{ route('home.neighborhood', ['slug' => $district_item['slug']]) }}" class="hover:text-[#6340FF] transition-colors">
                                {{ $district_item['name'] }}</a>@if(!$loop->last && $loop->index < count($districts) - 1), @endif
                        @endif
                    @endforeach
                    @if(count($districts) > 2)
                        <button @click="showAllDistricts = !showAllDistricts" class="text-[#FFFFFFCC] hover:text-[#6340FF] transition-colors text-sm focus:outline-none">
                            <span x-show="!showAllDistricts">{{ count($districts) - 2 }}+ Еще</span>
                            <span x-show="showAllDistricts">Скрыть</span>
                        </button>
                    @endif
                </div>
            @else
                <span class="text-[#FFFFFF66]">Район не указан</span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5FD01399] flex-shrink-0" viewBox="0 0 24 24"
                fill="currentColor">
                <path
                    d="M3.654 3.318a2.25 2.25 0 013.182.145l2.112 2.292a2.25 2.25 0 01-.168 3.22l-1.062.893a.75.75 0 00-.168.97 11.7 11.7 0 005.37 5.37.75.75 0 00.97-.168l.893-1.062a2.25 2.25 0 013.22-.168l2.292 2.112a2.25 2.25 0 01.145 3.182l-1.063 1.062a2.25 2.25 0 01-2.403.532c-3.175-1.206-6.373-3.559-9.271-6.457s-5.251-6.096-6.457-9.27a2.25 2.25 0 01.532-2.403l1.062-1.063z" />
            </svg>
            <a href="tel:{{ $phone }}" class="text-[#FFFFFF] hover:underline transition duration-300">
                {{ formatNumber($phone) }}
            </a>
        </div>
    </div>


    <!-- Pricing section -->
    <div class="bg-[#191919] mt-auto px-4 rounded-br-3xl rounded-bl-3xl py-3 space-y-2">
        <div class="flex justify-between border-b border-[#FFFFFF4D] items-center">
            <div class="md:text-sm  text-[#FFFFFFCC]">За один час</div>
            <div class="text-[#FFFFFFCC] md:text-sm  font-semibold">{{ round($prices['hour']) }} руб.</div>
        </div>
        <div class="flex justify-between border-b border-[#FFFFFF4D] items-center">
            <div class="md:text-sm  text-[#FFFFFFCC]">За 2 часа</div>
            <div class="text-[#FFFFFFCC] md:text-sm  font-semibold">{{ round($prices['two_hours']) }} руб.</div>
        </div>
        <div class="flex justify-between items-center">
            <div class="md:text-sm  text-[#FFFFFFCC]">За всю ночь</div>
            <div class="text-[#FFFFFFCC] md:text-sm  font-semibold">{{ round($prices['night']) }} руб.</div>
        </div>
    </div>
   </div>
