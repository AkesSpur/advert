<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Write a Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('reviews.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="profile_id" value="{{ request('profile_id') }}">

                        <div class="flex items-center mb-4">
                            <div class="mr-4">
                                <x-input-label for="rating" :value="__('Rating')" />
                            </div>
                            <div class="flex space-x-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div>
                                        <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" 
                                            class="hidden peer" {{ old('rating') == $i ? 'checked' : '' }} {{ $i === 5 ? 'checked' : '' }}>
                                        <label for="rating{{ $i }}" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-yellow-400 peer-checked:text-yellow-500 border border-gray-200 peer-checked:border-yellow-500 rounded-full cursor-pointer hover:bg-yellow-50">
                                            <span>{{ $i }}</span>
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('rating')" class="mt-2" />

                        <div>
                            <x-input-label for="comment" :value="__('Comment (Optional)')" />
                            <textarea id="comment" name="comment" rows="5" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('comment') }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('profiles.show', request('profile_id')) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            
                            <x-primary-button>
                                {{ __('Submit Review') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 