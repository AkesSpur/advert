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
            background-color: #121212;
            color: #ffffff;
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            text-size-adjust: none;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-header {
            text-align: center;
            padding: 20px 0;
        }
        .email-body {
            background-color: #191919;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
        }
        .email-footer {
            text-align: center;
            padding: 20px 0;
            color: white;
            font-size: 14px;
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
            .email-wrapper {
                padding: 10px;
            }
            .email-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>{{ __('Important Notification') }}</h1>
        </div>
        
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