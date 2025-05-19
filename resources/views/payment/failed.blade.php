@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-gray-800 shadow-md rounded-lg p-6 text-center">
        <h1 class="text-3xl font-bold text-red-400 mb-4">Ошибка платежа</h1>
        @if(session('error'))
            <p class="text-gray-300 mb-6">{{ session('error') }}</p>
        @else
            <p class="text-gray-300 mb-6">К сожалению, ваш платеж не прошел или был отменен. Пожалуйста, попробуйте еще раз или свяжитесь с поддержкой.</p>
        @endif
        <svg class="w-16 h-16 text-red-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <a href="{{ route('user.transaction.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300">
            Перейти к транзакциям
        </a>
        <a href="{{ route('home') }}" class="ml-4 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-300">
            Вернуться на главную
        </a>
    </div>
</div>
@endsection