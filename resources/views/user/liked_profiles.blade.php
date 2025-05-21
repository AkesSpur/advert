@extends('second-layout')

@section('content')
<div class="py-8">
    <div class="max-w-screen-2xl px-6  mb-4 mx-auto">
        <nav class="text-sm text-[#A0A0A0] mb-4" aria-label="Breadcrumb">
            <ol class="list-reset flex items-center space-x-1">
                <li>
                    <a href="/" class="hover:text-[#A0A0A0] text-[#636363]">Главная</a>
                </li>
                <li><span>/</span></li>
                <li class="text-[#6340FF] font-medium" aria-current="page">Избранные анкеты</li>
            </ol>
        </nav>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 px-6 max-w-screen-2xl mx-auto">
        @forelse ($likedProfiles as $profile)
        <div class="h-full">
            <x-ad-card
                :id="$profile->id"
                :slug="$profile->slug"
                :new="$profile->created_at >= now()->subDays(7) ?? false"
                :vip="$profile->is_vip ?? false"
                :video="isset($profile->video->path) ?? false "
                :verified="$profile->is_verified ?? false"
                :name="$profile->name"
                :age="$profile->age"
                :weight="$profile->weight . ' кг'"
                :height="$profile->height . ' см'"
                :size="$profile->size . ' размер'"
                :district="$profile->neighborhoods->isNotEmpty() ? 'р. ' . $profile->neighborhoods->first()->name : ''"
                :metro="$profile->metroStations->isNotEmpty() ? 'м. ' . $profile->metroStations->pluck('name')->implode(', м. ') : ''"
                :phone="$profile->phone"
                :prices="[
                    'hour' => $profile->vyezd_1hour ?? 0,
                    'two_hours' => $profile->vyezd_2hours ?? 0,
                    'night' => $profile->vyezd_night ?? 0,
                ]"
                :img="$profile->images"
            />
        </div>
            @empty
                <div class="col-span-full rounded-lg p-8 text-center">
                    <p class="text-xl">У вас пока нет избранных анкет</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-3 bg-[#6340FF] text-white rounded-lg hover:bg-[#5030EF] transition">Перейти к анкетам</a>
                </div>
            @endforelse
        </div>
</div>
@endsection