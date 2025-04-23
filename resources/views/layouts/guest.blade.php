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
                background-color: #000000;
            }
            
            @media (min-width: 640px) {
                body {
                    background-image: linear-gradient(rgba(0, 0, 0, 0.572), rgba(0, 0, 0, 0.5)), url('{{asset('assets/images/login.jpg')}}');
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    background-attachment: fixed;
                }
            }
            
            .auth-card {
                background-color: #000000;
                border-radius: 0;
                box-shadow: none;
                min-height: 100vh;
                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            @media (min-width: 640px) {
                .auth-card {
                    min-height: auto;
                    background-color: #121212;
                    border-radius: 8px;
                    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
                }
            }
            
            .auth-input {
                background-color: #191919;
                color: #ffffff;
            }
            
            .auth-label {
                color: #e5e5e5;
            }
            
            .auth-link {
                color: #6340FF;
            }
            
            .auth-button {
                background-color: #6340FF;
                transition: all 0.2s ease;
            }
            
            .auth-button:hover {
                background-color: #5737e7;
            }
            
            .close-button {
                position: absolute;
                top: 20px;
                right: 20px;
                color: #fff;
                transition: color 0.2s ease;
                z-index: 10;
            }
            
            .close-button:hover {
                color: #ccc;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center">
            <div class="w-full sm:max-w-md px-6 py-10 auth-card relative">
                <a href="/" class="close-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
