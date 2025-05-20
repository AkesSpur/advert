@extends('second-layout')

@section('content')
    
    <div class="py-6">
        
        <div class="px-4 py-4" 
                 x-data="{ 
        showModal: false,
        activeFilter: null,
        showServices: false,
        showMetro: false,
        showPrice: false,
        showAge: false,
        showHairColor: false,
        showHeight: false,
        showWeight: false,
        showSize: false,
        showNeighborhood: false,
        selectedService: '',
        selectedMetro: '',
        selectedPrice: '',
        selectedAge: '',
        selectedHairColor: '',
        selectedHeight: '',
        selectedWeight: '',
        selectedSize: '',
        selectedNeighborhood: '',
        services: {{ Js::from($services ?? []) }},
        metroStations: {{ Js::from($metroStations ?? []) }},
        neighborhoods: {{ Js::from($neighborhoods ?? []) }},
        hairColors: {{ Js::from($hairColors ?? []) }},
        heights: {{ Js::from($heights ?? []) }},
        weights: {{ Js::from($weights ?? []) }},
        sizes: {{ Js::from($sizes ?? []) }},
        prices: {{ Js::from($prices ?? []) }},
        ages: {{ Js::from($ages ?? []) }},
        profiles: {{ Js::from($profiles->items() ?? []) }},
        currentPage: {{ $profiles->currentPage() }},
        lastPage: {{ $profiles->lastPage() }},
        loading: false,
        sort: '{{ request('sort', 'default') }}',
        showModal: false,
        activeFilter: null, // For mobile modal accordion behavior
        showServices: false,
        showMetro: false,
        showNeighborhood: false,
        showPrice: false,
        showAge: false,
        showHairColor: false,
        showHeight: false,
        showWeight: false,
        showSize: false,
        selectedService: '{{ request('service') }}' || null,
        selectedMetro: '{{ request('metro') }}' || null,
        selectedNeighborhood: '{{ request('neighborhood') }}' || null,
        selectedPrice: '{{ request('price') }}' || null,
        selectedAge: '{{ request('age') }}' || null,
        selectedHairColor: '{{ request('hairColor') }}' || null,
        selectedHeight: '{{ request('height') }}' || null,
        selectedWeight: '{{ request('weight') }}' || null,
        selectedSize: '{{ request('size') }}' || null,

        selectedServiceName: '',
        selectedMetroName: '',
        selectedNeighborhoodName: '',
        selectedPriceName: '',
        selectedAgeName: '',
        selectedHairColorName: '',
        selectedHeightName: '',
        selectedWeightName: '',
        selectedSizeName: '',


        init() {
            const urlParams = new URLSearchParams(window.location.search);
            this.selectedService = urlParams.get('service') || (this.services.length > 0 ? null : null);
            this.selectedMetro = urlParams.get('metro') || (this.metroStations && Object.keys(this.metroStations).length > 0 ? null : null);
            this.selectedNeighborhood = urlParams.get('neighborhood') || (this.neighborhoods.length > 0 ? null : null);
            this.selectedPrice = urlParams.get('price') || (this.prices.length > 0 ? null : null);
            this.selectedAge = urlParams.get('age') || (this.ages.length > 0 ? null : null);
            this.selectedHairColor = urlParams.get('hairColor') || (this.hairColors.length > 0 ? null : null);
            this.selectedHeight = urlParams.get('height') || (this.heights.length > 0 ? null : null);
            this.selectedWeight = urlParams.get('weight') || (this.weights.length > 0 ? null : null);
            this.selectedSize = urlParams.get('size') || (this.sizes.length > 0 ? null : null);

            // Initialize Name properties based on selected values
            if (this.selectedService) {
                const service = this.services.find(s => s.value === this.selectedService);
                if (service) this.selectedServiceName = service.name;
            }
            if (this.selectedMetro) {
                for (const letter in this.metroStations) {
                    const station = this.metroStations[letter].find(s => s.value === this.selectedMetro);
                    if (station) {
                        this.selectedMetroName = station.name;
                        break;
                    }
                }
            }
            if (this.selectedNeighborhood) {
                const neighborhood = this.neighborhoods.find(n => n.value === this.selectedNeighborhood);
                if (neighborhood) this.selectedNeighborhoodName = neighborhood.name;
            }
            if (this.selectedPrice) {
                const price = this.prices.find(p => p.value === this.selectedPrice);
                if (price) this.selectedPriceName = price.name;
            }
            if (this.selectedAge) {
                const age = this.ages.find(a => a.value === this.selectedAge);
                if (age) this.selectedAgeName = age.name;
            }
            if (this.selectedHairColor) {
                const hairColor = this.hairColors.find(hc => hc.value === this.selectedHairColor);
                if (hairColor) this.selectedHairColorName = hairColor.name;
            }
            if (this.selectedHeight) {
                const height = this.heights.find(h => h.value === this.selectedHeight);
                if (height) this.selectedHeightName = height.name;
            }
            if (this.selectedWeight) {
                const weight = this.weights.find(w => w.value === this.selectedWeight);
                if (weight) this.selectedWeightName = weight.name;
            }
            if (this.selectedSize) {
                const size = this.sizes.find(s => s.value === this.selectedSize);
                if (size) this.selectedSizeName = size.name;
            }

            // ... rest of init
        },

        selectService(service) {
            this.selectedService = service.value;
            this.selectedServiceName = service.name;
            this.showServices = false;
            this.applyFilters();
        },
        selectMetro(station) {
            this.selectedMetro = station.value;
            this.selectedMetroName = station.name;
            this.showMetro = false;
            this.applyFilters();
        },
        selectNeighborhood(neighborhood) {
            this.selectedNeighborhood = neighborhood.value;
            this.selectedNeighborhoodName = neighborhood.name;
            this.showNeighborhood = false;
            this.applyFilters();
        },
        selectPrice(price) {
            this.selectedPrice = price.value;
            this.selectedPriceName = price.name;
            this.showPrice = false;
            this.applyFilters();
        },
        selectAge(age) {
            this.selectedAge = age.value;
            this.selectedAgeName = age.name;
            this.showAge = false;
            this.applyFilters();
        },
        selectHairColor(color) {
            this.selectedHairColor = color.value;
            this.selectedHairColorName = color.name;
            this.showHairColor = false;
            this.applyFilters();
        },
        selectHeight(height) {
            this.selectedHeight = height.value;
            this.selectedHeightName = height.name;
            this.showHeight = false;
            this.applyFilters();
        },
        selectWeight(weight) {
            this.selectedWeight = weight.value;
            this.selectedWeightName = weight.name;
            this.showWeight = false;
            this.applyFilters();
        },
        selectSize(size) {
            this.selectedSize = size.value;
            this.selectedSizeName = size.name;
            this.showSize = false;
            this.applyFilters();
        },
        toggleFilter(filter) {
            const toggles = {
                services: 'showServices',
                metro: 'showMetro',
                price: 'showPrice',
                age: 'showAge',
                hairColor: 'showHairColor',
                height: 'showHeight',
                weight: 'showWeight',
                size: 'showSize',
                neighborhood: 'showNeighborhood'
            };
            for (const key in toggles) {
                this[toggles[key]] = (key === filter) ? !this[toggles[key]] : false;
            }
        },
        openModal(filter) {
            this.activeFilter = filter;
            this.showModal = true;
        },
        selectService(service) {
            this.selectedService = service.name;
            this.showServices = false;
            // Use the provided slug directly
            window.location.href = '{{ url("/service") }}/' + service.slug;
        },
        selectMetro(station) {
            this.selectedMetro = station.name;
            this.showMetro = false;
            // Use the provided slug directly
            window.location.href = '{{ url("/metro") }}/' + station.slug;
        },
        selectPrice(price) {
            this.selectedPrice = price;
            this.showPrice = false;
            const slug = price;
            window.location.href = '{{ url("/price") }}/' + slug;
        },
        selectAge(age) {
            this.selectedAge = age;
            this.showAge = false;
            const slug = age;
            window.location.href = '{{ url("/age") }}/' + slug;
        },
        selectHairColor(color) {
            this.selectedHairColor = color;
            this.showHairColor = false;
            const slug = color;
            window.location.href = '{{ url("/hair-color") }}/' + slug;
        },
        selectHeight(height) {
            this.selectedHeight = height;
            this.showHeight = false;
            const slug = height;
            window.location.href = '{{ url("/height") }}/' + slug;
        },
        selectWeight(weight) {
            this.selectedWeight = weight;
            this.showWeight = false;
            const slug = weight;
            window.location.href = '{{ url("/weight") }}/' + slug;
        },
        selectSize(size) {
            this.selectedSize = size;
            this.showSize = false;
            const slug = size;
            window.location.href = '{{ url("/breast-size") }}/' + slug;
        },
        selectNeighborhood(neighborhood) {
            this.selectedNeighborhood = neighborhood.name;
            this.showNeighborhood = false;
            window.location.href = '{{ url("/neighborhood") }}/' + neighborhood.slug;
        },
        resetFilters() {
            this.selectedService = '';
            this.selectedMetro = '';
            this.selectedPrice = '';
            this.selectedAge = '';
            this.selectedHairColor = '';
            this.selectedHeight = '';
            this.selectedWeight = '';
            this.selectedSize = '';
            this.selectedNeighborhood = '';
        }
                    }"
