<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Профиль') }}: {{ $profile->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Profile Information -->
                    <div class="flex flex-col md:flex-row md:items-start">
                        <div class="md:w-1/3 flex justify-center md:justify-start mb-6 md:mb-0">
                            @if($profile->photo_path)
                                <img src="{{ Storage::url($profile->photo_path) }}" alt="{{ $profile->user->name }}" class="h-48 w-48 object-cover rounded-lg shadow-md">
                            @else
                                <div class="h-48 w-48 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="md:w-2/3 md:pl-8">
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold">{{ $profile->user->name }}</h1>
                                
                                <div class="mt-2 flex items-center">
                                    <div class="flex">
                                        @php
                                            $averageRating = $profile->reviews->count() > 0 
                                                ? round($profile->reviews->avg('rating'), 1) 
                                                : 0;
                                        @endphp
                                        
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $averageRating)
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
                                    
                                    <p class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $averageRating }} из 5 ({{ $profile->reviews->count() }} {{ trans_choice('отзыв|отзыва|отзывов', $profile->reviews->count()) }})
                                    </p>
                                </div>
                                
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $profile->city }}</span>
                                    </div>
                                    
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span>{{ $profile->phone }}</span>
                                    </div>
                                    
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $profile->category }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($profile->user_id === auth()->id())
                                <div class="mb-6 flex space-x-4">
                                    <a href="{{ route('profiles.edit', $profile) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        {{ __('Редактировать профиль') }}
                                    </a>
                                    
                                    @if($profile->tariffs->count() > 0)
                                    <button id="adStatsButton" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Статистика рекламы') }}
                                    </button>
                                    @endif
                                </div>
                            @else
                                <div class="mb-6 flex space-x-4">
                                    <button class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Написать сообщение') }}
                                    </button>
                                    
                                    <button class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Оставить отзыв') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Description -->
                    @if($profile->description)
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold border-b pb-2 border-gray-200 dark:border-gray-700">{{ __('О себе') }}</h2>
                            <div class="mt-4 text-gray-700 dark:text-gray-300">
                                {{ $profile->description }}
                            </div>
                        </div>
                    @endif
                    
                    <!-- Services -->
                    @if($profile->services && $profile->services->count() > 0)
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold border-b pb-2 border-gray-200 dark:border-gray-700">{{ __('Услуги') }}</h2>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($profile->services as $service)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        {{ $service->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Reviews -->
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-xl font-semibold border-b pb-2 border-gray-200 dark:border-gray-700">{{ __('Отзывы') }}</h2>
                        
                        @if($profile->reviews->count() > 0)
                            <div class="mt-4 space-y-6">
                                @foreach($profile->reviews as $review)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="font-medium">{{ $review->user->name }}</div>
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
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $review->created_at->format('d.m.Y') }}
                                            </div>
                                        </div>
                                        
                                        @if($review->comment)
                                            <div class="mt-3 text-gray-700 dark:text-gray-300">
                                                {{ $review->comment }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-4 text-center py-8 text-gray-500 dark:text-gray-400">
                                {{ __('У этого профиля пока нет отзывов.') }}
                            </div>
                        @endif
                        
                        @if(auth()->check() && $profile->user_id !== auth()->id())
                            <div class="mt-6">
                                <button id="leave-review-btn" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Оставить отзыв') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include Ad Statistics Modal -->
    @include('profiles.partials.ad-stats-modal')
</x-app-layout>