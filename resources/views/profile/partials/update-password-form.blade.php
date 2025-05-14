<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Обновить пароль') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('Убедитесь, что ваша учетная запись использует длинный и случайный пароль, чтобы оставаться в безопасности.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Текущий пароль')" class="text-white mb-1 font-medium" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-gray-300 text-white placeholder-gray-500 focus:ring-0" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Новый пароль')" class="text-white mb-1 font-medium" />
            <x-text-input id="update_password_password" name="password" type="password" class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-gray-300 text-white placeholder-gray-500 focus:ring-0" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Подтвердите пароль')" class="text-white mb-1 font-medium" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-gray-300 text-white placeholder-gray-500 focus:ring-0" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-md text-sm transition">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
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
