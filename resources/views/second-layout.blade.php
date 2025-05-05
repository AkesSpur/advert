<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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

<body class="font-sans antialiased text-white min-h-screen overflow-x-hidden">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-[#000000] pb-2">
            
            <div class="max-w-screen-2xl  mx-auto px-6 py-5 flex items-center justify-between">
                <a href="/" class="text-xl font-bold">Logo</a>
            <h2 class="hidden lg:block text-3xl text-white text-center">Escort услуги в Санкт-Петербурге</h2>     

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
                        <a href="{{ route('user.profiles.index') }}"
                           class="px-4 py-2 bg-transparent text-white border border-white-700 rounded-lg text-sm">
                            Мой кабинет
                        </a>
                    @endguest
                </div>
                
            </div>

            <h2 class=" lg:hidden text-3xl max-w-screen-2xl px-6 mx-auto pb-4 mb-2 text-white text-center">Escort услуги в Санкт-Петербурге</h2>     
        </header>

        <!-- Page Content -->
        <main class="">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="pb-6">
            <div class="max-w-screen-2xl mx-auto px-6">
                <div class="flex flex-col sm:flex-row border-t border-[#363636] pt-6 justify-between items-center ">
                    <div class="mb-4 sm:mb-0">
                        <a href="/" class="text-xl font-bold">Logo</a>
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
                        <a href="{{ route('user.profiles.index') }}"
                           class="px-4 py-2 bg-transparent text-white border border-white-700 rounded-lg text-sm">
                            Мой кабинет
                        </a>
                    @endguest
                    </div>
                </div>
            </div>
        </footer>
    </div>
    @if (session('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 4000)" 
        x-show="show"
        x-transition 
        class="fixed bottom-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
    >
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
<div 
    x-data="{ show: true }" 
    x-init="setTimeout(() => show = false, 4000)" 
    x-show="show"
    x-transition 
    class="fixed bottom-5 right-5 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
>
    {{ session('error') }}
</div>
@endif

    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>

</html>