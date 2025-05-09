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
        
            /* in your global CSS or <style> */
            table {
                border-collapse: collapse;
                overflow: hidden;
                border-radius: 0.75rem;
                /* Tailwind’s rounded-xl = 0.75rem */
            }
        
            table thead th:first-child {
                border-top-left-radius: 0.75rem;
                border-bottom-left-radius: 0.75rem;
            }
        
            table thead th:last-child {
                border-top-right-radius: 0.75rem;
            }

        </style>
    </head>
    <body class="font-sans antialiased text-white min-h-screen overflow-x-hidden">
        <div class="flex flex-col min-h-screen">
            <!-- Header -->
            <header class="">
                <div class="max-w-screen-2xl  mx-auto px-6 py-6 flex items-center justify-between">
                    <a href="/" class="text-xl font-bold">Logo</a>
                    <div class="flex items-center gap-4">
                        <div class="text-white">
                            <span>Баланс: </span>
                            <span class="font-medium">{{Auth::user()->balance}} ₽</span>
                            <a href="#" class="text-white underline ml-2 text-sm">Пополнить</a>
                        </div>
                    </div>
                </div>
            </header>
            
<main class="flex-grow">
    <div class="max-w-screen-2xl mx-auto px-6 py-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Sidebar Menu -->
            <div class="hidden lg:block w-full max-w-[220px] shrink-0">
                <div class="bg-[#191919] rounded-2xl overflow-hidden p-4">
                    <a href="{{route('user.profiles.index')}}" class="block w-full rounded-2xl text-md font-semibold mb-2 px-4 py-3 {{isActiveRoute('user.profiles.index')}}">
                        Анкеты
                    </a>
                    <a href="{{route('user.chat.index')}}" class="block w-full rounded-2xl mb-2 text-md font-semibold px-4 py-3 {{isActiveRoute('user.chat.index')}}">
                        Сообщения
                        @php
                        use App\Models\ChatMessage;
                    
                        $unreadUserMessages = ChatMessage::whereHas('conversation', function ($q) {
                            $q->where('user_id', auth()->id());
                        })
                        ->unread() // uses whereNull('read_at')
                        ->where('sender_id', '!=', auth()->id())
                        ->count();
                    @endphp
                    
                        @if($unreadUserMessages > 0)
                            <span class="inline-flex items-center justify-center ml-2 w-5 h-5 text-xs font-semibold rounded-full bg-[#6340FF] text-white">{{ $unreadUserMessages }}</span>
                        @endif
                    </a>
                    <a href="{{route('user.transaction.index')}}" class="block w-full rounded-2xl mb-2 text-md font-semibold px-4 py-3 {{isActiveRoute('user.transaction.index')}}">
                        Транзакции
                    </a>
                    <a href="{{route('user.advert.index')}}" class="block w-full rounded-2xl mb-2 text-md font-semibold px-4 py-3 {{isActiveRoute('user.advert.index')}}">
                        Реклама
                    </a>

                    <div class="border-t border-gray-800 mt-20 pt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                        <button href="{{route('logout')}}" class="block w-full text-start rounded-2xl mb-2 text-md font-semibold px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition">
                            Выйти
                        </button>
                        </form>
                        <a href="#" class="block w-full rounded-2xl mb-2 text-md font-semibold px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition">
                            Настройки
                        </a>
                        <a href="/" class="block w-full rounded-2xl mb-2 text-md font-semibold px-4 py-3 text-gray-400 hover:bg-red-800  hover:text-white transition">
                            Удалить аккаунт
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-grow rounded-2xl ">
                {{ $slot }}
            </div>
        </div>
    </div>
</main>

            
            <!-- Mobile Menu -->
            <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-[#191919] border-t border-gray-800 z-50">
                <div class="flex justify-around">
                    <a href="{{route('user.profiles.index')}}" class="flex flex-col items-center p-3 {{isActiveTab('user.profiles.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-xs mt-1">Анкеты</span>
                    </a>
                    <a href="{{route('user.chat.index')}}" class="flex flex-col items-center p-3 {{isActiveTab('user.chat.index')}}">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            @if($unreadUserMessages > 0)
                                <span class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-xs font-bold rounded-full bg-[#6340FF] text-white">{{ $unreadUserMessages }}</span>
                            @endif
                        </div>
                        <span class="text-xs mt-1">Сообщения</span>
                    </a>
                    <a href="{{route('user.transaction.index')}}" class="flex flex-col items-center p-3 {{isActiveTab('user.transaction.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="text-xs mt-1">Транзакции</span>
                    </a>
                    <a href="{{route('user.advert.index')}}" class="flex flex-col items-center p-3 {{isActiveTab('user.advert.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        <span class="text-xs mt-1">Реклама</span>
                    </a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex flex-col items-center p-3 text-gray-400 hover:text-white focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-xs mt-1">Еще</span>
                        </button>
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute bottom-full mb-2 w-48 rounded-lg bg-[#191919] shadow-lg border border-[#2B2B2B] py-2 right-0">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white transition">
                                Настройки
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full block px-4 py-2 text-sm text-start text-gray-400 hover:bg-gray-800 hover:text-white transition">
                                    Выйти
                                </button>
                            </form>            
                            <a href="#" class="block px-4 py-2 text-sm text-gray-400 hover:bg-red-800 hover:text-white transition">
                                Удалить аккаунт
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
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
                        <a href="{{ route('user.profile.likedProfiles') }}" class="relative p-2 mr-2 hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12.1 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54l-1.35 1.31z" />
                            </svg>
                            <span class="absolute -top-1 -right-1 bg-[#6340FF] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ Auth::user()->likedProfiles()->count() }}
                            </span>
                        </a>
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
         @stack('scripts')
         <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </body>
</html>
