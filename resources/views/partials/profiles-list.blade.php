@forelse ($profiles as $index => $profile)
    <div class="h-full profile-item">
        <x-ad-card
            :id="$profile->id"
            :slug="$profile->slug"
            :isFirst="$index == 0"
            :new="$profile->created_at >= now()->subDays(7) ?? false"
            :vip="$profile->is_vip ?? false"
            :video="isset($profile->video->path) ?? false "
            :verified="$profile->is_verified ?? false"
            :name="$profile->name"
            :age="$profile->age"
            :weight="$profile->weight . ' кг'"
            :height="$profile->height . ' см'"
            :size="$profile->size . ' размер'"
            :district_display="$profile->neighborhoods->map(function ($neighborhood) {
                return ['name' => 'р. ' . $neighborhood->name, 'slug' => $neighborhood->slug];
            })->all()" 
            :district_slug="null"
            :metro_items="$profile->metroStations->isNotEmpty() ? $profile->metroStations->map(function($station) {
                return (object)['name' => $station->name, 'slug' => $station->slug];
            })->all() : []"
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
    @if(request()->ajax())
        {{-- Don't show 'No profiles found' message on subsequent AJAX loads if there are no more items --}}
    @else
        <div class="col-span-full py-10 text-center">
            <div class="text-2xl font-bold text-white mb-4">Анкеты не найдены</div>
            <p class="text-gray-400 mb-6">К сожалению, по вашему запросу не найдено ни одной анкеты.</p>
            <a href="{{ Request::url() }}" class="px-6 py-3 bg-[#6340FF] text-white rounded-lg hover:bg-[#5030EF] transition-colors">
                Сбросить фильтры
            </a>
        </div>
    @endif
@endforelse