>
    <!-- Rest of the HTML remains unchanged -->
    <!-- Filter Bar - All Screen Sizes -->
    <div class="flex max-w-screen-2xl px-2 2xl:px-4 mx-auto gap-2 flex-wrap">
        <!-- Services Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showServices }"
                 @click="toggleFilter('services')">
                <span x-text="selectedService || 'Услуги'"
                      :class="{ 'text-white': selectedService, 'text-[#FFFFFF66]': !selectedService }"></span>
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Services Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-[300%] shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showServices" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showServices = false"
                 style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-8">
                    <template x-for="service in services" :key="service.name">
                        <div @click="selectService(service)" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="service.name"></span>
                            <span x-text="'(' + service.count + ')'"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Metro Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showMetro }"
                 @click="toggleFilter('metro')">
                <span x-text="selectedMetro || 'Метро'"
                      :class="{ 'text-white': selectedMetro, 'text-[#FFFFFF66]': !selectedMetro }"></span>
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Metro Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-[300%] shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showMetro" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showMetro = false"
                 style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <template x-for="(stations, letter) in metroStations" :key="letter">
                        <div>
                            <div class="text-xl font-bold mb-2" x-text="letter"></div>
                            <div class="space-y-2">
                                <template x-for="station in stations" :key="station.name">
                                    <div @click="selectMetro(station)" 
                                         class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                                        <span x-text="station.name"></span>
                                        <span x-text="'(' + station.count + ')'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

         <!-- Neighborhood Filter -->
 <div class="hidden lg:block flex-1 min-w-[210px] relative">
    <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
         :class="{ 'bg-[#6340FF]': showNeighborhood }"
         @click="toggleFilter('neighborhood')">
        <span x-text="selectedNeighborhood || 'Район'"
              :class="{ 'text-white': selectedNeighborhood, 'text-[#FFFFFF66]': !selectedNeighborhood }"></span>
        <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
            <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
        </svg>
    </div>
    
    <!-- Neighborhood Options Dropdown -->
    <div class="absolute left-[-50%] right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-[300%] shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
         x-show="showNeighborhood" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         @click.away="showNeighborhood = false"
         style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-8">
            <template x-for="neighborhood in neighborhoods" :key="neighborhood.name">
                <div @click="selectNeighborhood(neighborhood)" 
                     class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                    <span x-text="neighborhood.name"></span>
                    <span x-text="'(' + neighborhood.count + ')'"></span>
                </div>
            </template>
        </div>
    </div>
