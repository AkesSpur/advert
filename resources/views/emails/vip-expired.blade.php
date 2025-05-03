@extends('emails.custom-layout')

@section('content')
    <h2>Срок действия VIP статуса истек</h2>
    
    <div style="margin-top: 20px;">
        <p>Уведомляем вас, что срок действия VIP статуса для вашего профиля "{{ $profile->name }}" истек.</p>
        <p>Ваше объявление теперь отображается в обычном порядке.</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/user/traffis') }}" class="btn" target="_blank">Продлить VIP статус</a>
    </div>

    <div>
        <p>Спасибо за использование нашего сервиса!</p>
    </div>

    <div style="margin-top: 25px;">
        <p>С уважением,<br>{{ env('MAIL_FROM_ADDRESS', 'hello@example.com') }}</p>
    </div>
@endsection