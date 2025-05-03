@extends('emails.custom-layout')

@section('content')
    <h2>{{ $greeting ?? 'Здравствуйте!' }}</h2>
    
    <div style="margin-top: 20px;">
        @foreach ($introLines as $line)
            <p>{{ $line }}</p>
        @endforeach
    </div>

    @isset($actionText)
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $actionUrl }}" class="btn" target="_blank">{{ $actionText }}</a>
        </div>
    @endisset

    <div>
        @foreach ($outroLines as $line)
            <p>{{ $line }}</p>
        @endforeach
    </div>

    <div style="margin-top: 25px;">
        <p>С уважением,<br>{{ env('MAIL_FROM_ADDRESS', 'hello@example.com') }}</p>
    </div>

    @isset($actionText)
        <div class="divider"></div>
        <div style="color: #8B8B8B; font-size: 13px; margin-top: 15px;">
            Если у вас возникли проблемы с нажатием кнопки "{{ $actionText }}", скопируйте и вставьте URL-адрес ниже в ваш веб-браузер:
            <br>
            <a href="{{ $actionUrl }}" style="word-break: break-all;">{{ $actionUrl }}</a>
        </div>
    @endisset
@endsection