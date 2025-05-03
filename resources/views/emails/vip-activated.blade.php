@extends('emails.custom-layout')

@section('content')
    <h2>Ваш профиль активирован как VIP!</h2>
    
    <div style="margin-top: 20px;">
        <p>Поздравляем! Ваш профиль "{{ $profile->name }}" теперь имеет статус VIP.</p>
        <p>Ваше объявление будет отображаться в приоритетном порядке.</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/profiles/' . $profile->id) }}" class="btn" target="_blank">Посмотреть профиль</a>
    </div>

    <div>
        <p>Спасибо за использование нашего сервиса!</p>
    </div>

    <div style="margin-top: 25px;">
        <p>С уважением,<br>{{ config('app.name') }}</p>
    </div>
@endsection