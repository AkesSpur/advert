<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(auth()->user()->profile)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium">Ваш профиль</h3>
                            <div class="mt-4">
                                <x-profile-card :profile="auth()->user()->profile" :showDetails="true" />
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('profiles.edit', auth()->user()->profile) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Редактировать профиль
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">У вас еще нет профиля</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Создайте профиль, чтобы клиенты могли находить вас
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('profiles.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Создать профиль
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            @if(auth()->user()->profile)
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium mb-4">Сообщения</h3>
                            
                            @if(auth()->user()->receivedMessages->count() > 0)
                                <div class="space-y-4">
                                    @foreach(auth()->user()->receivedMessages->take(5) as $message)
                                        <div class="p-4 border rounded-lg">
                                            <div class="flex justify-between">
                                                <span class="font-medium">От: {{ $message->sender->name }}</span>
                                                <span class="text-sm text-gray-500">{{ $message->created_at->format('d.m.Y H:i') }}</span>
                                            </div>
                                            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ Str::limit($message->content, 100) }}</p>
                                            <a href="#" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Ответить</a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <a href="#" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Все сообщения →</a>
                                </div>
                            @else
                                <p class="text-center py-4 text-gray-500 dark:text-gray-400">У вас пока нет сообщений</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium mb-4">Отзывы</h3>
                            
                            @if(auth()->user()->profile && auth()->user()->profile->reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach(auth()->user()->profile->reviews->take(3) as $review)
                                        <div class="p-4 border rounded-lg">
                                            <div class="flex justify-between">
                                                <div>
                                                    <span class="font-medium">{{ $review->user->name }}</span>
                                                    <div class="flex items-center mt-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                            @else
                                                                <svg class="w-5 h-5 text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $review->created_at->format('d.m.Y') }}</span>
                                            </div>
                                            @if($review->comment)
                                                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <a href="#" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Все отзывы ({{ auth()->user()->profile->reviews->count() }}) →</a>
                                </div>
                            @else
                                <p class="text-center py-4 text-gray-500 dark:text-gray-400">У вас пока нет отзывов</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