</div>

        <!-- Price Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showPrice }"
                 @click="toggleFilter('price')">
                <span x-text="selectedPrice || 'Цена'"
                      :class="{ 'text-white': selectedPrice, 'text-[#FFFFFF66]': !selectedPrice }"></span>
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Price Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-[100%] shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showPrice" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showPrice = false"
                 style="display: none;">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="price in prices" :key="price.name">
                        <div @click="selectPrice(price.value)" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="price.name"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Age Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showAge }"
                 @click="toggleFilter('age')">
                <span x-text="selectedAge || 'Возраст'"
                      :class="{ 'text-white': selectedAge, 'text-[#FFFFFF66]': !selectedAge }"></span>
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Age Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-full shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showAge" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showAge = false"
                 style="display: none;">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="age in ages" :key="age.value"> {{-- Changed from age.range --}}
                        <div @click="selectAge(age.value); activeFilter = null" {{-- Changed from selectAge(age.value) --}}
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="age.name"></span> {{-- Changed from age.range --}}
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Hair Color Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showHairColor }"
                 @click="toggleFilter('hairColor')">
                <span x-text="selectedHairColor || 'Цвет волос'"
                      :class="{ 'text-white': selectedHairColor, 'text-[#FFFFFF66]': !selectedHairColor }"></span>
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Hair Color Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-full shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showHairColor" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showHairColor = false"
                 style="display: none;">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="color in hairColors" :key="color.value">
                        <div @click="selectHairColor(color.value); activeFilter = null" {{-- Changed from selectHairColor(color.value) --}}
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="color.name"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Height Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showHeight }"
                 @click="toggleFilter('height')">
                <span x-text="selectedHeight || 'Рост'" {{-- Changed from selectedHeight --}}
                      :class="{ 'text-white': selectedHeightName, 'text-[#FFFFFF66]': !selectedHeightName }"></span> {{-- Changed from selectedHeight --}}
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Height Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-full shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showHeight" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showHeight = false"
                 style="display: none;">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="height in heights" :key="height.value"> {{-- Changed from height.range --}}
                        <div @click="selectHeight(height.value)" {{-- Changed from selectHeight(height.value) --}}
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="height.name"></span> {{-- Changed from height.range --}}
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <!-- Weight Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showWeight }"
                 @click="toggleFilter('weight')">
                <span x-text="selectedWeightName || 'Вес'" {{-- Changed from selectedWeight --}}
                      :class="{ 'text-white': selectedWeightName, 'text-[#FFFFFF66]': !selectedWeightName }"></span> {{-- Changed from selectedWeight --}}
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Weight Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-full shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showWeight" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showWeight = false"
                 style="display: none;">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="weight in weights" :key="weight.value">
                        <div @click="selectWeight(weight.value); activeFilter = null" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="weight.name"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Size Filter -->
        <div class="hidden lg:block flex-1 min-w-[210px] relative">
            <div class="w-full h-[50px] flex justify-between items-center cursor-pointer px-4 py-3 rounded-xl bg-[#191919]"
                 :class="{ 'bg-[#6340FF]': showSize }"
                 @click="toggleFilter('size')">
                <span x-text="selectedSizeName || 'Грудь'" {{-- Changed from selectedSize --}}
                      :class="{ 'text-white': selectedSizeName, 'text-[#FFFFFF66]': !selectedSizeName }"></span> {{-- Changed from selectedSize --}}
                <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                    <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            
            <!-- Size Options Dropdown -->
            <div class="absolute left-0 right-0 bg-neutral-900 p-6 rounded-xl mt-2 z-30 w-full shadow-xl max-h-[400px] overflow-y-auto custom-scrollbar" 
                 x-show="showSize" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 @click.away="showSize = false"
                 style="display: none;">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="size in sizes" :key="size.value"> {{-- Changed from size.range --}}
                        <div @click="selectSize(size.value)" {{-- Changed from selectSize(size.value) --}}
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="size.name"></span> {{-- Changed from size.range --}}
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Filter Button -->
    <div class="w-full mt-2 lg:hidden">
        <button @click="showModal = true"
                class="w-full px-4 py-3 rounded-xl bg-[#6340FF] text-white">
            Фильтры
        </button>
    </div>

    <!-- Mobile Modal -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 bg-black overflow-auto custom-scrollbar" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-800">
            <div class="text-xl font-bold">    <img src="{{ asset($logoSetting->logo) }}" 
                alt="SHEMK logo" 
                class="h-12 w-auto object-contain"
                loading="lazy"></div>
            <button @click="showModal = false" class="text-white text-2xl">&times;</button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-4 space-y-4">
            <!-- Services -->
            <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                 :class="{ 'bg-[#6340FF]': activeFilter === 'services' }"
                 @click="activeFilter = activeFilter === 'services' ? null : 'services'">
                <div class="flex justify-between items-center">
                    <span x-text="selectedService || 'Услуги'" 
                          :class="{ 'text-white': selectedService, 'text-[#FFFFFF66]': !selectedService }"></span>
                    <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                        <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            
            <!-- Services Options -->
            <div x-show="activeFilter === 'services'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <template x-for="service in services" :key="service.name">
                        <div @click="selectService(service); activeFilter = null" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="service.name"></span>
                            <span x-text="'(' + service.count + ')'"></span>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Metro -->
            <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                 :class="{ 'bg-[#6340FF]': activeFilter === 'metro' }"
                 @click="activeFilter = activeFilter === 'metro' ? null : 'metro'">
                <div class="flex justify-between items-center">
                    <span x-text="selectedMetro || 'Метро'" 
                          :class="{ 'text-white': selectedMetro, 'text-[#FFFFFF66]': !selectedMetro }"></span>
                    <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                        <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            
            <!-- Metro Options -->
            <div x-show="activeFilter === 'metro'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="(stations, letter) in metroStations" :key="letter">
                        <div>
                            <div class="text-xl font-bold mb-2" x-text="letter"></div>
                            <div class="space-y-2">
                                <template x-for="station in stations" :key="station.name">
                                    <div @click="selectMetro(station); activeFilter = null" 
                                         class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                                        <span x-text="station.name"></span>
                                        <span x-text="'(' + station.count + ')'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

                       <!-- Neighborhood -->
                       <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                       :class="{ 'bg-[#6340FF]': activeFilter === 'neighborhood' }"
                       @click="activeFilter = activeFilter === 'neighborhood' ? null : 'neighborhood'">
                      <div class="flex justify-between items-center">
                          <span x-text="selectedNeighborhood || 'Район'" 
                                :class="{ 'text-white': selectedNeighborhood, 'text-[#FFFFFF66]': !selectedNeighborhood }"></span>
                          <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                              <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                          </svg>
                      </div>
                  </div>
                  
                  <!-- Neighborhood Options -->
                  <div x-show="activeFilter === 'neighborhood'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                      <div class="grid grid-cols-1 gap-y-4">
                          <template x-for="neighborhood in neighborhoods" :key="neighborhood.name">
                              <div @click="selectNeighborhood(neighborhood); activeFilter = null" 
                                   class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                                  <span x-text="neighborhood.name"></span>
                                  <span x-text="'(' + neighborhood.count + ')'"></span>
                              </div>
                          </template>
                      </div>
                  </div>

            
        
                {{-- hair color and breast size --}}
            <div class="flex space-x-4">
                <!-- Hair Color -->
            <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
            :class="{ 'bg-[#6340FF]': activeFilter === 'hairColor' }"
            @click="activeFilter = activeFilter === 'hairColor' ? null : 'hairColor'">
           <div class="flex justify-between items-center">
               <span x-text="selectedHairColorName || 'Цвет волос'" {{-- Changed from selectedHairColor --}}
                     :class="{ 'text-white': selectedHairColorName, 'text-[#FFFFFF66]': !selectedHairColorName }"></span> {{-- Changed from selectedHairColor --}}
               <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                   <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
               </svg>
           </div>
       </div>

       <!-- Size -->
       <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
       :class="{ 'bg-[#6340FF]': activeFilter === 'size' }"
       @click="activeFilter = activeFilter === 'size' ? null : 'size'">
      <div class="flex justify-between items-center">
          <span x-text="selectedSizeName || 'Грудь'" {{-- Changed from selectedSize --}}
                :class="{ 'text-white': selectedSizeName, 'text-[#FFFFFF66]': !selectedSizeName }"></span> {{-- Changed from selectedSize --}}
          <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
              <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
          </svg>
      </div>
  </div>

            </div>
            
            <!-- Hair Color Options -->
            <div x-show="activeFilter === 'hairColor'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="color in hairColors" :key="color.value"> {{-- Changed from color.name --}}
                        <div @click="selectHairColor(color.value); activeFilter = null" {{-- Changed from selectHairColor(color.value) --}}
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="color.name"></span>
                        </div>
                    </template>
                </div>
            </div>
  
            <!-- Size Options -->
            <div x-show="activeFilter === 'size'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="size in sizes" :key="size.value"> {{-- Changed from size.range --}}
                        <div @click="selectSize(size.value); activeFilter = null" {{-- Changed from selectSize(size.value) --}}
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="size.name"></span> {{-- Changed from size.range --}}
                        </div>
                    </template>
                </div>
            </div>            
            
            {{-- weight and height --}}
            <div class="flex space-x-4">
            <!-- Height -->
            <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                 :class="{ 'bg-[#6340FF]': activeFilter === 'height' }"
                 @click="activeFilter = activeFilter === 'height' ? null : 'height'">
                <div class="flex justify-between items-center">
                    <span x-text="selectedHeightName || 'Рост'" {{-- Changed from selectedHeight --}}
                          :class="{ 'text-white': selectedHeightName, 'text-[#FFFFFF66]': !selectedHeightName }"></span> {{-- Changed from selectedHeight --}}
                    <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                        <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            
                        <!-- Weight -->
                        <div class="w-full cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                        :class="{ 'bg-[#6340FF]': activeFilter === 'weight' }"
                        @click="activeFilter = activeFilter === 'weight' ? null : 'weight'">
                       <div class="flex justify-between items-center">
                           <span x-text="selectedWeightName || 'Вес'" {{-- Changed from selectedWeight --}}
                                 :class="{ 'text-white': selectedWeightName, 'text-[#FFFFFF66]': !selectedWeightName }"></span> {{-- Changed from selectedWeight --}}
                           <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                               <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                           </svg>
                       </div>
                   </div>
       
            </div>
            
            <!-- Height Options -->
            <div x-show="activeFilter === 'height'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="height in heights" :key="height.value"> {{-- Changed from height.range --}}
                        <div @click="selectHeight(height.value); activeFilter = null" {{-- Changed from selectHeight(height.value) --}}
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="height.name"></span> {{-- Changed from height.range --}}
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Weight Options -->
            <div x-show="activeFilter === 'weight'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 gap-y-4">
                    <template x-for="weight in weights" :key="weight.value">
                        <div @click="selectWeight(weight.value); activeFilter = null" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="weight.name"></span>
                        </div>
                    </template>
                </div>
            </div>

            
          
            <!-- Price and Age Row -->
            <div class="flex space-x-4">
                <!-- Price -->
                <div class="w-1/2 cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                     :class="{ 'bg-[#6340FF]': activeFilter === 'price' }"
                     @click="activeFilter = activeFilter === 'price' ? null : 'price'">
                    <div class="flex justify-between items-center">
                        <span x-text="selectedPrice || 'Цена'" 
                              :class="{ 'text-white': selectedPrice, 'text-[#FFFFFF66]': !selectedPrice }"></span>
                        <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                            <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Age -->
                <div class="w-1/2 cursor-pointer px-4 py-3 rounded-xl bg-[#191919] relative" 
                     :class="{ 'bg-[#6340FF]': activeFilter === 'age' }"
                     @click="activeFilter = activeFilter === 'age' ? null : 'age'">
                    <div class="flex justify-between items-center">
                        <span x-text="selectedAge || 'Возраст'" 
                              :class="{ 'text-white': selectedAge, 'text-[#FFFFFF66]': !selectedAge }"></span>
                        <svg class="w-4 h-4 fill-current text-[#FFFFFF66]" viewBox="0 0 20 20">
                            <path d="M5.5 7l4.5 4.5L14.5 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Price Options -->
            <div x-show="activeFilter === 'price'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <template x-for="price in prices" :key="price.value">
                        <div @click="selectPrice(price.value); activeFilter = null" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="price.name"></span>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Age Options -->
            <div x-show="activeFilter === 'age'" class="bg-neutral-900 p-4 rounded-xl max-h-[300px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <template x-for="age in ages" :key="age.value">
                        <div @click="selectAge(age.value); activeFilter = null" 
                             class="flex items-center justify-between cursor-pointer hover:text-[#6340FF] text-[#FFFFFFCC]">
                            <span x-text="age.name"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
        </div>


        <div class="max-w-screen-2xl px-6 mt-4 mx-auto">
            <!-- Filter and Sort Container - Flex on lg, Column on md/sm -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                <!-- Filter Buttons -->
                <div class="flex overflow-x-auto hide-scrollbar justify-start gap-3 mb-4 lg:w-[75%] lg:mb-0">
                    @foreach ($topMenus as $menu)
                        @if ($menu->all_accounts == true && $menu->status == true)
                        <a href="{{ Request::url() }}" 
                        class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'all' || !$filter ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                        {{$menu->name}}
                        </a>                             
                        @endif

                        @if ($menu->has_video == true && $menu->status == true)
                        <a href="{{ Request::url() . '?filter=video' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                        class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'video' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                        {{$menu->name}}
                        </a>                             
                        @endif

                        @if ($menu->new == true && $menu->status == true)
                        <a href="{{ Request::url() . '?filter=new' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                        class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'new' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                        {{$menu->name}}
                        </a>                             
                        @endif

                        @if ($menu->vip == true && $menu->status == true)
                        <a href="{{ Request::url() . '?filter=vip' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                        class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'vip' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                        {{$menu->name}}
                        </a>
                             
                        @endif

                        @if ($menu->cheapest == true && $menu->status == true)
                        <a href="{{ Request::url() . '?filter=cheap' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                        class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'cheap' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                        {{$menu->name}}
                        </a>                    
                        @endif

                        @if ($menu->verified == true && $menu->status == true)
                        <a href="{{ Request::url() . '?filter=verified' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                        class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'verified' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                        {{$menu->name}}
                        </a>                             
                        @endif

                    @endforeach
                    
                    <!-- Custom Categories as Filter Buttons -->
                    @if(isset($customCategories) && count($customCategories) > 0)
                        @foreach($customCategories as $category)
                            @if($category->status == 1 && $category->show_in_top_menu == 1)
                            <a href="{{ url('/category/' . $category->slug) }}" 
                               class="capitalize px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ request()->is('category/' . $category->slug) ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">{{ $category->name }}</a>
                            @endif
                        @endforeach
                    @endif
                </div>
                
                <!-- Sort Dropdown -->
                <div class="relative" x-data="{ 
                    showSortOptions: false, 
                    selectedSort: '{{ $sort == "popular" ? "Самые популярные" : ($sort == "cheapest" ? "Дешевые" : ($sort == "expensive" ? "Дорогие" : "Сортировать по умолчанию")) }}' 
                }">
    
                <button @click="showSortOptions = !showSortOptions" 
                            class="flex items-center w-full px-4 py-2 text-white rounded-lg hover:bg-[#252525] transition-colors">
                        <span class="text-white pr-2" x-text="selectedSort"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="{'transform rotate-180': showSortOptions}">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="showSortOptions" 
                         @click.away="showSortOptions = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute left-0 md:left-auto md:right-0 lg:right-0 mt-2 w-full md:w-64 bg-[#191919] rounded-lg shadow-lg z-50">
                        <div class="py-1">
                            <a href="{{ Request::url() . '?sort=popular' . (request('filter') ? '&filter=' . request('filter') : '') }}" 
                               @click="selectedSort = 'Самые популярные'; showSortOptions = false" 
                               class="block px-4 py-2 text-white hover:bg-[#252525]">Самые популярные</a>
                            <a href="{{ Request::url() . '?sort=cheapest' . (request('filter') ? '&filter=' . request('filter') : '') }}" 
                               @click="selectedSort = 'Дешевые'; showSortOptions = false" 
                               class="block px-4 py-2 text-white hover:bg-[#252525]">Дешевые</a>
                            <a href="{{ Request::url() . '?sort=expensive' . (request('filter') ? '&filter=' . request('filter') : '') }}" 
                               @click="selectedSort = 'Дорогие'; showSortOptions = false" 
                               class="block px-4 py-2 text-white hover:bg-[#252525]">Дорогие</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div id="profiles-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 px-6 max-w-screen-2xl mx-auto">
            @include('partials.profiles-list', ['profiles' => $profiles])
        </div>    

        @if($profiles->hasMorePages())
        <div id="show-more-container" class="mt-6 text-center">
            <button id="show-more-btn" class="px-6 py-2 bg-[#6340FF] hover:bg-[#333333] text-white rounded-lg transition">
                Показать еще
            </button>
        </div>
        @else
            @if($profiles->isNotEmpty() && !$profiles->onFirstPage())
            {{-- Still show pagination if not on first page and no more items, but hide show more --}}
            @elseif($profiles->isEmpty() && !$profiles->onFirstPage())
            {{-- If ajax loaded and no items, this part is not hit. This is for initial load with no items on page > 1 --}}
            @elseif($profiles->isNotEmpty())
            {{-- This covers the case where there are items, but not enough for a next page from initial load --}}
            @endif
        @endif

        {{-- Always show pagination if there are items, let Laravel handle showing/hiding it based on total items vs perPage --}}
        @if($profiles->isNotEmpty() || !$profiles->onFirstPage())
        <div class="col-span-full mt-8 flex justify-center">
            {{ $profiles->appends(request()->query())->links('partials.custom-pagination') }}
        </div>
        @endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showMoreBtn = document.getElementById('show-more-btn');
        const showMoreContainer = document.getElementById('show-more-container');
        const profilesContainer = document.getElementById('profiles-container');
        let currentPage = {{ $profiles->currentPage() }};
        let isLoading = false;

        if (showMoreBtn && !showMoreBtn.dataset.listenerAttached) {
            showMoreBtn.dataset.listenerAttached = 'true';
            showMoreBtn.addEventListener('click', function () {
                if (isLoading) return;

                isLoading = true;
                currentPage++;
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('page', currentPage);

                const originalButtonText = showMoreBtn.textContent;
                showMoreBtn.textContent = 'Загрузка...';
                showMoreBtn.disabled = true;

                fetch(currentUrl.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.html && data.html.trim() !== '') {
                        // Create a temporary div to parse the HTML string
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.html;
                        // Append each child of the tempDiv to the profilesContainer
                        // This ensures that scripts within the partial are not executed again if they were already loaded
                        // and also correctly appends the new profile items.
                        while (tempDiv.firstChild) {
                            profilesContainer.appendChild(tempDiv.firstChild);
                        }
                    }
                    
                    if (data.has_more_pages) {
                        history.pushState({page: currentPage}, '', currentUrl.toString());
                    } else {
                        if(showMoreContainer) {
                            showMoreContainer.style.display = 'none';
                        }
                        // Update URL to the current (last) page
                        history.pushState({page: currentPage}, '', currentUrl.toString());
                    }
                    // Update Laravel's pagination links if they exist
                    const paginationLinks = document.querySelector('.pagination');
                    if (paginationLinks) {
                        
                    }
                })
                .catch(error => {
                    console.error('Error fetching more profiles:', error);
                    currentPage--; // revert page increment on error
                })
                .finally(() => {
                    isLoading = false;
                    if (showMoreBtn) {
                        showMoreBtn.textContent = originalButtonText;
                        showMoreBtn.disabled = false;
                    }
                });
            });
        }
    });
