@extends('emails.custom-layout')

@section('content')
    <h2>Недостаточно средств на балансе</h2>
    
    <div style="margin-top: 20px;">
        <p>Уведомляем вас, что на вашем балансе недостаточно средств для списания ежедневной платы.</p>
        <p>Ваш профиль "{{ $profile->name }}" был приостановлен.</p>
        <p>Требуемая сумма для возобновления: <strong>{{ $requiredAmount }} руб.</strong></p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/user/profiles') }}" class="btn" target="_blank">Пополнить баланс</a>
    </div>

    <div>
        <p>После пополнения баланса вы сможете возобновить работу профиля.</p>
    </div>

    <div style="margin-top: 25px;">
        <p>С уважением,<br>{{ env('MAIL_FROM_ADDRESS', 'hello@example.com') }}</p>
    </div>
@endsection