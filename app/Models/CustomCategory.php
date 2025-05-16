<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'title',
        'meta_description',
        'h1',
        'age_filters',
        'weight_filters',
        'size_filters',
        'hair_color_filters',
        'price_filters',
        'height_filters',
        'service_ids',
        'metro_station_ids',
        'neighborhood_ids',
        'status',
        'filter_is_vip',
        'filter_is_new',
        'filter_is_verified',
        'filter_has_video',
        'filter_is_cheapest',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age_filters' => 'array',
        'weight_filters' => 'array',
        'size_filters' => 'array',
        'hair_color_filters' => 'array',
        'price_filters' => 'array',
        'height_filters' => 'array',
        'service_ids' => 'array',
        'metro_station_ids' => 'array',
        'neighborhood_ids' => 'array',
        'status' => 'boolean',
        'filter_is_vip' => 'boolean',
        'filter_is_new' => 'boolean',
        'filter_is_verified' => 'boolean',
        'filter_has_video' => 'boolean',
        'filter_is_cheapest' => 'boolean',
    ];

    /**
     * Get the services associated with this custom category.
     */
    public function getServicesAttribute()
    {
        return Service::whereIn('id', $this->service_ids ?? [])->get();
    }

    /**
     * Get the metro stations associated with this custom category.
     */
    public function getMetroStationsAttribute()
    {
        return MetroStation::whereIn('id', $this->metro_station_ids ?? [])->get();
    }

    /**
     * Get the neighborhoods associated with this custom category.
     */
    public function getNeighborhoodsAttribute()
    {
        return Neighborhood::whereIn('id', $this->neighborhood_ids ?? [])->get();
    }

    /**
     * Get profiles that match the criteria of this custom category.
     * 
     * @param bool $returnQuery If true, returns the query builder instead of the collection
     * @return \Illuminate\Database\\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection
     */
    public function getMatchingProfiles($returnQuery = false)
    {
        $query = Profile::query()->where('is_active', true);

        // Filter by age
        if (!empty($this->age_filters)) {
            $query->where(function($q) {
                foreach ($this->age_filters as $ageFilter) {
                    switch ($ageFilter) {
                        case 'age-under-22':
                            $q->orWhere('age', '<=', 22);
                            break;
                        case 'age-18':
                            $q->orWhere('age', '=', 18);
                            break;
                        case 'age-under-20':
                            $q->orWhere('age', '<=', 20);
                            break;
                        case 'age-under-25':
                            $q->orWhere('age', '<=', 25);
                            break;
                        case 'age-under-30':
                            $q->orWhere('age', '<=', 30);
                            break;
                        case 'age-30-35':
                            $q->orWhereBetween('age', [30, 35]);
                            break;
                        case 'age-35-40':
                            $q->orWhereBetween('age', [35, 40]);
                            break;
                        case 'age-28-40':
                            $q->orWhereBetween('age', [28, 40]);
                            break;
                        case 'age-over-40':
                            $q->orWhere('age', '>', 40);
                            break;
                    }
                }
            });
        }

        // Filter by weight
        if (!empty($this->weight_filters)) {
            $query->where(function($q) {
                foreach ($this->weight_filters as $weightFilter) {
                    switch ($weightFilter) {
                        case 'weight-under-45':
                            $q->orWhere('weight', '<=', 45);
                            break;
                        case 'weight-under-50':
                            $q->orWhere('weight', '<=', 50);
                            break;
                        case 'weight-50-65':
                            $q->orWhereBetween('weight', [50, 65]);
                            break;
                        case 'weight-over-65':
                            $q->orWhere('weight', '>', 65);
                            break;
                    }
                }
            });
        }

        // Filter by height
        if (!empty($this->height_filters)) {
            $query->where(function($q) {
                foreach ($this->height_filters as $heightFilter) {
                    switch ($heightFilter) {
                        case 'height-under-150':
                            $q->orWhere('height', '<=', 150);
                            break;
                        case 'height-under-165':
                            $q->orWhere('height', '<=', 165);
                            break;
                        case 'height-165-180':
                            $q->orWhereBetween('height', [165, 180]);
                            break;
                        case 'height-over-180':
                            $q->orWhere('height', '>', 180);
                            break;
                    }
                }
            });
        }

        // Filter by size
        if (!empty($this->size_filters)) {
            $query->where(function($q) {
                foreach ($this->size_filters as $sizeFilter) {
                    switch ($sizeFilter) {
                        case 'size-under-1':
                            $q->orWhere('size', '<', 1);
                            break;
                        case 'size-1-2':
                            $q->orWhereBetween('size', [1, 2]);
                            break;
                        case 'size-2-3':
                            $q->orWhereBetween('size', [2, 3]);
                            break;
                        case 'size-over-3':
                            $q->orWhere('size', '>', 3);
                            break;
                    }
                }
            });
        }

        // Filter by hair color
        if (!empty($this->hair_color_filters)) {
            $query->where(function($q) {
                foreach ($this->hair_color_filters as $hairColor) {
                    switch ($hairColor) {
                        case 'blondes':
                            $q->orWhere('hair_color', 'Блондинка');
                            break;
                        case 'brunettes':
                            $q->orWhere('hair_color', 'Брюнетка');
                            break;
                        case 'brown-haired':
                            $q->orWhere('hair_color', 'Шатенка');
                            break;
                        case 'Wolverines':
                            $q->orWhere('hair_color', 'Русая');
                            break;
                        default:
                            $q->orWhere('hair_color', 'Рыжая');
                            break;
                    }
                }
            });
        }

        // Filter by price
        if (!empty($this->price_filters)) {
            $query->where(function($q) {
                foreach ($this->price_filters as $priceFilter) {
                    switch ($priceFilter) {
                        case 'price-under-2000':
                            $q->orWhere('vyezd_1hour', '<=', 2000);
                            break;
                        case 'price-1500':
                            $q->orWhere('vyezd_1hour', '=', 1500);
                            break;
                        case 'price-under-2500':
                            $q->orWhere('vyezd_1hour', '<=', 2500);
                            break;
                        case 'price-under-5000':
                            $q->orWhere('vyezd_1hour', '<=', 5000);
                            break;
                        case 'price-under-8000':
                            $q->orWhere('vyezd_1hour', '<=', 8000);
                            break;
                        case 'price-over-5000':
                            $q->orWhere('vyezd_1hour', '>', 5000);
                            break;
                        case 'price-over-8000':
                            $q->orWhere('vyezd_1hour', '>', 8000);
                            break;
                        case 'price-over-10000':
                            $q->orWhere('vyezd_1hour', '>', 10000);
                            break;
                    }
                }
            });
        }

        // Filter by services
        if (!empty($this->service_ids)) {
            $query->whereHas('services', function($q) {
                $q->whereIn('services.id', $this->service_ids);
            });
        }

        // Filter by metro stations
        if (!empty($this->metro_station_ids)) {
            $query->whereHas('metroStations', function($q) {
                $q->whereIn('metro_stations.id', $this->metro_station_ids);
            });
        }

        // Filter by neighborhoods
        if (!empty($this->neighborhood_ids)) {
            $query->whereHas('neighborhoods', function($q) {
                $q->whereIn('neighborhoods.id', $this->neighborhood_ids);
            });
        }

        // Apply new boolean filters
        if ($this->filter_is_vip) {
            $query->isVip();
        }
        if ($this->filter_is_new) {
            $query->isNew();
        }
        if ($this->filter_is_verified) {
            $query->isVerified();
        }
        if ($this->filter_has_video) {
            $query->hasVideo();
        }
        if ($this->filter_is_cheapest) {
            $query->isCheap(); // Assuming default threshold or modify if CustomCategory needs its own threshold
        }

        // Return either the query builder or the collection of profiles
        // return $returnQuery ? $query : $query->get();
        return $query->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff']);
    }
}
