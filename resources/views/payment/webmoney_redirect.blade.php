<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="webmoney_form" name="webmoney_form" method="POST" action="{{ $webMoneyUrl }}" accept-charset="windows-1251">
                        @foreach ($formData as $name => $value)
                            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                        @endforeach
                        <p>Перенаправление на WebMoney, пожалуйста, подождите...</p>
                        <noscript>
                            <p>Пожалуйста, нажмите на кнопку ниже, чтобы продолжить.</p>
                            <input type="submit" value="Continue to WebMoney">
                        </noscript>
                    </form>
                    <script type="text/javascript">
                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementById('webmoney_form').submit();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>