<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\MetroStation;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $district = $request->input('district');


        // Start with base query for regular profiles
        $profilesQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
            ->where('profiles.is_active', true);
            
        // Create a separate query for VIP profiles
        $vipProfilesQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
            ->where('profiles.is_active', true)
            ->whereHas('activeAds', function($query) {
                $query->whereHas('adTariff', function($q) {
                    $q->where('slug', 'vip');
                })->where('is_paused', 0);
            });
            
        // Apply filters to regular profiles query
        if ($service) {
            $profilesQuery->whereHas('services', function ($query) use ($service) {
                $query->where('name', $service);
            });
            
            // Also apply service filter to VIP profiles for consistency, but we'll still include all VIPs later
            $vipProfilesQuery->whereHas('services', function ($query) use ($service) {
                $query->where('name', $service);
            });
        }

        if ($metro) {
            $profilesQuery->whereHas('metroStations', function ($query) use ($metro) {
                $query->where('name', $metro);
            });
            
            $vipProfilesQuery->whereHas('metroStations', function ($query) use ($metro) {
                $query->where('name', $metro);
            });
        }
        
        if ($district) {
            $profilesQuery->whereHas('neighborhoods', function ($query) use ($district) {
                $query->where('name', $district);
            });
            
            $vipProfilesQuery->whereHas('neighborhoods', function ($query) use ($district) {
                $query->where('name', $district);
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
                $vipProfilesQuery->whereBetween('profiles.price', [$minPrice, $maxPrice]);
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
                $vipProfilesQuery->whereBetween('profiles.age', [$minAge, $maxAge]);
            }
        }

        // Apply additional filters based on filter parameter to regular profiles query
    if ($filter !== 'vip') {
        switch ($filter) {
            case 'video':
                $profilesQuery->hasVideo();
                // Also apply to VIP profiles for consistency
                $vipProfilesQuery->hasVideo();
                break;
            case 'new':
                $profilesQuery->isNew();
                $vipProfilesQuery->isNew();
                break;
            case 'cheap':
                $profilesQuery->isCheap();
                $vipProfilesQuery->isCheap();
                break;
            case 'verified':
                $profilesQuery->isVerified();
                $vipProfilesQuery->isVerified();
                break;
        }
    } else {
        // For VIP filter, we'll only use the VIP profiles query
        $profilesQuery = $vipProfilesQuery;
        $vipProfilesQuery = null; // No need for a separate VIP query in this case
    }

    // Prepare both queries with the same joins and selects
    $profilesQuery->leftJoin('profile_ad_tariffs', 'profiles.id', '=', 'profile_ad_tariffs.profile_id')
        ->leftJoin('ad_tariffs', 'profile_ad_tariffs.ad_tariff_id', '=', 'ad_tariffs.id')
        ->select('profiles.*')
        ->distinct();
    
    // If we're not in VIP-only mode, prepare the VIP query the same way
    if ($vipProfilesQuery) {
        $vipProfilesQuery->leftJoin('profile_ad_tariffs', 'profiles.id', '=', 'profile_ad_tariffs.profile_id')
            ->leftJoin('ad_tariffs', 'profile_ad_tariffs.ad_tariff_id', '=', 'ad_tariffs.id')
            ->select('profiles.*')
            ->distinct();
            
        // Combine the VIP profiles with the filtered profiles using union
        $profilesQuery = $profilesQuery->union($vipProfilesQuery);
    }
    
    // Apply sorting to the combined query
    $profilesQuery = Profile::from(DB::raw("({$profilesQuery->toSql()}) as profiles"))
        ->mergeBindings($profilesQuery->getQuery())
        ->leftJoin('profile_ad_tariffs', 'profiles.id', '=', 'profile_ad_tariffs.profile_id')
        ->leftJoin('ad_tariffs', 'profile_ad_tariffs.ad_tariff_id', '=', 'ad_tariffs.id')
        // Always prioritize VIP profiles first
        ->orderByRaw('CASE WHEN ad_tariffs.slug = "vip" AND profile_ad_tariffs.is_paused = 0 THEN 1 ELSE 0 END DESC')
        // Then prioritize by priority level for priority tariffs
        ->orderByRaw('CASE WHEN ad_tariffs.slug = "priority" AND profile_ad_tariffs.is_paused = 0 THEN profile_ad_tariffs.priority_level ELSE 0 END DESC');
        
    // Secondary sorting after VIP and priority
    switch ($sort) {
        case 'popular':
            $profilesQuery->orderByDesc('profiles.views_count');
            break;
        case 'cheapest':
            // Sort by lowest price first
            $profilesQuery->orderBy('profiles.vyezd_1hour');
            break;
        case 'expensive':
            $profilesQuery->orderByDesc('profiles.vyezd_1hour');
            break;
        default:
            // Default sorting by view count after VIP and priority sorting
            $profilesQuery->orderByDesc('profiles.views_count');
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

    public function profileClick($id)
{
    $profile = Profile::findOrFail($id);
    $profile->incrementClickCount();
    
    return redirect()->route('profile.show', $profile->id);
}

public function show($id)
{
    $profile = Profile::with(['metroStations', 'services', 'images', 'video'])->findOrFail($id);
    $profile->incrementViewCount();

    return view( 'profiles.profile', compact('profile'));

}

}
