@php
$logoSetting = app(App\Models\LogoSetting::class)->first();
$generalSettings = app(App\Models\GeneralSetting::class)->first();
$seoTitle = $__env->yieldContent('title') . ' - ' . ($generalSettings->site_name ?? config('app.name', 'Laravel'));
$seoMetaDescription = $__env->yieldContent('message'); // Or a more generic error description
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoMetaDescription }}">

    @if($logoSetting && $logoSetting->favicon)
    <link rel="icon" type="image/png" href="{{ asset($logoSetting->favicon) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        body {
            background-color: #121212;
        }

       
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #353535;
        border-radius: 10px;
        border: 2px solid #222222;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #222222;
    }

    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #353535 #222222;
    }


        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="font-sans antialiased text-white min-h-screen overflow-x-hidden custom-scrollbar">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-[#000000] pb-2">
            
            <div class="max-w-screen-2xl mx-auto px-6 py-5 flex items-center justify-between">
                <a href="/" class="inline-block">
                    @if ($logoSetting && $logoSetting->logo)
                    <img src="{{ asset($logoSetting->logo) }}" 
                         alt="Logo" 
                         class="h-12 w-auto object-contain"
                         loading="lazy">
                    @else
                    <span class="text-xl font-semibold">{{ $generalSettings->site_name ?? config('app.name', 'Laravel') }}</span>
                    @endif
                </a>
                <h2 class="hidden lg:block text-3xl text-white text-center">{{$generalSettings->default_h1_heading}}</h2>     

                <div class="flex space-x-1 md:space-x-4">
                    @guest
                    <a href="{{ route('profile.likedProfiles') }}" class="relative p-2 hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12.1 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54l-1.35 1.31z" />
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-[#6340FF] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            <span>{{ count(session('liked_profiles', [])) }}</span>
                        </span>
                    </a>
                        <a href="{{ route('register') }}"
                           class="px-2 py-2 bg-transparent text-white border border-white-700 rounded-lg text-sm">
                            Добавить анкету
                        </a>
                        <a href="{{ route('login') }}"
                           class="px-1 py-2 bg-transparent text-white rounded-md text-sm">
                            Войти
                        </a>
                    @else
                    <a href="{{ route('profile.likedProfiles') }}" class="relative p-2 mr-2 hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12.1 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54l-1.35 1.31z" />
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-[#6340FF] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            <span>{{ Auth::user()->likedProfiles()->count() }}</span>
                        </span>
                    </a>
                        @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="relative p-2 mr-2 hover:scale-110 transition-transform">
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
            <h2 class="lg:hidden text-3xl max-w-screen-2xl px-6 mx-auto pb-4 mb-2 text-white text-center">{{$generalSettings->default_h1_heading}}</h2>     
        </header>

        <!-- Page Content -->
        <main class="flex-grow ">
            <div class="relative flex items-top justify-center min-h-[calc(100vh-150px)] sm:items-center sm:pt-0">
                <div class="max-w-xl mx-auto sm:px-6 lg:px-8 text-center">
                    <div class="flex flex-col items-center justify-center pt-8 sm:pt-0">
                        <div class="px-4 text-5xl text-gray-200 border-r border-gray-500 tracking-wider">
                            @yield('code')
                        </div>
                        <div class="ml-4 text-2xl text-gray-300 uppercase tracking-wider mt-4">
                            @yield('message')
                        </div>
                        <a href="/" class="mt-8 px-6 py-3 bg-[#6340FF] text-white rounded-lg hover:bg-opacity-80 transition-colors">
                            Вернуться на главную
                        </a>
                    </div>
                </div>
            </div>
        </main>

    </div>

    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
