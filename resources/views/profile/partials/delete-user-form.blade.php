<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Удалить аккаунт') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('После удаления вашей учетной записи все ее ресурсы и данные будут безвозвратно удалены. Перед удалением учетной записи загрузите все данные и информацию, которые вы хотите сохранить.') }}
        </p>
    </header>

    <button type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm transition"
    >{{ __('Удалить аккаунт') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-6 bg-[#1E1E1E] rounded-lg">
            <form method="post" action="{{ route('user.settings.destroy') }}" class="p-0">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-white">
                    {{ __('Вы уверены, что хотите удалить свою учетную запись?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-400">
                    {{ __('После удаления вашей учетной записи все ее ресурсы и данные будут безвозвратно удалены. Пожалуйста, введите свой пароль, чтобы подтвердить, что вы хотите удалить свою учетную запись.') }}
                </p>

                <div class="mt-6">
                    <x-input-label for="password" value="{{ __('Пароль') }}" class="sr-only text-gray-300" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-gray-500 focus:ring-0"
                        placeholder="{{ __('Пароль') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-sm transition">
                        {{ __('Отмена') }}
                    </button>

                    <button type="submit" class="ms-3 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm transition">
                        {{ __('Удалить аккаунт') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
