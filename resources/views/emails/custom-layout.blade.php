<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ __('Notification') }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Figtree', sans-serif;
            -webkit-text-size-adjust: none;
            text-size-adjust: none;
            -webkit-font-smoothing: antialiased;
            color: #ffffff;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #121212;
        }
        .email-header {
            text-align: center;
            padding: 25px 0;
        }
        .email-hero {
            position: relative;
            height: 200px;
            background-image: url('{{ asset('assets/images/login.jpg') }}');
            background-size: cover;
            background-position: center;
            border-radius: 0;
        }
        .email-hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .email-hero-text {
            color: #ffffff;
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        .email-body {
            background-color: #191919;
            border-radius: 16px;
            padding: 30px;
            margin: 20px;
        }
        .email-footer {
            text-align: center;
            padding: 20px;
            color: #8B8B8B;
            font-size: 14px;
            background-color: #121212;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #ffffff;
            margin-top: 0;
        }
        p {
            margin: 0 0 15px;
            line-height: 1.5;
        }
        a {
            color: #6340FF;
            text-decoration: none;
        }
        .btn {
            display: inline-block;
            background-color: #6340FF;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 15px 0;
        }
        .btn:hover {
            background-color: #5030EF;
        }
        .divider {
            border-top: 1px solid #333;
            margin: 20px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 20px;
                margin: 10px;
            }
            .email-hero {
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        
        {{-- <div class="email-hero">
            <div class="email-hero-overlay">
                <div class="email-hero-text">@yield('hero_text', 'Уведомление')</div>
            </div>
        </div> --}}
        
        <div class="email-body">
            @yield('content')
        </div>
        
        <div class="email-footer">
            <p>© {{ date('Y') }}. {{ __('All rights reserved') }}.</p>
            <p>{{ __('If you did not request this email, no action is required') }}.</p>
        </div>
    </div>
</body>
</html>