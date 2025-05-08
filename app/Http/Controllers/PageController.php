<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\MetroStation;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index(Request $request, $slug = null)
    {
        // Get filter parameters
        $filter = $request->input('filter', 'all');
        $sort = $request->input('sort');
        $service = null;
        $metro = null;
        $price = null;
        $age = null;
        $district = $request->input('district');
        
        // Check if we're using directory-style URLs
        $routeName = $request->route()->getName();
        if ($routeName && $slug) {
            // Use the appropriate filter based on route name
            switch ($routeName) {
                case 'home.service':
                    $service = $slug; // Use slug directly instead of deslugifying
                    break;
                case 'home.metro':
                    $metro = $slug; // Use slug directly instead of deslugifying
                    break;
                case 'home.price':
                    $price = \App\Helpers\UrlHelper::deslugify($slug); // Still deslugify for price
                    break;
                case 'home.age':
                    $age = \App\Helpers\UrlHelper::deslugify($slug); // Still deslugify for age
                    break;
            }
        } else {
            // For backward compatibility, still check query parameters
            $service = $request->input('service');
            $metro = $request->input('metro');
            $price = $request->input('price');
            $age = $request->input('age');
        }

        // First, get all VIP profiles regardless of filters
        $vipQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
            ->where('profiles.is_active', true)
            ->where('is_vip', true);

        // Now create the filtered query for non-VIP profiles
        $filteredQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
            ->where('profiles.is_active', true);

        // Apply all filters to the filtered query
        if ($service) {
            $filteredQuery->whereHas('services', function ($query) use ($service) {
                $query->where('slug', $service);
            });
        }

        if ($metro) {
            $filteredQuery->whereHas('metroStations', function ($query) use ($metro) {
                $query->where('slug', $metro);
            });
        }

        if ($district) {
            $filteredQuery->whereHas('neighborhoods', function ($query) use ($district) {
                $query->where('name', $district);
            });
        }

        if ($price) {
            // Parse price range like "От 2000 до 3000 руб"
            preg_match_all('/\d+/', $price, $matches);
            if (count($matches[0]) === 2) {
                $minPrice = (int)$matches[0][0];
                $maxPrice = (int)$matches[0][1];
                $filteredQuery->whereBetween('profiles.vyezd_1hour', [$minPrice, $maxPrice]);
            }
        }

        if ($age) {
            preg_match_all('/\d+/', $age, $matches);
            if (count($matches[0]) === 2) {
                $minAge = (int)$matches[0][0];
                $maxAge = (int)$matches[0][1];
                $filteredQuery->whereBetween('profiles.age', [$minAge, $maxAge]);
            }
        }

        // Apply additional filters based on filter parameter (except for VIP filter)
        if ($filter !== 'vip') {
            switch ($filter) {
                case 'video':
                    $filteredQuery->hasVideo();
                    break;
                case 'new':
                    $filteredQuery->isNew();
                    break;
                case 'cheap':
                    $filteredQuery->isCheap();
                    break;
                case 'verified':
                    $filteredQuery->isVerified();
                    break;
            }

            // For non-VIP filter, exclude VIP profiles from filtered query to avoid duplicates
            $filteredQuery->where('is_vip', false);
        }

        // For VIP filter, we'll only use the VIP query
        if ($filter === 'vip' && !$sort) {
            // Just use the VIP query directly
            $profiles = $vipQuery
                ->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
                ->withCount(['activeAds as vip_score' => function ($query) {
                    $query->whereHas('adTariff', fn($q) => $q->where('slug', 'vip'))
                        ->where('is_paused', 0);
                }])
                ->withCount(['activeAds as priority_score' => function ($query) {
                    $query->whereHas('adTariff', fn($q) => $q->where('slug', 'priority'))
                        ->where('is_paused', 0)
                        ->select(DB::raw('MAX(priority_level)'));
                }])
                ->orderByDesc('vip_score')
                ->orderByDesc('priority_score')
                ->paginate(12);
        } else {
            // When sorting is applied, ignore VIP and priority conditions
            if ($sort) {
                // Combine VIP and non-VIP profiles for sorting
                $combinedQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
                    ->where('profiles.is_active', true);
                
                // Apply all the same filters to the combined query
                if ($service) {
                    $combinedQuery->whereHas('services', function ($query) use ($service) {
                        $query->where('slug', $service);
                    });
                }
                
                if ($metro) {
                    $combinedQuery->whereHas('metroStations', function ($query) use ($metro) {
                        $query->where('slug', $metro);
                    });
                }
                
                if ($district) {
                    $combinedQuery->whereHas('neighborhoods', function ($query) use ($district) {
                        $query->where('name', $district);
                    });
                }
                
                if ($price) {
                    preg_match_all('/\d+/', $price, $matches);
                    if (count($matches[0]) === 2) {
                        $minPrice = (int)$matches[0][0];
                        $maxPrice = (int)$matches[0][1];
                        $combinedQuery->whereBetween('profiles.vyezd_1hour', [$minPrice, $maxPrice]);
                    }
                }
                
                if ($age) {
                    preg_match_all('/\d+/', $age, $matches);
                    if (count($matches[0]) === 2) {
                        $minAge = (int)$matches[0][0];
                        $maxAge = (int)$matches[0][1];
                        $combinedQuery->whereBetween('profiles.age', [$minAge, $maxAge]);
                    }
                }
                
                // Apply additional filters based on filter parameter
                if ($filter !== 'all' && $filter !== 'vip') {
                    switch ($filter) {
                        case 'video':
                            $combinedQuery->hasVideo();
                            break;
                        case 'new':
                            $combinedQuery->isNew();
                            break;
                        case 'cheap':
                            $combinedQuery->isCheap();
                            break;
                        case 'verified':
                            $combinedQuery->isVerified();
                            break;
                    }
                }
                
                // Apply sorting to all profiles
                switch ($sort) {
                    case 'popular':
                        $combinedQuery->orderByDesc('profiles.views_count');
                        break;
                    case 'cheapest':
                        $combinedQuery->orderBy('profiles.vyezd_1hour');
                        break;
                    case 'expensive':
                        $combinedQuery->orderByDesc('profiles.vyezd_1hour');
                        break;
                    default:
                        $combinedQuery->orderByDesc('profiles.views_count');
                }
                
                // Paginate the combined results
                $profiles = $combinedQuery->paginate(12);
            } else {
                // For all other filters without sorting, we'll handle VIP and non-VIP profiles separately
                // Apply sorting only to non-VIP profiles
                $filteredQuery->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
                    ->withCount(['activeAds as priority_score' => function ($query) {
                        $query->whereHas('adTariff', fn($q) => $q->where('slug', 'priority'))
                            ->where('is_paused', 0)
                            ->select(DB::raw('MAX(priority_level)'));
                    }])
                    ->orderByDesc('priority_score')
                    ->orderByDesc('profiles.views_count');

                // Get VIP profiles first (with their own sorting by vip_score and priority_score)
                $vipProfiles = $vipQuery
                    ->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
                    ->withCount(['activeAds as vip_score' => function ($query) {
                        $query->whereHas('adTariff', fn($q) => $q->where('slug', 'vip'))
                            ->where('is_paused', 0);
                    }])
                    ->withCount(['activeAds as priority_score' => function ($query) {
                        $query->whereHas('adTariff', fn($q) => $q->where('slug', 'priority'))
                            ->where('is_paused', 0)
                            ->select(DB::raw('MAX(priority_level)'));
                    }])
                    ->orderByDesc('vip_score')
                    ->orderByDesc('priority_score')
                    ->get();

                // Get non-VIP profiles with applied sorting
                $filteredProfiles = $filteredQuery->get();

                // Merge collections with VIP profiles first, then paginate
                $allProfiles = $vipProfiles->concat($filteredProfiles);
                $profiles = new LengthAwarePaginator(
                    $allProfiles->forPage(request()->input('page', 1), 12),
                    $allProfiles->count(),
                    12,
                    request()->input('page', 1),
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            }
        }

        // Increment view count for displayed profiles
        foreach ($profiles as $profile) {
            if (method_exists($profile, 'incrementViewCount')) {
                $profile->incrementViewCount();
            } else {
                $profile->increment('views_count');
            }
        }

        // Sidebar: Services
        $services = Service::withCount(['profiles as profiles_count' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name')
            ->get()
            ->map(function($s) {
                // Generate slug if it doesn't exist in the database
                $slug = isset($s->slug) ? $s->slug : \App\Helpers\UrlHelper::slugify($s->name);
                return [
                    'name' => $s->name, 
                    'slug' => $slug,
                    'count' => $s->profiles_count
                ];
            });

        // Sidebar: Metro stations grouped by alphabet
        $metroStations = MetroStation::withCount(['profiles as profiles_count' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name')
            ->get()
            ->groupBy(fn($item) => mb_strtoupper(mb_substr($item->name, 0, 1, 'UTF-8')))
            ->map(fn($group) => $group->map(fn($station) => [
                'name' => $station->name,
                'slug' => $station->slug,
                'count' => $station->profiles_count
            ]))
            ->toArray();


        return view('home.index', compact('services', 'metroStations', 'profiles', 'filter', 'sort'));
    }


    public function profileClick($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->incrementClickCount();

        return redirect()->route('profiles.view', $profile->id);
    }

    public function show($id)
    {
        $profile = Profile::with(['metroStations', 'services', 'images', 'video'])->findOrFail($id);
        $profile->incrementViewCount();

        return view('profiles.profile', compact('profile'));
    }
}


// public function index(Request $request)
//     {
//         // Get filter parameters
//         $filter = $request->input('filter', 'all');
//         $sort = $request->input('sort');
//         $service = $request->input('service');
//         $metro = $request->input('metro');
//         $price = $request->input('price');
//         $age = $request->input('age');
//         $district = $request->input('district');

//         // First, get all VIP profiles regardless of filters
//         $vipQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
//             ->where('profiles.is_active', true)
//             ->where('is_vip', true);

//         // Now create the filtered query for non-VIP profiles
//         $filteredQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
//             ->where('profiles.is_active', true);

//         // Apply all filters to the filtered query
//         if ($service) {
//             $filteredQuery->whereHas('services', function ($query) use ($service) {
//                 $query->where('name', $service);
//             });
//         }

//         if ($metro) {
//             $filteredQuery->whereHas('metroStations', function ($query) use ($metro) {
//                 $query->where('name', $metro);
//             });
//         }

//         if ($district) {
//             $filteredQuery->whereHas('neighborhoods', function ($query) use ($district) {
//                 $query->where('name', $district);
//             });
//         }

//         if ($price) {
//             // Parse price range like "От 2000 до 3000 руб"
//             preg_match_all('/\d+/', $price, $matches);
//             if (count($matches[0]) === 2) {
//                 $minPrice = (int)$matches[0][0];
//                 $maxPrice = (int)$matches[0][1];
//                 $filteredQuery->whereBetween('profiles.vyezd_1hour', [$minPrice, $maxPrice]);
//             }
//         }

//         if ($age) {
//             preg_match_all('/\d+/', $age, $matches);
//             if (count($matches[0]) === 2) {
//                 $minAge = (int)$matches[0][0];
//                 $maxAge = (int)$matches[0][1];
//                 $filteredQuery->whereBetween('profiles.age', [$minAge, $maxAge]);
//             }
//         }

//         // Apply additional filters based on filter parameter (except for VIP filter)
//         if ($filter !== 'vip') {
//             switch ($filter) {
//                 case 'video':
//                     $filteredQuery->hasVideo();
//                     break;
//                 case 'new':
//                     $filteredQuery->isNew();
//                     break;
//                 case 'cheap':
//                     $filteredQuery->isCheap();
//                     break;
//                 case 'verified':
//                     $filteredQuery->isVerified();
//                     break;
//             }

//             // For non-VIP filter, exclude VIP profiles from filtered query to avoid duplicates
//             $filteredQuery->where('is_vip', false);
//         }

//         // For VIP filter, we'll only use the VIP query
//         if ($filter === 'vip') {
//             // Just use the VIP query directly
//             $profiles = $vipQuery
//                 ->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
//                 ->withCount(['activeAds as vip_score' => function ($query) {
//                     $query->whereHas('adTariff', fn($q) => $q->where('slug', 'vip'))
//                         ->where('is_paused', 0);
//                 }])
//                 ->withCount(['activeAds as priority_score' => function ($query) {
//                     $query->whereHas('adTariff', fn($q) => $q->where('slug', 'priority'))
//                         ->where('is_paused', 0)
//                         ->select(DB::raw('MAX(priority_level)'));
//                 }])
//                 ->orderByDesc('vip_score')
//                 ->orderByDesc('priority_score')
//                 ->paginate(12);
//         } else {
//             // For all other filters, we'll handle VIP and non-VIP profiles separately
//             // Apply sorting only to non-VIP profiles
//             $filteredQuery->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
//                 ->withCount(['activeAds as priority_score' => function ($query) {
//                     $query->whereHas('adTariff', fn($q) => $q->where('slug', 'priority'))
//                         ->where('is_paused', 0)
//                         ->select(DB::raw('MAX(priority_level)'));
//                 }])
//                 ->orderByDesc('priority_score');

//             // Apply sorting only to non-VIP profiles
//             switch ($sort) {
//                 case 'popular':
//                     $filteredQuery->orderByDesc('profiles.views_count');
//                     break;
//                 case 'cheapest':
//                     $filteredQuery->orderBy('profiles.vyezd_1hour');
//                     break;
//                 case 'expensive':
//                     $filteredQuery->orderByDesc('profiles.vyezd_1hour');
//                     break;
//                 default:
//                     $filteredQuery->orderByDesc('profiles.views_count');
//             }

//             // Get VIP profiles first (with their own sorting by vip_score and priority_score)
//             $vipProfiles = $vipQuery
//                 ->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
//                 ->withCount(['activeAds as vip_score' => function ($query) {
//                     $query->whereHas('adTariff', fn($q) => $q->where('slug', 'vip'))
//                         ->where('is_paused', 0);
//                 }])
//                 ->withCount(['activeAds as priority_score' => function ($query) {
//                     $query->whereHas('adTariff', fn($q) => $q->where('slug', 'priority'))
//                         ->where('is_paused', 0)
//                         ->select(DB::raw('MAX(priority_level)'));
//                 }])
//                 ->orderByDesc('vip_score')
//                 ->orderByDesc('priority_score')
//                 ->get();

//             // Get non-VIP profiles with applied sorting
//             $filteredProfiles = $filteredQuery->get();

//             // Merge collections with VIP profiles first, then paginate
//             $allProfiles = $vipProfiles->concat($filteredProfiles);
//             $profiles = new LengthAwarePaginator(
//                 $allProfiles->forPage(request()->input('page', 1), 12),
//                 $allProfiles->count(),
//                 12,
//                 request()->input('page', 1),
//                 ['path' => request()->url(), 'query' => request()->query()]
//             );
//         }

//         // Increment view count for displayed profiles
//         foreach ($profiles as $profile) {
//             if (method_exists($profile, 'incrementViewCount')) {
//                 $profile->incrementViewCount();
//             } else {
//                 $profile->increment('views_count');
//             }
//         }

//         // Sidebar: Services
//         $services = Service::withCount(['profiles as profiles_count' => function ($query) {
//             $query->where('is_active', true);
//         }])
//             ->orderBy('name')
//             ->get()
//             ->map(fn($s) => ['name' => $s->name, 'count' => $s->profiles_count]);

//         // Sidebar: Metro stations grouped by alphabet
//         $metroStations = MetroStation::withCount(['profiles as profiles_count' => function ($query) {
//             $query->where('is_active', true);
//         }])
//             ->orderBy('name')
//             ->get()
//             ->groupBy(fn($item) => mb_strtoupper(mb_substr($item->name, 0, 1, 'UTF-8')))
//             ->map(fn($group) => $group->map(fn($station) => [
//                 'name' => $station->name,
//                 'count' => $station->profiles_count
//             ]))
//             ->toArray();


//         return view('home.index', compact('services', 'metroStations', 'profiles', 'filter', 'sort'));
//     }