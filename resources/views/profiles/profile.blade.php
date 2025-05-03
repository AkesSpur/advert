@extends('second-layout')

@section('content')
    <div class="max-w-screen-2xl mx-auto px-1 md:px-4 py-6">
        <!-- Breadcrumbs -->
        <div class="text-sm text-gray-400 mb-4 pl-4 flex justify-between">
            <nav class="text-sm text-[#A0A0A0] mb-4" aria-label="Breadcrumb">
                <ol class="list-reset flex items-center space-x-1">
                    <li>
                        <a href="/" class="hover:text-[#A0A0A0] text-[#636363]">Главная</a>
                    </li>
                    <li><span>/</span></li>
                    <li>
                        <a href="#" class="hover:text-[#A0A0A0] text-[#636363]">
                            @if ($profile->profile_type == 'individual')
                                Индивидуалки
                            @else
                                Интим-салон
                            @endif
                        </a>
                    </li>
                    <li><span>/</span></li>
                    <li class="text-[#6340FF] font-medium" aria-current="page">{{$profile->name}}</li>
                </ol>
            </nav>

            <div>
                <span class="text-[#636363] text-sm hidden md:block">Анкета добавлена
                    {{$profile->created_at->format('d.m.Y')}}</span>
            </div>
        </div>

        <!-- Profile Header -->
        <div class="rounded-xl ">
            <!-- Profile Info for small and medium screens (shown above gallery) -->
            <div class="block md:block lg:hidden py-2 px-4">
                <div class="flex items-center mb-1 md:mb-none">
                    <h1 class="text-3xl font-semibold text-white mr-2 relative">
                        {{$profile->name . ', ' . $profile->age}}
                        @if ($profile->is_verified)
                            <span class="absolute -top-1 -right-5">
                                <img src="{{ asset('assets/svg/verified.png') }}" class="w-4 h-4">
                            </span>
                        @endif
                    </h1>
                </div>
                <div class="mb-2 hidden md:block lg:hidden">
                    <span class="text-[#636363] text-sm">Была активна 01.02.2025</span>
                </div>

                <!-- Attributes for small and medium screens -->
                <div class="flex md:hidden overflow-x-auto hide-scrollbar gap-3">
                    <span class="bg-[#FFFFFF33] shrink-0 rounded-lg px-3 py-1.5 text-base text-white">{{$profile->weight}}
                        кг</span>
                    <span class="bg-[#FFFFFF33] shrink-0 rounded-lg px-3 py-1.5 text-base text-white">{{$profile->height}}
                        см</span>
                    <span class="bg-[#FFFFFF33] shrink-0 rounded-lg px-3 py-1.5 text-base text-white">{{$profile->size}}
                        размер</span>
                    <span
                        class="bg-[#FFFFFF33] shrink-0 rounded-lg px-3 py-1.5 text-base text-white">{{$profile->hair_color}}</span>
                    {{-- <span class="bg-[#FFFFFF33] shrink-0 rounded-lg px-3 py-1.5 text-base text-white">Славянка</span>
                    --}}
                    <span class="bg-[#FFFFFF33] shrink-0 rounded-lg px-3 py-1.5 text-base text-white">{{$profile->tattoo}}
                        тату</span>
                    {{-- <span class="bg-[#FFFFFF33] shrink-0 rounded-lg px-3 py-1.5 text-base text-white">Интимная
                        стрижка</span> --}}
                </div>
            </div>


            <div class="flex flex-col lg:flex-row">
                <span class="text-[#636363] px-4 text-sm md:hidden">Анкета добавлена
                    {{$profile->created_at->format('d.m.Y')}}</span>
                <!-- Gallery Section -->
                <div class="w-full lg:w-1/2 p-4">
                    <div class="flex flex-row h-full gap-4">
                        <!-- Thumbnails on the left (visible on medium and large screens) -->
                        <div class="hidden md:flex flex-col gap-2 w-1/4 h-full justify-between">
                            @php
                                $slide = 0;
                            @endphp

                            @foreach ($profile->images as $image)
                                <!-- Thumbnail  -->
                                <div class="h-[33%] rounded-xl overflow-hidden cursor-pointer" onclick="showSlide({{$slide}})">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="Thumbnail {{$slide}}"
                                        class="w-full h-full object-cover">
                                    @php
                                        $slide++;                                      
                                      @endphp
                                </div>
                                @if ($slide > 1)

                                    @break
                                @endif
                            @endforeach

                            @if (isset($profile->video->path))
                                <!-- Video Thumbnail -->
                                <div class="h-[33%] rounded-xl overflow-hidden cursor-pointer" onclick="showVideo()">
                                    <div class="relative w-full h-full">
                                        <img src="{{ asset('storage/', $profile->video->thumbnail_path) }}"
                                            alt="Video Thumbnail" class="w-full h-full object-cover opacity-75">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @else

                            @endif

                        </div>
                        <!-- Main Image/Video Carousel on the right -->
                        <div class="relative rounded-xl flex-grow w-full md:w-3/4">
                            <!-- Fixed height container to match thumbnails -->
                            <div class="w-full h-[450px] md:h-full relative">
                                <!-- Top badges -->
                                <div class="absolute top-3 left-3 flex flex-col items-start gap-2 z-10">
                                    @if (isset($profile->video->path))
                                        <div
                                            class="w-7 h-7 flex items-center justify-center rounded-full bg-pink-500 text-white">
                                            <img src="{{asset('assets/svg/vid.png')}}" class="w-4 h-3">
                                        </div>
                                    @endif
                                    @if ($profile->created_at >= now()->subDays(7))
                                        <div
                                            class="w-8 h-8 flex items-center justify-center text-xs font-semibold bg-[#4059FF] text-white rounded-full">
                                            New
                                        </div>
                                    @endif
                                    @if ($profile->is_vip)
                                        <div
                                            class="w-8 h-8 flex items-center justify-center text-xs font-semibold bg-[#D3A25B] text-white rounded-full">
                                            VIP
                                        </div>
                                    @endif
                                </div>
                                <!-- Carousel container -->
                                <div class="carousel-container rounded-xl h-full w-full relative">
                                    @php
                                        $slide = 1;
                                    @endphp
                                    <!-- Images (in carousel) -->
                                    @foreach ($profile->images as $image)
                                        <div
                                            class="carousel-slide absolute inset-0 opacity-100 transition-opacity rounded-xl duration-300">
                                            <img src="{{ asset('storage/' . $image->path) }}" alt="Image {{$slide}}"
                                                class="w-full rounded-xl h-full object-cover">
                                        </div>
                                        @php
                                            $slide++;
                                        @endphp
                                    @endforeach

                                    @if (isset($profile->video->path))
                                        <!-- Video (hidden by default) -->
                                        <div class="video-slide absolute inset-0 opacity-0 transition-opacity duration-300">
                                            <video class="w-full h-full object-cover" controls>
                                                <source src="{{asset('storage/' . $profile->video->path)}}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif
                                </div>

                                <!-- Carousel Controls -->
                                <div class="absolute inset-y-0 left-0 flex items-center">
                                    <button class="p-2 text-white hover:text-gray-300 focus:outline-none"
                                        onclick="prevSlide()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <button class="p-2 text-white hover:text-gray-300 focus:outline-none"
                                        onclick="nextSlide()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Favorite button -->
                                <div class="absolute top-4 right-4 z-10">
                                    <button class="p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- <!-- Mobile indicators (visible on small and medium screens) -->
                                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 lg:hidden">
                                    <button class="h-2 w-2 rounded-full bg-white opacity-100"
                                        onclick="showSlide(0)"></button>
                                    <button class="h-2 w-2 rounded-full bg-white opacity-50"
                                        onclick="showSlide(1)"></button>
                                    <button class="h-2 w-2 rounded-full bg-white opacity-50"
                                        onclick="showSlide(2)"></button>
                                    <button class="h-2 w-2 rounded-full bg-white opacity-50" onclick="showVideo()"></button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Info (shown on large screens inline, shown below gallery on small/medium) -->
                <div class="lg:block lg:w-1/2 p-4 hidden">
                    <div class="flex items-center ">
                        <h1 class="text-4xl font-semibold text-white mr-2 relative">
                            {{$profile->name . ', ' . $profile->age}}
                            @if ($profile->is_verified)
                                <span class="absolute -top-1 -right-5">
                                    <img src="{{ asset('assets/svg/verified.png') }}" class="w-4 h-4">
                                </span>
                            @endif
                        </h1>
                    </div>
                    <p class="text-[#636363] mb-4">Была активна 01.02.2023</p>

                    <!-- Location -->
                    <div class="flex items-center mb-2">
                        <span class="inline-flex gap-2 items-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" fill="none" stroke="#4059FF" stroke-width="2" />
                                <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#4059FF"
                                    font-family="Arial, sans-serif">M</text>
                            </svg>
                            <span class="text-[#C2C2C2]">
                              @foreach ($profile->neighborhoods as $key => $neighborhood)
                              <a href="{{ route('home', ['district' => $neighborhood->name]) }}"
                                  class="hover:text-[#6340FF] transition-colors">
                                  {{$neighborhood->name}}{{ $key < count($profile->neighborhoods) - 1 ? ',' : '' ;}}</a>
                            @endforeach
                        </span>
                    </div>
                    <div class="flex items-center mb-6">
                        <span class="inline-flex gap-2 items-center">
                            <svg class="w-5 h-5 text-[#920C0C] flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C7.589 2 4 5.589 4 9.995C4 15.4 12 22 12 22C12 22 20 15.4 20 9.995C20 5.589 16.411 2 12 2ZM12 14C9.791 14 8 12.209 8 10C8 7.791 9.791 6 12 6C14.209 6 16 7.791 16 10C16 12.209 14.209 14 12 14Z" />
                            </svg>
                            <span class="text-[#C2C2C2]">                              
                                @foreach ($profile->metroStations as $key => $metroStation)
                                <a href="{{ route('home', ['metro' => $metroStation->name]) }}"
                                    class="hover:text-[#6340FF] transition-colors">
                                    м. {{ $metroStation->name }}{{$key < count($profile->metroStations) - 1 ? ',' : '';}}</a>
                              @endforeach
                            </span>
                        </span>
                    </div>

                    <!-- Attributes -->
                    <div class="flex flex-wrap gap-3 mb-4">
                        <span class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-base text-white">{{$profile->weight}}
                            кг</span>
                        <span class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-base text-white">{{$profile->height}}
                            см</span>
                        <span class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-base text-white">{{$profile->size}}
                            размер</span>
                        <span class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-sm text-white">{{$profile->hair_color}}</span>
                        {{-- <span class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-sm text-white">Славянка</span> --}}
                        <span class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-sm text-white">{{$profile->tattoo}} тату</span>
                        {{-- <span class="bg-[#FFFFFF33] rounded-lg px-2 py-1 text-sm text-white">Интимная стрижка</span> --}}
                    </div>

                    <!-- Description -->
                    <p class="text-[#C2C2C2] mb-4">
                        {{$profile->description}}
                    </p>

                    <!-- Contact Info -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold mb-3">Контакты</h3>
                        <div class="flex items-center gap-4">
                            <!-- Phone -->
                            <a href="tel:{{$profile->phone}}"
                                class="flex items-center text-[#C2C2C2] hover:text-[#6340FF]">
                                @php
                                $phone = preg_replace('/\D/', '', $profile->phone);
                                $formatted = '+7 (' . substr($phone, 1, 3) . ') ' . substr($phone, 4, 3) . '-' . substr($phone, 7, 2) . '-' . substr($phone, 9, 2);
                            @endphp
                            {{ $formatted }}                            
                            </a>

                            @if ($profile->has_whatsapp)
                            <!-- WhatsApp -->
                            <a href="{{$profile->whatsapp}}" target="_blank"
                                class="flex gap-1 items-center text-[#C2C2C2] hover:text-[#25D366]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#25D366]" viewBox="0 0 32 32"
                                    fill="currentColor">
                                    <path
                                        d="M16.002 3C9.38 3 3.998 8.383 3.998 15.005c0 2.65.869 5.093 2.34 7.09L4 29l7.116-2.278A11.922 11.922 0 0 0 16 27c6.622 0 12.002-5.382 12.002-11.995C28.002 8.383 22.622 3 16.002 3zm.003 21.837c-1.954 0-3.897-.506-5.596-1.47l-.403-.237-3.36.985.976-3.275-.262-.417a10.328 10.328 0 0 1-1.62-5.598c0-5.724 4.655-10.378 10.382-10.378 5.723 0 10.375 4.654 10.375 10.378 0 5.726-4.652 10.37-10.375 10.37zm5.83-7.628c-.32-.16-1.89-.934-2.184-1.04-.293-.107-.506-.16-.72.16-.213.32-.826 1.04-1.014 1.254-.187.213-.373.24-.693.08-.32-.16-1.35-.498-2.57-1.59-.949-.844-1.59-1.882-1.775-2.2-.187-.32-.02-.494.14-.654.144-.143.32-.373.48-.56.16-.187.213-.32.32-.533.107-.213.054-.4-.027-.56-.08-.16-.72-1.74-.986-2.373-.26-.624-.524-.54-.72-.547l-.613-.01a1.177 1.177 0 0 0-.854.4c-.293.32-1.12 1.1-1.12 2.666 0 1.566 1.146 3.08 1.305 3.293.16.213 2.257 3.453 5.474 4.843.766.33 1.364.53 1.83.678.768.244 1.464.21 2.014.128.614-.092 1.89-.773 2.157-1.52.267-.747.267-1.387.187-1.52-.08-.133-.293-.213-.613-.373z" />
                                </svg>
                                WhatsApp
                            </a>                                
                            @endif

                            @if ($profile->has_telegram)
                            <!-- Telegram -->
                            <a href="{{$profile->telegram}}" target="_blank"
                                class="flex items-center text-[#C2C2C2] hover:text-[#0088cc]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#0088cc]"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M9.036 15.584l-.363 4.979c.519 0 .743-.222 1.02-.489l2.447-2.323 5.081 3.719c.933.514 1.595.243 1.822-.866L23.95 4.343c.269-1.192-.422-1.656-1.307-1.366L1.75 9.245C.606 9.636.617 10.296 1.522 10.57l5.597 1.745L18.273 5.82c.385-.253.735-.112.446.161z" />
                                </svg>
                                Telegram
                            </a>                                
                            @endif
                        </div>
                    </div>

                    <!-- Pricing Tables -->
                    <div class="grid grid-cols-1 md:bg-[#191919] md:mx-2 p-4  rounded-2xl md:grid-cols-2 gap-8">
                        <!-- Выезд Pricing -->
                        <div class="p-2">
                            <h3 class="text-xl font-semibold mb-4">Цена выезд</h3>
                            <div class="space-y-3">
                                <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                                    <span class="text-[#FFFFFFCC]">За один час</span>
                                    <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->vyezd_1hour)}}</span>
                                </div>
                                <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                                    <span class="text-[#FFFFFFCC]">За 2 часа</span>
                                    <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->vyezd_2hours)}}</span>
                                </div>
                                <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                                    <span class="text-[#FFFFFFCC]">За всю ночь</span>
                                    <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->vyezd_night)}}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Апартаменты Pricing -->
                        <div class="p-2">
                            <h3 class="text-xl font-semibold mb-4">Цена апартаменты</h3>
                            <div class="space-y-3">
                                <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                                    <span class="text-[#FFFFFFCC]">За один час</span>
                                    <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->appartamenti_1hour)}}</span>
                                </div>
                                <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                                    <span class="text-[#FFFFFFCC]">За 2 часа</span>
                                    <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->appartamenti_2hours)}}</span>
                                </div>
                                <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                                    <span class="text-[#FFFFFFCC]">За всю ночь</span>
                                    <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->appartamenti_night)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="text-[#636363] px-4 text-sm md:hidden">Была активна 01.02.2023</span>
            </div>
        </div>

        <p class="text-lg  lg:hidden font-semibold px-4 py-2 "> Обо мне </p>

        <!-- Attributes for small and medium screens -->
        <div class="hidden md:flex lg:hidden overflow-x-auto hide-scrollbar gap-3 px-4">
            <span class="bg-[#FFFFFF33] rounded-lg shrink-0 px-2 py-1.5 text-base text-white">{{$profile->weight}}
                кг</span>
            <span class="bg-[#FFFFFF33] rounded-lg shrink-0 px-2 py-1.5 text-base text-white">{{$profile->height}}
                см</span>
            <span class="bg-[#FFFFFF33] rounded-lg shrink-0 px-2 py-1.5 text-base text-white">{{$profile->size}}
                размер</span>
            <span class="bg-[#FFFFFF33] rounded-lg shrink-0 px-2 py-1.5 text-sm text-white">{{$profile->hair_color}}</span>
            {{-- <span class="bg-[#FFFFFF33] rounded-lg shrink-0 px-2 py-1.5 text-sm text-white">Славянка</span> --}}
            <span class="bg-[#FFFFFF33] rounded-lg shrink-0 px-2 py-1.5 text-sm text-white">{{$profile->tattoo}} тату</span>
            {{-- <span class="bg-[#FFFFFF33] rounded-lg shrink-0 px-2 py-1.5 text-sm text-white">Интимная стрижка</span> --}}
        </div>


        <!-- Profile Info for small and medium screens (shown below gallery) -->
        <div class="block md:block lg:hidden px-4 py-2 rounded-xl mb-2">

            <!-- Description -->
            <p class="text-[#C2C2C2] mb-3">
                {{$profile->description}}
            </p>


            <p class="text-lg font-semibold py-3">Район и метро</p>
            <div class="flex items-center mb-2">
                <span class="inline-flex gap-2 items-center mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" fill="none" stroke="#4059FF" stroke-width="2" />
                        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#4059FF"
                            font-family="Arial, sans-serif">M</text>
                    </svg>
                    <span class="text-[#C2C2C2]">
                      @foreach ($profile->neighborhoods as $key => $neighborhood)
                      <a href="{{ route('home', ['district' => $neighborhood->name]) }}"
                          class="hover:text-[#6340FF] transition-colors">
                          {{$neighborhood->name}}{{ $key < count($profile->neighborhoods) - 1 ? ',' : '' ;}}</a>
                    @endforeach
                </span>
            </div>
            <div class="flex items-center mb-6">
                <span class="inline-flex gap-2 items-center">
                    <svg class="w-5 h-5 text-[#920C0C] flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2C7.589 2 4 5.589 4 9.995C4 15.4 12 22 12 22C12 22 20 15.4 20 9.995C20 5.589 16.411 2 12 2ZM12 14C9.791 14 8 12.209 8 10C8 7.791 9.791 6 12 6C14.209 6 16 7.791 16 10C16 12.209 14.209 14 12 14Z" />
                    </svg>
                    <span class="text-[#C2C2C2]">                              
                        @foreach ($profile->metroStations as $key => $metroStation)
                        <a href="{{ route('home', ['metro' => $metroStation->name]) }}"
                            class="hover:text-[#6340FF] transition-colors">
                            м. {{ $metroStation->name }}{{$key < count($profile->metroStations) - 1 ? ',' : '';}}</a>
                      @endforeach
                    </span>
                </span>
            </div>

            <!-- Contact Info -->
            <div class="mb-4 mt-4">
                <h3 class="text-xl font-semibold mb-3">Контакты</h3>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <!-- Phone -->
                    <a href="tel:{{$profile->phone}}"
                        class="flex items-center text-[#C2C2C2] hover:text-[#6340FF]">
                        @php
                        $phone = preg_replace('/\D/', '', $profile->phone);
                        $formatted = '+7 (' . substr($phone, 1, 3) . ') ' . substr($phone, 4, 3) . '-' . substr($phone, 7, 2) . '-' . substr($phone, 9, 2);
                    @endphp
                    {{ $formatted }}                            
                    </a>

                    @if ($profile->has_whatsapp)
                    <!-- WhatsApp -->
                    <a href="{{$profile->whatsapp}}" target="_blank"
                        class="flex gap-1 items-center text-[#C2C2C2] hover:text-[#25D366]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#25D366]" viewBox="0 0 32 32"
                            fill="currentColor">
                            <path
                                d="M16.002 3C9.38 3 3.998 8.383 3.998 15.005c0 2.65.869 5.093 2.34 7.09L4 29l7.116-2.278A11.922 11.922 0 0 0 16 27c6.622 0 12.002-5.382 12.002-11.995C28.002 8.383 22.622 3 16.002 3zm.003 21.837c-1.954 0-3.897-.506-5.596-1.47l-.403-.237-3.36.985.976-3.275-.262-.417a10.328 10.328 0 0 1-1.62-5.598c0-5.724 4.655-10.378 10.382-10.378 5.723 0 10.375 4.654 10.375 10.378 0 5.726-4.652 10.37-10.375 10.37zm5.83-7.628c-.32-.16-1.89-.934-2.184-1.04-.293-.107-.506-.16-.72.16-.213.32-.826 1.04-1.014 1.254-.187.213-.373.24-.693.08-.32-.16-1.35-.498-2.57-1.59-.949-.844-1.59-1.882-1.775-2.2-.187-.32-.02-.494.14-.654.144-.143.32-.373.48-.56.16-.187.213-.32.32-.533.107-.213.054-.4-.027-.56-.08-.16-.72-1.74-.986-2.373-.26-.624-.524-.54-.72-.547l-.613-.01a1.177 1.177 0 0 0-.854.4c-.293.32-1.12 1.1-1.12 2.666 0 1.566 1.146 3.08 1.305 3.293.16.213 2.257 3.453 5.474 4.843.766.33 1.364.53 1.83.678.768.244 1.464.21 2.014.128.614-.092 1.89-.773 2.157-1.52.267-.747.267-1.387.187-1.52-.08-.133-.293-.213-.613-.373z" />
                        </svg>
                        WhatsApp
                    </a>                                
                    @endif

                    @if ($profile->has_telegram)
                    <!-- Telegram -->
                    <a href="{{$profile->telegram}}" target="_blank"
                        class="flex items-center text-[#C2C2C2] hover:text-[#0088cc]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#0088cc]"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9.036 15.584l-.363 4.979c.519 0 .743-.222 1.02-.489l2.447-2.323 5.081 3.719c.933.514 1.595.243 1.822-.866L23.95 4.343c.269-1.192-.422-1.656-1.307-1.366L1.75 9.245C.606 9.636.617 10.296 1.522 10.57l5.597 1.745L18.273 5.82c.385-.253.735-.112.446.161z" />
                        </svg>
                        Telegram
                    </a>                                
                    @endif
                </div>
            </div>
        </div>

        <!-- Pricing Tables -->
        <div class=" lg:hidden grid grid-cols-1 md:bg-[#191919] md:mx-2 p-4  rounded-2xl md:grid-cols-2 gap-8 mb-8">
            <!-- Выезд Pricing -->
            <div class="md:p-2">
                <h3 class="text-xl font-semibold mb-4">Цена выезд</h3>
                <div class="space-y-3">
                    <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                        <span class="text-[#FFFFFFCC]">За один час</span>
                        <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->vyezd_1hour)}}</span>
                    </div>
                    <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                        <span class="text-[#FFFFFFCC]">За 2 часа</span>
                        <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->vyezd_2hours)}}</span>
                    </div>
                    <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                        <span class="text-[#FFFFFFCC]">За всю ночь</span>
                        <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->vyezd_night)}}</span>
                    </div>
                </div>
            </div>

            <!-- Апартаменты Pricing -->
            <div class="md:p-2">
                <h3 class="text-xl font-semibold mb-4">Цена апартаменты</h3>
                <div class="space-y-3">
                    <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                        <span class="text-[#FFFFFFCC]">За один час</span>
                        <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->appartamenti_1hour)}}</span>
                    </div>
                    <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                        <span class="text-[#FFFFFFCC]">За 2 часа</span>
                        <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->appartamenti_2hours)}}</span>
                    </div>
                    <div class="flex border-b border-[#FFFFFF4D] justify-between items-center">
                        <span class="text-[#FFFFFFCC]">За всю ночь</span>
                        <span class="text-[#FFFFFFCC] font-semibold">{{round($profile->appartamenti_night)}}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warning Message -->
        <div class="mb-8 mx-3 text-center">
            <p class="bg-[#1B1B1B] rounded-2xl p-4 text-[#A0A0A0]">Если с вас требуют предоплату, страховку, залог или
                деньги, то это мошенники! Всегда!
                Без исключений!</p>
        </div>

        {{-- services and map section --}}
        <div class="flex flex-col px-4 lg:flex-row">
            <!-- Services Section -->
            <div class="flex lg:w-1/2 flex-col md:flex-row gap-8 mb-8">
                <div class="md:w-1/2">
                    <h3 class="text-2xl font-semibold mb-4">Базовые услуги</h3>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($profile->services as $service)
                        <div class="flex justify-between">
                            <span class="text-[#C2C2C2]">{{$service->name}}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="md:w-1/2">
                    <h3 class="text-2xl font-semibold mb-4">Дополнительные услуги</h3>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($profile->paidServices as $service)
                        <div class="flex">
                            <span class="text-[#C2C2C2]">{{$service->name}}</span>
                            <span class="text-[#6340FF] ml-3">+{{round($service->pivot->price)}} руб</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Map Location -->
            <div class="flex md:w-1/2 flex-col md:flex-row gap-8 mb-8">
                <div class="w-full">
                    <h3 class="text-2xl w-full font-semibold mb-4">Расположение на карте</h3>
                    <div class="rounded-xl overflow-hidden ">
                        <img src="{{asset('assets/images/map.png')}}" alt="Map Location" class="w-full h-full object-cover">
                    </div>
                </div>
                {{-- <div class="md:w-1/2">

                    <!-- This div is intentionally left empty to maintain the half-half layout -->
                    <!-- You can add additional information or content related to the location here -->
                </div> --}}
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mb-8 p-4">
            <h3 class="text-2xl font-semibold mb-4">Отзывы и вопросы</h3>

            <!-- Tabs -->
            <div class="flex space-x-4 mb-6">
                <button id="tab-comments" class="tab-button px-4 py-2 bg-[#6340FF] rounded-md text-white"
                    data-tab="comments">Вопросы и комментарии</button>
                <button id="tab-reviews"
                    class="tab-button px-4 py-2 bg-transparent border border-gray-700 rounded-md text-white"
                    data-tab="reviews">Отзывы реальных клиентов</button>
            </div>

            <!-- Comments Tab Content -->
            <div id="tab-content-comments" class="tab-content">
                <div class="mb-6">
                    <button class="flex items-center text-white">
                        Оставить свой вопрос
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Comments List -->
                <div class="space-y-6">
                    <!-- Comment 1 -->
                    <div class="bg-[#1A1A1A] rounded-xl p-6">
                        <div class="flex flex-col mb-2">
                            <div class="text-lg font-semibold">Андрей</div>
                            <span class="text-gray-400">23.05.2023</span>
                        </div>
                        <p class="text-gray-300">Привет, сейчас на ул. Плесецкая, на пару часов, один мужчина классика.
                            приедешь? Новый дом, все красиво.</p>
                    </div>

                    <!-- Comment 2 -->
                    <div class="bg-[#1A1A1A] rounded-xl p-6">
                        <div class="flex flex-col mb-2">
                            <h4 class="text-lg font-semibold">Владимир</h4>
                            <span class="text-gray-400">23.05.2023</span>
                        </div>
                        <p class="text-gray-300">Привет, сейчас на ул. Плесецкая, на пару часов, один мужчина классика.
                            приедешь? Новый дом, все красиво. Привет солнце сегодня возможно подъехать в гости?</p>
                    </div>

                    <!-- Comment 3 -->
                    <div class="bg-[#1A1A1A] rounded-xl p-6">
                        <div class="flex flex-col mb-2">
                            <h4 class="text-lg font-semibold">Андрей</h4>
                            <span class="text-gray-400">23.05.2023</span>
                        </div>
                        <p class="text-gray-300">Привет красотка сейчас свободно?</p>
                    </div>
                </div>

                <!-- Comment Form -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Добавить свой вопрос/комментарий</h3>
                    <form class="space-y-4">
                        <div>
                            <input type="text" placeholder="Введите имя"
                                class="w-full px-6 py-5 bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0 rounded-lg">
                        </div>
                        <div>
                            <textarea rows="5" placeholder="Текст вопроса/комментария"
                                class="w-full px-6 py-5 bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0 rounded-lg"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-6 py-3 bg-[#6340FF] w-full md:w-auto rounded-xl text-white font-medium">Отправить</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reviews Tab Content -->
            <div id="tab-content-reviews" class="tab-content hidden">
                <!-- Reviews List -->
                <div class="space-y-6">
                    <!-- Review 1 -->
                    <div class="bg-[#1A1A1A] rounded-xl p-6">
                        <div class="flex flex-col mb-2">
                            <div class="text-lg font-semibold">Максим</div>
                            <span class="text-gray-400">15.06.2023</span>
                        </div>
                        <div class="flex mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <p class="text-gray-300">Отличная девушка, все было на высшем уровне. Рекомендую!</p>
                    </div>

                    <!-- Review 2 -->
                    <div class="bg-[#1A1A1A] rounded-xl p-6">
                        <div class="flex flex-col mb-2">
                            <h4 class="text-lg font-semibold">Алексей</h4>
                            <span class="text-gray-400">10.06.2023</span>
                        </div>
                        <div class="flex mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <p class="text-gray-300">Очень приятная девушка, все было хорошо. Приеду еще.</p>
                    </div>
                </div>

                <!-- Review Form -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Оставить отзыв</h3>
                    <form class="space-y-4">
                        <div>
                            <input type="text" placeholder="Введите имя"
                                class="w-full px-6 py-5 bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label class="block text-white mb-2">Ваша оценка</label>
                            <div class="flex space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-gray-400 cursor-pointer hover:text-yellow-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-gray-400 cursor-pointer hover:text-yellow-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-gray-400 cursor-pointer hover:text-yellow-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-gray-400 cursor-pointer hover:text-yellow-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-gray-400 cursor-pointer hover:text-yellow-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <textarea rows="5" placeholder="Текст отзыва"
                                class="w-full px-6 py-5 bg-[#191919] border-0 text-white placeholder-[#FFFFFF66] focus:ring-0 rounded-lg"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-6 py-3 bg-[#6340FF] w-full md:w-auto rounded-xl text-white font-medium">Отправить
                                отзыв</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Similar Profiles -->
        <div class="p-4">
            <h3 class="text-xl font-semibold mb-6">Похожие проститутки</h3>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JavaScript for handling carousel and video display -->
    <script>
        // Track current slide index
        let currentSlide = 0;
        const totalSlides = document.querySelectorAll('.carousel-slide').length;
        const hasVideo = document.querySelector('.video-slide') !== null;
        let isVideoActive = false;
        let touchStartX = 0;
        let touchEndX = 0;

        // Function to show a specific slide
        function showSlide(index) {
            // Reset video active state
            isVideoActive = false;

            // Hide all slides
            const slides = document.querySelectorAll('.carousel-slide');
            slides.forEach(slide => {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            });

            @if (isset($profile->video->path))
                  // Hide video if it's playing
            const videoSlide = document.querySelector('.video-slide');
            videoSlide.classList.remove('opacity-100');
            videoSlide.classList.add('opacity-0');
            const video = videoSlide.querySelector('video');
            if (video) video.pause();
            @endif

          

            // Show the selected slide
            if (index >= 0 && index < slides.length) {
                slides[index].classList.remove('opacity-0');
                slides[index].classList.add('opacity-100');
                currentSlide = index;
            }

            // Update mobile indicators
            updateIndicators();
        }

        // Function to show next slide
        function nextSlide() {
            if (isVideoActive) {
                // If video is active, go to first slide
                isVideoActive = false;
                currentSlide = 0;
                showSlide(currentSlide);
            } else if (currentSlide === totalSlides - 1) {
               @if (isset($profile->video->path))
 // If we're at the last image slide, show video
 showVideo();
               @endif
            } else {
                // Otherwise, go to next slide
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }
        }

        // Function to show previous slide
        function prevSlide() {
            if (isVideoActive) {
                // If video is active, go to last image slide
                isVideoActive = false;
                currentSlide = totalSlides - 1;
                showSlide(currentSlide);
            } else if (currentSlide === 0) {
                @if (isset($profile->video->path))
                // If we're at the first slide, show video
                showVideo();
                @endif
            } else {
                // Otherwise, go to previous slide
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
            }
        }

        @if (isset($profile->video->path))
        // Function to show video
        function showVideo() {
            // Hide all image slides
            const slides = document.querySelectorAll('.carousel-slide');
            slides.forEach(slide => {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            });

            // Show video slide
            const videoSlide = document.querySelector('.video-slide');
            videoSlide.classList.remove('opacity-0');
            videoSlide.classList.add('opacity-100');

            // Mark video as active
            isVideoActive = true;

            // Set video source if not already set and play
            const video = videoSlide.querySelector('video');
            if (video) {
                // The source is already set in the HTML
                video.play();
            }

            // Update mobile indicators
            updateIndicators(true);
        }            
        @endif

        // Update mobile indicators
        function updateIndicators(isVideo = false) {
            const indicators = document.querySelectorAll('.bottom-4 button');
            indicators.forEach((indicator, index) => {
                if ((isVideo && index === indicators.length - 1) || (!isVideo && index === currentSlide)) {
                    indicator.classList.remove('opacity-50');
                    indicator.classList.add('opacity-100');
                } else {
                    indicator.classList.remove('opacity-100');
                    indicator.classList.add('opacity-50');
                }
            });
        }

        // Initialize the carousel and touch events
        document.addEventListener('DOMContentLoaded', function () {
            showSlide(0);

            // Add touch event listeners for swipe functionality
            const carouselContainer = document.querySelector('.carousel-container');

            carouselContainer.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
            }, false);

            carouselContainer.addEventListener('touchend', function (e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, false);

            // Add click event to open fullscreen when clicking on the main image or video
            carouselContainer.addEventListener('click', function (e) {
                if (e.target.closest('button')) {
                    return; // Don't do anything if clicking on a button
                }

                if (isVideoActive) {
                    const video = document.querySelector('.video-slide video');
                    if (video) {
                        openVideoFullscreen(video);
                    }
                } else {
                    const currentImage = document.querySelectorAll('.carousel-slide')[currentSlide].querySelector('img');
                    if (currentImage) {
                        openFullscreen(currentImage.src);
                    }
                }
            });
        });

        // Handle swipe gesture
        function handleSwipe() {
            if (touchEndX < touchStartX - 50) { // Swipe left (next)
                nextSlide();
            }
            if (touchEndX > touchStartX + 50) { // Swipe right (previous)
                prevSlide();
            }
        }

        // Open image in fullscreen
        function openFullscreen(imageSrc) {
            const fullscreenOverlay = document.createElement('div');
            fullscreenOverlay.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center';

            const fullscreenImage = document.createElement('img');
            fullscreenImage.src = imageSrc;
            fullscreenImage.className = 'max-h-screen max-w-screen-lg object-contain';

            const closeButton = document.createElement('button');
            closeButton.className = 'absolute top-4 right-4 text-white text-2xl';
            closeButton.innerHTML = '×';
            closeButton.onclick = function () {
                document.body.removeChild(fullscreenOverlay);
            };

            fullscreenOverlay.appendChild(fullscreenImage);
            fullscreenOverlay.appendChild(closeButton);
            fullscreenOverlay.onclick = function (e) {
                if (e.target === fullscreenOverlay) {
                    document.body.removeChild(fullscreenOverlay);
                }
            };

            document.body.appendChild(fullscreenOverlay);
        }

        // Open video in fullscreen
        function openVideoFullscreen(videoElement) {
            const fullscreenOverlay = document.createElement('div');
            fullscreenOverlay.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center';

            const fullscreenVideo = document.createElement('video');
            fullscreenVideo.className = 'max-h-screen max-w-screen-lg object-contain';
            fullscreenVideo.controls = true;
            fullscreenVideo.autoplay = true;

            // Copy the source from the original video
            const originalSource = videoElement.querySelector('source');
            if (originalSource) {
                const newSource = document.createElement('source');
                newSource.src = originalSource.src;
                newSource.type = originalSource.type;
                fullscreenVideo.appendChild(newSource);
            } else if (videoElement.src) {
                fullscreenVideo.src = videoElement.src;
            }

            const closeButton = document.createElement('button');
            closeButton.className = 'absolute top-4 right-4 text-white text-2xl';
            closeButton.innerHTML = '×';
            closeButton.onclick = function () {
                // Pause the video before removing
                fullscreenVideo.pause();
                document.body.removeChild(fullscreenOverlay);
            };

            fullscreenOverlay.appendChild(fullscreenVideo);
            fullscreenOverlay.appendChild(closeButton);
            fullscreenOverlay.onclick = function (e) {
                if (e.target === fullscreenOverlay) {
                    // Pause the video before removing
                    fullscreenVideo.pause();
                    document.body.removeChild(fullscreenOverlay);
                }
            };

            document.body.appendChild(fullscreenOverlay);
        }
    </script>
    <!-- Tab Functionality Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get tab elements
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            // Add click event to tab buttons
            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('bg-[#6340FF]');
                        btn.classList.add('bg-transparent', 'border', 'border-gray-700');
                    });

                    // Add active class to clicked button
                    this.classList.remove('bg-transparent', 'border', 'border-gray-700');
                    this.classList.add('bg-[#6340FF]');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show the corresponding tab content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById('tab-content-' + tabId).classList.remove('hidden');
                });
            });


            // Star rating functionality for reviews
            const stars = document.querySelectorAll('#tab-content-reviews .flex.space-x-2 svg');
            stars.forEach((star, index) => {
                star.addEventListener('click', function () {
                    // Reset all stars
                    stars.forEach(s => {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-400');
                    });

                    // Fill stars up to the clicked one
                    for (let i = 0; i <= index; i++) {
                        stars[i].classList.remove('text-gray-400');
                        stars[i].classList.add('text-yellow-400');
                    }
                });

                // Hover effects
                star.addEventListener('mouseenter', function () {
                    for (let i = 0; i <= index; i++) {
                        stars[i].classList.add('text-yellow-400');
                        stars[i].classList.remove('text-gray-400');
                    }
                });

                star.addEventListener('mouseleave', function () {
                    stars.forEach(s => {
                        if (!s.classList.contains('selected')) {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-400');
                        }
                    });
                });
            });
        });
    </script>

@endpush