</script>
@endpush
        
    </div>


      <!-- Hero Section -->
    <div class="py-8 md:py-12 lg:py-16">
        <div class="max-w-screen-2xl mx-auto px-6">
            <div class="flex flex-col md:flex-col lg:flex-row items-stretch h-full">
                @php
                    $heroSetting = \App\Models\HeroSectionSetting::first();
                @endphp
                <!-- Text Content -->
                <div class="w-full lg:w-1/2 p-5 text-white bg-[#191919] rounded-tl-3xl lg:rounded-bl-3xl rounded-tr-3xl lg:rounded-tr-none">
                    <h1 class="text-3xl md:text-4xl font-bold mb-6">{{@$heroSetting->title ?? 'База анкет проституток, откровенные индивидуалки'}}</h1>
                    <div class="space-y-4 text-[#FFFFFFCC]">
                        {!! @$heroSetting->text_content ?? '<p>Здравствуйте, дорогой гость нашего сайта для взрослых! Если вас интересуют лучшие проститутки из Челябинска, обязательно изучите представленную подборку анкет, и вы сможете подобрать идеальную шлюху для совместных развлечений. Девушки, страницы которых опубликованы в нашем масштабном каталоге, отличаются:</p><ul class="list-disc pl-5 space-y-1"><li>ослепительно красивой внешностью;</li><li>шикарными формами;</li><li>горячим темпераментом;</li><li>богатым опытом обслуживания мужчин;</li><li>индивидуальным подходом к каждому клиенту.</li></ul><p>Кроме того, нашим женщинам характерен широкий ассортимент практикуемых услуг. Каждая индивидуалка практикует десятки видов интимных процедур, выходя за рамки традиционного секса. Сняв обученную путану, вы сможете заказать любые виды секса, в том числе:</p><ul class="list-disc pl-5 space-y-1"><li>классику;</li><li>анал;</li><li>минет;</li><li>лесби;</li><li>БДСМ.</li></ul>' !!}
                    </div>
                </div>
                
                <!-- Image -->
                <div class="w-full lg:w-1/2 relative">
                    <div class="overflow-hidden shadow-xl bg-[#191919] rounded-bl-3xl lg:rounded-bl-none lg:rounded-tr-3xl rounded-br-3xl h-full">
                        <img src="{{asset(@$heroSetting->image ?? 'assets/images/hero.jpg')}}" alt="Hero image" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-2xl px-6 mt-4 mx-auto">
            <!-- Filter Buttons -->
            <div class="flex overflow-x-auto hide-scrollbar justify-center gap-3 mb-4">
                @foreach ($footerMenus as $menu)
                    @if ($menu->all_accounts == true && $menu->status == true)
                    <a href="{{ Request::url() }}" 
                    class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'all' || !$filter ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                    {{$menu->name}}
                    </a>                             
                    @endif

                    @if ($menu->has_video == true && $menu->status == true)
                    <a href="{{ Request::url() . '?filter=video' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                    class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'video' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                    {{$menu->name}}
                    </a>                             
                    @endif

                    @if ($menu->new == true && $menu->status == true)
                    <a href="{{ Request::url() . '?filter=new' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                    class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'new' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                    {{$menu->name}}
                    </a>                             
                    @endif

                    @if ($menu->vip == true && $menu->status == true)
                    <a href="{{ Request::url() . '?filter=vip' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                    class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'vip' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                    {{$menu->name}}
                    </a>
                         
                    @endif

                    @if ($menu->cheapest == true && $menu->status == true)
                    <a href="{{ Request::url() . '?filter=cheap' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                    class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'cheap' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                    {{$menu->name}}
                    </a>                    
                    @endif

                    @if ($menu->verified == true && $menu->status == true)
                    <a href="{{ Request::url() . '?filter=verified' . (request('sort') ? '&sort=' . request('sort') : '') }}" 
                    class="px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ $filter == 'verified' ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">
                    {{$menu->name}}
                    </a>                             
                    @endif

                @endforeach
                
                <!-- Custom Categories as Filter Buttons -->
                @if(isset($customCategories) && count($customCategories) > 0)
                    @foreach($customCategories as $category)
                        @if($category->status == 1 && $category->show_in_footer_menu == 1)
                        <a href="{{ url('/category/' . $category->slug) }}" 
                           class="capitalize px-4 py-2 shrink-0 text-white rounded-lg hover:bg-[#5030EF] transition-colors {{ request()->is('category/' . $category->slug) ? 'bg-[#6340FF]' : 'bg-[#191919] border border-[#8B8B8B] hover:bg-[#252525]' }}">{{ $category->name }}</a>
                        @endif
                    @endforeach
                @endif
            </div>
    </div>
@endsection
