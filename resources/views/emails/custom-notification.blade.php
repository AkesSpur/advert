@extends('emails.custom-layout')

@section('hero_text')
    {{ $title ?? 'Уведомление' }}
@endsection

@section('content')
    <h2>{{ $greeting ?? 'Здравствуйте!' }}</h2>
    
    <div style=" color: white; margin-top: 20px;">
        @foreach ($introLines as $line)
            <p>{{ $line }}</p>
        @endforeach
    </div>

    @isset($actionText)
        <div style="color: white; text-align: center; margin: 30px 0;">
            <a href="{{ $actionUrl }}" class="btn" target="_blank">{{ $actionText }}</a>
        </div>
    @endisset

    <div style="color: white; ">
        @foreach ($outroLines as $line)
            <p>{{ $line }}</p>
        @endforeach
    </div>

    <div style="color: white; margin-top: 25px;">
        <p>С уважением,<br>{{ env('MAIL_FROM_ADDRESS', 'hello@example.com') }}</p>
    </div>

    @isset($actionText)
        <div class="divider"></div>
        <div style="color: white; font-size: 13px; margin-top: 15px;">
            Если у вас возникли проблемы с нажатием кнопки "{{ $actionText }}", скопируйте и вставьте URL-адрес ниже в ваш веб-браузер:
            <br>
            <a href="{{ $actionUrl }}" style="word-break: break-all;">{{ $actionUrl }}</a>
        </div>
    @endisset
@endsection