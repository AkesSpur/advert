<x-guest-layout>
    <h2 class="text-2xl font-bold text-white text-center mb-6">Подтверждение email</h2>

    <div class="mb-4 text-sm text-gray-300">
        {{ __('Спасибо за регистрацию! Прежде чем начать, не могли бы вы подтвердить свой адрес электронной почты, перейдя по ссылке, которую мы только что отправили вам по электронной почте? Если вы не получили электронное письмо, мы с радостью отправим вам другое.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-400">
            {{ __('Новая ссылка для подтверждения была отправлена на адрес электронной почты, указанный при регистрации.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-col space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 auth-button">
                    {{ __('Отправить письмо повторно') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-gray-700 rounded-md shadow-sm text-sm font-medium text-white bg-transparent hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                {{ __('Выйти') }}
            </button>
        </form>
    </div>
</x-guest-layout>
