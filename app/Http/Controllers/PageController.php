<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\MetroStation;
use App\Models\Profile;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $filter = $request->input('filter', 'all');
        $sort = $request->input('sort', 'popular');
        $service = $request->input('service');
        $metro = $request->input('metro');
        $price = $request->input('price');
        $age = $request->input('age');


        // Start with base query
        $profilesQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
            ->where('profiles.is_active', true);

        // Apply filters based on request
        if ($service) {
            $profilesQuery->whereHas('services', function ($query) use ($service) {
                $query->where('name', $service);
            });
        }

        if ($metro) {
            $profilesQuery->whereHas('metroStations', function ($query) use ($metro) {
                $query->where('name', $metro);
            });
        }

        if ($price) {
            // Parse price range like "От 2000 до 3000 руб"
            $priceRange = preg_replace('/[^0-9-]/', '', $price);
            $prices = explode('-', $priceRange);

            if (count($prices) == 2) {
                $minPrice = (int) $prices[0];
                $maxPrice = (int) $prices[1];
                $profilesQuery->whereBetween('profiles.price', [$minPrice, $maxPrice]);
            }
        }

        if ($age) {
            // Parse age range like "18-20 лет"
            $ageRange = preg_replace('/[^0-9-]/', '', $age);
            $ages = explode('-', $ageRange);

            if (count($ages) == 2) {
                $minAge = (int) $ages[0];
                $maxAge = (int) $ages[1];
                $profilesQuery->whereBetween('profiles.age', [$minAge, $maxAge]);
            }
        }

        // Apply additional filters based on filter parameter
    switch ($filter) {
        case 'video':
            $profilesQuery->hasVideo();
            break;
        case 'new':
            $profilesQuery->isNew();
            break;
        case 'cheap':
            $profilesQuery->isCheap();
            break;
        case 'vip':
            $profilesQuery->isVip();
            break;
        case 'verified':
            $profilesQuery->isVerified();
            break;
    }

    // Apply sorting
    switch ($sort) {
        case 'popular':
            $profilesQuery->orderByDesc('profiles.views_count');
            break;
        case 'cheapest':
            $profilesQuery->orderBy('profiles.vyezd_1hour');
            break;
        case 'expensive':
            $profilesQuery->orderByDesc('profiles.vyezd_1hour');
            break;
        default:
            // Default sorting: VIP first, then by priority level, then by view count
            $profilesQuery->leftJoin('profile_ad_tariffs', 'profiles.id', '=', 'profile_ad_tariffs.profile_id')
                ->leftJoin('ad_tariffs', 'profile_ad_tariffs.ad_tariff_id', '=', 'ad_tariffs.id')
                ->select('profiles.*')
                ->orderByRaw('CASE WHEN ad_tariffs.slug = "vip" AND profile_ad_tariffs.is_paused = 0 THEN 1 ELSE 0 END DESC')
                ->orderByRaw('CASE WHEN ad_tariffs.slug = "priority" AND profile_ad_tariffs.is_paused = 0 THEN profile_ad_tariffs.priority_level ELSE 0 END DESC')
                ->orderByDesc('profiles.view_count');
    }

     // Get profiles with pagination
     $profiles = $profilesQuery->paginate(12);
    
     // Increment view count for all profiles in the current page
     foreach ($profiles as $profile) {
         $profile->incrementViewCount();
     }

        // Get all services with count of associated profiles
        $services = Service::withCount('profiles')
            ->orderBy('name')
            ->get()
            ->map(function ($service) {
                return [
                    'name' => $service->name,
                    'count' => $service->profiles_count
                ];
            });

        // Get metro stations grouped by first letter with count
        $metroStations = MetroStation::withCount('profiles')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($item) {
                // Ensure proper UTF-8 encoding for the first character
                return mb_strtoupper(mb_substr($item->name, 0, 1, 'UTF-8'), 'UTF-8');
            })
            ->map(function ($group) {
                return $group->map(function ($station) {
                    return [
                        // Ensure the name is properly encoded
                        'name' => mb_convert_encoding($station->name, 'UTF-8', 'UTF-8'),
                        'count' => $station->profiles_count
                    ];
                });
            })
            ->toArray();


        return view('home.index', compact('services', 'metroStations', 'profiles', 'filter', 'sort'));
    }

}
