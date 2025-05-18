<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $seoTitle ?? config('app.name', 'Laravel') }}</title>
    <meta name="description" content="{{ $seoMetaDescription ?? $settings->default_seo_description }}">

    <link rel="icon" type="image/png" href="{{asset($logoSetting->favicon ?? '' )}}">

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
            <h2 class="hidden lg:block text-3xl text-white text-center">{{$seoH1 ?? 'Проститутки услуги в Санкт-Петербурге'}}</h2>     

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
                            <span id="likes-count" class="absolute -top-1 -right-1 bg-[#6340FF] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ Auth::user()->likedProfiles()->count() }}
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

            <h2 class=" lg:hidden text-3xl max-w-screen-2xl px-6 mx-auto pb-4 mb-2 text-white text-center">{{$seoH1 ?? 'Проститутки услуги в Санкт-Петербурге'}}</h2>     
        </header>

        <!-- Page Content -->
        <main class="">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="pb-6 mt-auto">
            <div class="max-w-screen-2xl mx-auto px-6">
                <div class="flex  border-t border-[#363636] pt-6 justify-between items-center ">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
    <script>
        $(document).ready(function() {
            $('.like-button').click(function() {
                const profileId = $(this).data('profile-id');
                const button = $(this);
                @auth
                $.ajax({
                    url: `/user/profile/${profileId}/toggle-like`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Update button appearance
                        if (response.status === 'liked') {
                            // button.addClass('bg-red-500');
                            button.find('svg').attr('fill', 'red');
                            button.find('svg').attr('stroke', 'none');

                            // Increment counter
                            const counter = $('#likes-count');
                            counter.text(parseInt(counter.text()) + 1);
                        } else {
                            button.removeClass('bg-red-500');
                            button.find('svg').attr('fill', 'none');
                            button.find('svg').attr('stroke', 'white');
                            button.find('svg').attr('stroke-width', '2');
                            // Decrement counter
                            const counter = $('#likes-count');
                            counter.text(parseInt(counter.text()) - 1);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error toggling like:', xhr.responseText);
                    }
                });

                @else
    // User is not logged in, redirect to login
    window.location.href = '{{ route("login") }}';
        @endauth
            });
        });
    </script>
    @stack('scripts')
</body>

</html>