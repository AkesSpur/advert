<x-guest-layout>
    <div class="flex flex-col items-center justify-center w-full">
        <h2 class="text-2xl font-semibold text-center text-white mb-6">Забыли пароль?</h2>

        <div class="mb-4 text-center text-sm text-gray-300">
            {{ __('Мы отправим вам ссылку для сброса пароля, которая позволит вам выбрать новый.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4 w-full max-w-md mx-auto">
            @csrf

            <!-- Email Address -->
            <div class="mt-2">
                <x-text-input id="email" 
                    class="block w-full px-4 py-3 rounded-xl bg-[#191919] border-0 text-white placeholder-gray-500 focus:ring-0" 
                    type="email" 
                    name="email" 
                    placeholder="Email"
                    :value="old('email')" 
                    required 
                    autofocus 
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full  flex justify-center py-3 px-4 border-0 rounded-xl shadow-sm text-base font-medium text-white bg-[#6340FF] hover:bg-[#5737e7] focus:outline-none">
                    {{ __('Отправить ссылку для сброса') }}
                </button>
            </div>

        </form>
    </div>
</x-guest-layout>
