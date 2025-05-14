<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Информация о профиле') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Обновите информацию о профиле и адрес электронной почты своей учетной записи.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('user.settings.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Имя')" class="text-white font-medium mb-1" />
            <x-text-input id="name" name="name" type="text" class="block w-full px-4 py-3 rounded-xl bg-[#191919]  text-white placeholder-gray-500 focus:ring-0" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Электронная почта')" class="text-white font-medium mb-1" />
            <x-text-input id="email" name="email" type="email" class="block w-full px-4 py-3 rounded-xl bg-[#191919]  text-white placeholder-gray-500 focus:ring-0" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-white font-medium mb-1">
                        {{ __('Ваш адрес электронной почты непроверен.') }}

                        <button form="send-verification" class="underline text-sm text-gray-400 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Нажмите здесь, чтобы повторно отправить письмо для проверки.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-400">
                            {{ __('На ваш адрес электронной почты отправлена новая ссылка для проверки.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-md text-sm transition">{{ __('Сохранить') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-400"
                >{{ __('Сохранено.') }}</p>
            @endif
        </div>
    </form>
</section>
