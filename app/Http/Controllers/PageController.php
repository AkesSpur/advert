<?php

namespace App\Http\Controllers;

use App\Models\CustomCategory;
use App\Models\FooterMenu;
use App\Models\Neighborhood;
use App\Models\Service;
use App\Models\MetroStation;
use App\Models\Profile;
use App\Models\SeoTemplate;
use App\Models\TopMenu;
use App\Models\HairColor; // Added
use App\Models\Height;    // Added
use App\Models\Weight;    // Added
use App\Models\Size;      // Added
use App\Models\Price;     // Added
use App\Models\Age;       // Added
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
        $serviceSlug = null;
        $metroSlug = null;
        $priceValue = null;
        $ageValue = null;
        $hairColorValue = null;
        $heightValue = null;
        $weightValue = null;
        $sizeValue = null;
        $neighborhoodSlug = null;
        $district = $request->input('district');

        // SEO variables
        $seoTitle = null;
        $seoMetaDescription = null;
        $seoH1 = null;
        $pageType = 'search_results'; // Default page type for SEO

        // Check if we're using directory-style URLs
        $routeName = $request->route()->getName();
        if ($routeName && $slug) {
            switch ($routeName) {
                case 'home.service':
                    $serviceSlug = $slug;
                    $serviceModel = Service::where('slug', $serviceSlug)->first();
                    if ($serviceModel) {
                        $seoTitle = $serviceModel->title;
                        $seoMetaDescription = $serviceModel->meta_description;
                        $seoH1 = $serviceModel->h1_header;
                        $pageType = 'service';
                    }
                    break;
                case 'home.metro':
                    $metroSlug = $slug;
                    $metroModel = MetroStation::where('slug', $metroSlug)->first();
                    if ($metroModel) {
                        $seoTitle = $metroModel->title;
                        $seoMetaDescription = $metroModel->meta_description;
                        $seoH1 = $metroModel->h1_header;
                        $pageType = 'metro';
                    }
                    break;
                case 'home.price':
                    $priceValue = $slug; // $slug is the 'value' from the URL
                    $priceModel = Price::where('value', $priceValue)->first();
                    if ($priceModel) {
                        $seoTitle = $priceModel->title;
                        $seoMetaDescription = $priceModel->meta_description;
                        $seoH1 = $priceModel->h1_header;
                        $pageType = 'price';
                    }
                    break;
                case 'home.age':
                    $ageValue = $slug; // $slug is the 'value' from the URL
                    $ageModel = Age::where('value', $ageValue)->first();
                    if ($ageModel) {
                        $seoTitle = $ageModel->title;
                        $seoMetaDescription = $ageModel->meta_description;
                        $seoH1 = $ageModel->h1_header;
                        $pageType = 'age';
                    }
                    break;
                case 'home.hair_color':
                    $hairColorValue = $slug; // $slug is the 'value' from the URL                    
                    $hairColorModel = HairColor::where('value', $hairColorValue)->first();
                    $hairColorValue = $hairColorModel->type; 
                    if ($hairColorModel) {
                        $seoTitle = $hairColorModel->title;
                        $seoMetaDescription = $hairColorModel->meta_description;
                        $seoH1 = $hairColorModel->h1_header;
                        $pageType = 'hair_color';
                    }
                    break;
                case 'home.height':
                    $heightValue = $slug; // $slug is the 'value' from the URL
                    $heightModel = Height::where('value', $heightValue)->first();
                    if ($heightModel) {
                        $seoTitle = $heightModel->title;
                        $seoMetaDescription = $heightModel->meta_description;
                        $seoH1 = $heightModel->h1_header;
                        $pageType = 'height';
                    }
                    break;
                case 'home.weight':
                    $weightValue = $slug; // $slug is the 'value' from the URL
                    $weightModel = Weight::where('value', $weightValue)->first();
                    if ($weightModel) {
                        $seoTitle = $weightModel->title;
                        $seoMetaDescription = $weightModel->meta_description;
                        $seoH1 = $weightModel->h1_header;
                        $pageType = 'weight';
                    }
                    break;
                case 'home.breast-size':
                    $sizeValue = $slug; // $slug is the 'value' from the URL
                    $sizeModel = Size::where('value', $sizeValue)->first();
                    if ($sizeModel) {
                        $seoTitle = $sizeModel->title;
                        $seoMetaDescription = $sizeModel->meta_description;
                        $seoH1 = $sizeModel->h1_header;
                        $pageType = 'size';
                    }
                    break;
                case 'home.neighborhood':
                    $neighborhoodSlug = $slug;
                    $neighborhoodModel = Neighborhood::where('slug', $neighborhoodSlug)->first();
                    if ($neighborhoodModel) {
                        $seoTitle = $neighborhoodModel->title;
                        $seoMetaDescription = $neighborhoodModel->meta_description;
                        $seoH1 = $neighborhoodModel->h1_header;
                        $pageType = 'neighborhood';
                    }
                    break;
            }
        } else {
            // For backward compatibility, still check query parameters
            $serviceSlug = $request->input('service');
            $metroSlug = $request->input('metro');
            $priceValue = $request->input('price');
            $ageValue = $request->input('age');
            $heightValue = $request->input('height');
            $weightValue = $request->input('weight');
            $sizeValue = $request->input('size');
            $neighborhoodSlug = $request->input('neighborhood');

            if ($request->input('hair_color')) {
                $hairColorModel = HairColor::where('value', $request->input('hair_color'))->first();
                $hairColorValue = $hairColorModel->type;
            }
        }

        // First, get all VIP profiles regardless of filters
        $vipQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
            ->where('profiles.is_active', true)
            ->where('is_vip', true);

        // Now create the filtered query for non-VIP profiles
        $filteredQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
            ->where('profiles.is_active', true);

        // Apply all filters to the filtered query
        if ($serviceSlug) {
            $filteredQuery->whereHas('services', function ($query) use ($serviceSlug) {
                $query->where('slug', $serviceSlug);
            });
        }

        if ($metroSlug) {
            $filteredQuery->whereHas('metroStations', function ($query) use ($metroSlug) {
                $query->where('slug', $metroSlug);
            });
        }

        if ($hairColorValue) {
            // Assuming $hairColorValue is the actual value stored in the 'hair_color' column of the 'profiles' table
            // If 'hair_colors.value' is different from 'profiles.hair_color', adjust this logic
            $filteredQuery->where('hair_color', $hairColorValue);
        }

        if ($heightValue) {
            // The $heightValue is the 'value' from the 'heights' table (e.g., 'height-under-150')
            // We need to map this value to the actual height conditions
            $heightFilter = Height::where('value', $heightValue)->first();
            if ($heightFilter) {
                // Example: if $heightFilter->name is "До 150 см" and $heightFilter->value is "height-under-150"
                // You'll need to parse the 'name' or have specific logic based on 'value'
                // For now, using the existing switch logic but it should ideally use the $heightFilter properties
                switch ($heightValue) { // This should ideally use $heightFilter->value or a mapping
                    case 'height-under-150':
                        $filteredQuery->where('height', '<=', 150);
                        break;
                    case 'height-under-165':
                        $filteredQuery->where('height', '<=', 165);
                        break;
                    case 'height-165-180':
                        $filteredQuery->whereBetween('height', [165, 180]);
                        break;
                    case 'height-over-180':
                        $filteredQuery->where('height', '>', 180);
                        break;
                }
            }
        }

        if ($weightValue) {
            $weightFilter = Weight::where('value', $weightValue)->first();
            if ($weightFilter) {
                switch ($weightValue) {
                    case 'weight-under-45':
                        $filteredQuery->where('weight', '<=', 45);
                        break;
                    case 'weight-under-50':
                        $filteredQuery->where('weight', '<=', 50);
                        break;
                    case 'weight-50-65':
                        $filteredQuery->whereBetween('weight', [50, 65]);
                        break;
                    case 'weight-over-65':
                        $filteredQuery->where('weight', '>', 65);
                        break;
                }
            }
        }

        if ($sizeValue) {
            $sizeFilter = Size::where('value', $sizeValue)->first();
            if ($sizeFilter) {
                switch ($sizeValue) {
                    case 'size-under-1':
                        $filteredQuery->where('size', '<', 1);
                        break;
                    case 'size-1-2':
                        $filteredQuery->whereBetween('size', [1, 2]);
                        break;
                    case 'size-2-3':
                        $filteredQuery->whereBetween('size', [2, 3]);
                        break;
                    case 'size-over-3':
                        $filteredQuery->where('size', '>', 3);
                        break;
                }
            }
        }

        if ($neighborhoodSlug) {
            $filteredQuery->whereHas('neighborhoods', function ($query) use ($neighborhoodSlug) {
                $query->where('slug', $neighborhoodSlug);
            });
        }

        if ($district) {
            $filteredQuery->whereHas('neighborhoods', function ($query) use ($district) {
                $query->where('name', $district); // Assuming district name is unique enough
            });
        }

        if ($priceValue) {
            $priceFilter = Price::where('value', $priceValue)->first();
            if ($priceFilter) {
                switch ($priceValue) {
                    case 'price-under-2000':
                        $filteredQuery->where('profiles.vyezd_1hour', '<=', 2000);
                        break;
                    case 'price-1500':
                        $filteredQuery->where('profiles.vyezd_1hour', '=', 1500);
                        break;
                    case 'price-under-2500':
                        $filteredQuery->where('profiles.vyezd_1hour', '<=', 2500);
                        break;
                    case 'price-under-5000':
                        $filteredQuery->where('profiles.vyezd_1hour', '<=', 5000);
                        break;
                    case 'price-under-8000':
                        $filteredQuery->where('profiles.vyezd_1hour', '<=', 8000);
                        break;
                    case 'price-over-5000':
                        $filteredQuery->where('profiles.vyezd_1hour', '>', 5000);
                        break;
                    case 'price-over-8000':
                        $filteredQuery->where('profiles.vyezd_1hour', '>', 8000);
                        break;
                    case 'price-over-10000':
                        $filteredQuery->where('profiles.vyezd_1hour', '>', 10000);
                        break;
                }
            }
        }

        // Handle age filter
        if ($ageValue) {
            $ageFilter = Age::where('value', $ageValue)->first();
            if ($ageFilter) {
                switch ($ageValue) {
                    case 'age-under-22':
                        $filteredQuery->where('profiles.age', '<=', 22);
                        break;
                    case 'age-18':
                        $filteredQuery->where('profiles.age', '=', 18);
                        break;
                    case 'age-under-20':
                        $filteredQuery->where('profiles.age', '<=', 20);
                        break;
                    case 'age-under-25':
                        $filteredQuery->where('profiles.age', '<=', 25);
                        break;
                    case 'age-under-30':
                        $filteredQuery->where('profiles.age', '<=', 30);
                        break;
                    case 'age-30-35':
                        $filteredQuery->whereBetween('profiles.age', [30, 35]);
                        break;
                    case 'age-35-40':
                        $filteredQuery->whereBetween('profiles.age', [35, 40]);
                        break;
                    case 'age-28-40':
                        $filteredQuery->whereBetween('profiles.age', [28, 40]);
                        break;
                    case 'age-over-40':
                        $filteredQuery->where('profiles.age', '>', 40);
                        break;
                }
            }
        }

        // Handle price filter
        $price = request('price');
        if ($price) {
            preg_match_all('/\d+/', $price, $matches);
            if (count($matches[0]) === 2) {
                $minPrice = (int)$matches[0][0];
                $maxPrice = (int)$matches[0][1];
                $filteredQuery->where(function($query) use ($minPrice, $maxPrice) {
                    $query->whereBetween('appartamenti_1hour', [$minPrice, $maxPrice])
                          ->orWhereBetween('vyezd_1hour', [$minPrice, $maxPrice]);
                });
            }
        }

        // Handle height filter
        $height = request('height');
        if ($height) {
            preg_match_all('/\d+/', $height, $matches);
            if (count($matches[0]) === 2) {
                $minHeight = (int)$matches[0][0];
                $maxHeight = (int)$matches[0][1];
                $filteredQuery->whereBetween('height', [$minHeight, $maxHeight]);
            }
        }

        // Handle weight filter
        $weight = request('weight');
        if ($weight) {
            preg_match_all('/\d+/', $weight, $matches);
            if (count($matches[0]) === 2) {
                $minWeight = (int)$matches[0][0];
                $maxWeight = (int)$matches[0][1];
                $filteredQuery->whereBetween('weight', [$minWeight, $maxWeight]);
            }
        }

        // Handle size (breast size) filter
        $size = request('size');
        if ($size) {
            $filteredQuery->where('size', $size);
        }

        // Handle hair color filter
        $hairColor = request('hair-color');
        if ($hairColor) {
            $filteredQuery->where('hair_color', $hairColor);
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
                if ($serviceSlug) {
                    $combinedQuery->whereHas('services', function ($query) use ($serviceSlug) {
                        $query->where('slug', $serviceSlug);
                    });
                }
                
                if ($metroSlug) {
                    $combinedQuery->whereHas('metroStations', function ($query) use ($metroSlug) {
                        $query->where('slug', $metroSlug);
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
                
                if ($ageValue) {
                    preg_match_all('/\d+/', $ageValue, $matches);
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
                $profiles = $combinedQuery->paginate(1);
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
                    $allProfiles->forPage(request()->input('page', 1), 2),
                    $allProfiles->count(),
                    2,
                    request()->input('page', 1),
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            }
        }

        // Increment view count for displayed profiles
        if (!$request->ajax()) {
            foreach ($profiles as $profile) {
                if (method_exists($profile, 'incrementViewCount')) {
                    $profile->incrementViewCount();
                } else {
                    $profile->increment('views_count');
                }
            }
        }

        if ($request->ajax()) {
            if ($profiles->isEmpty() && $request->input('page', 1) > 1) {
                return response()->json(['html' => '', 'has_more_pages' => false]);
            }
            return response()->json([
                'html' => view('partials.profiles-list', compact('profiles'))->render(),
                'has_more_pages' => $profiles->hasMorePages()
            ]);
        }

        // Sidebar: Services
        $services = Service::where('status', true)
        ->withCount(['profiles as profiles_count' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name')
            ->get()
            ->map(function($s) {
                $slug = isset($s->slug) ? $s->slug : slugify($s->name); // slugify might need to be a helper
                return [
                    'name' => $s->name,
                    'slug' => $slug,
                    'value' => $s->slug, // Assuming slug is the value for services
                    'count' => $s->profiles_count
                ];
            });

        // Sidebar: Neighborhoods
        $neighborhoods = Neighborhood::where('status', true)
        ->withCount(['profiles as profiles_count' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name')
            ->get()
            ->map(function($n) {
                $slug = isset($n->slug) ? $n->slug : slugify($n->name);
                return [
                    'name' => $n->name,
                    'slug' => $slug,
                    'value' => $n->slug, // Assuming slug is the value for neighborhoods
                    'count' => $n->profiles_count
                ];
            });

        // Sidebar: Metro stations grouped by alphabet
        $metroStations = MetroStation::where('status', true)
        ->withCount(['profiles as profiles_count' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name')
            ->get()
            ->groupBy(fn($item) => mb_strtoupper(mb_substr($item->name, 0, 1, 'UTF-8')))
            ->map(fn($group) => $group->map(fn($station) => [
                'name' => $station->name,
                'slug' => $station->slug,
                'value' => $station->slug, // Assuming slug is the value for metro
                'count' => $station->profiles_count
            ]))
            ->toArray();

        // New filters from database
        $hairColors = HairColor::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
        $heights = Height::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
        $weights = Weight::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
        $sizes = Size::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
        $prices = Price::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
        $ages = Age::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);

        // Get active custom categories for filtering
        $customCategories = CustomCategory::where('status', 1)->orderBy('name')->get();

        $topMenus = TopMenu::all();
        $footerMenus = FooterMenu::all();

        // SEO Fallbacks if not set by specific filter
        if (empty($seoTitle) || empty($seoMetaDescription) || empty($seoH1)) {
            $defaultSeo = SeoTemplate::where('page_type', $pageType)->first();
            if ($defaultSeo) {
                $placeholders = ['site_name' => config('app.name'), 'current_year' => date('Y')];
                // Add more specific placeholders if available, e.g., selected filter names
                // For example, if $serviceModel exists, add $serviceModel->name as {{service_name}}

                if (empty($seoTitle)) {
                    $seoTitle = $defaultSeo->replacePlaceholders($defaultSeo->title_template, null, $placeholders);
                }
                if (empty($seoMetaDescription)) {
                    $seoMetaDescription = $defaultSeo->replacePlaceholders($defaultSeo->meta_description_template, null, $placeholders);
                }
                if (empty($seoH1)) {
                    $seoH1 = $defaultSeo->replacePlaceholders($defaultSeo->h1_template, null, $placeholders);
                }
            }
        }
        // Final fallbacks if still empty
        if (empty($seoTitle)) $seoTitle = config('app.name') . ' - Главная';
        if (empty($seoMetaDescription)) $seoMetaDescription = 'Описание сайта по умолчанию.';
        if (empty($seoH1)) $seoH1 = 'Проститутки услуги в Санкт-Петербурге';


        return view('home.index', compact('services',
         'metroStations',
         'neighborhoods',
         'profiles',
         'filter',
         'topMenus',
         'footerMenus',
         'sort',
         'customCategories',
         'hairColors',
         'heights',
         'weights',
         'sizes',
         'prices',
         'ages',
         'seoTitle',
         'seoMetaDescription',
         'seoH1'
        ));
    }


    public function profileClick($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->incrementClickCount();

        return redirect()->route('profiles.view', $profile->id);
    }
    
    /**
     * Filter profiles by custom category
     */
    public function filterByCustomCategory($slug, Request $request): mixed
    {
        // Find the custom category by slug
        $customCategory = CustomCategory::where('slug', $slug)->where('status', 1)->firstOrFail();

        $seoTitle = $customCategory->title;
        $seoMetaDescription = $customCategory->meta_description;
        $seoH1 = $customCategory->h1;
        $pageType = 'category';

        // Use the getMatchingProfiles method from the CustomCategory model
        $initialProfilesQuery = $customCategory->getMatchingProfiles();

        $filter = $request->input('filter', 'all'); // 'all', 'vip', 'video', 'new', 'cheap', 'verified'
        $sort = $request->input('sort'); // 'popular', 'cheapest', 'expensive'

        // Base query for VIP profiles - these are always shown first if not sorting
        $vipQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
        ->where('profiles.is_active', true)
        ->where('is_vip', true);
 
        $vipQuery->where('is_vip', true)
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
            ->orderByDesc('priority_score');

        // Base query for non-VIP profiles (filtered further)
        $filteredQuery = clone $initialProfilesQuery;
        $filteredQuery->where('is_vip', false); // Exclude VIPs from this query initially

        // Apply additional filters from request (video, new, cheap, verified)
        if ($filter !== 'all' && $filter !== 'vip') {
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
        }

        if ($filter === 'vip' && !$sort) {
            $profiles = $vipQuery
                ->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
                ->paginate(12);
        } elseif ($sort) {
            $combinedQuery = clone $initialProfilesQuery; // Start with all profiles matching category
            $combinedQuery->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff']);

            // Apply additional filters if any (video, new, cheap, verified) to the combined query
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
                    // Default sort for combined could be VIP status then priority, then views or creation date
                    $combinedQuery->orderByDesc('is_vip') // VIPs first
                                  ->orderByDesc(DB::raw('(SELECT MAX(priority_level) FROM profile_ad_tariffs pat JOIN ad_tariffs at ON pat.ad_tariff_id = at.id WHERE pat.profile_id = profiles.id AND at.slug = \'priority\' AND pat.is_paused = 0)')) // Then by priority score
                                  ->orderByDesc('profiles.views_count'); // Then by views
            }
            $profiles = $combinedQuery->paginate(12);
        } else {
            // Default case: Not sorting, and filter is 'all' or one of the specific non-VIP filters
            $filteredQuery->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
                ->withCount(['activeAds as priority_score' => function ($query) {
                    $query->whereHas('adTariff', fn($q) => $q->where('slug', 'priority'))
                        ->where('is_paused', 0)
                        ->select(DB::raw('MAX(priority_level)'));
                }])
                ->orderByDesc('priority_score')
                ->orderByDesc('profiles.views_count');

            $vipProfiles = $vipQuery
                ->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
                ->get();

            $filteredProfiles = $filteredQuery->get();

            $allProfiles = $vipProfiles->concat($filteredProfiles);
            $profiles = new LengthAwarePaginator(
                $allProfiles->forPage($request->input('page', 1), 12),
                $allProfiles->count(),
                12,
                $request->input('page', 1),
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        // Increment view count for displayed profiles
        if (!$request->ajax()) {
            foreach ($profiles as $profile) {
                if (method_exists($profile, 'incrementViewCount')) {
                    $profile->incrementViewCount();
                } else {
                    $profile->increment('views_count');
                }
            }
        }

        if ($request->ajax()) {
            if ($profiles->isEmpty() && $request->input('page', 1) > 1) {
                return response()->json(['html' => '', 'has_more_pages' => false]);
            }
            return response()->json([
                'html' => view('partials.profiles-list', compact('profiles'))->render(),
                'has_more_pages' => $profiles->hasMorePages()
            ]);
        }

        // Sidebar: Services
        $services = Service::where('status', true)
        ->withCount(['profiles as profiles_count' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name') // Added sort_order
            ->get()
            ->map(function($s) {
                // Generate slug if it doesn't exist in the database
                $slug = isset($s->slug) ? $s->slug : slugify($s->name);
                return [
                    'name' => $s->name, 
                    'slug' => $slug,
                    'value' => $s->slug,
                    'count' => $s->profiles_count
                ];
            });

        //neighborhoods
        $neighborhoods = Neighborhood::where('status', true)
        ->withCount(['profiles as profiles_count' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name') // Added sort_order
            ->get()
            ->map(function($s) {
                // Generate slug if it doesn't exist in the database
                $slug = isset($s->slug) ? $s->slug : slugify($s->name);
                return [
                    'name' => $s->name, 
                    'slug' => $slug,
                    'value' => $s->slug,
                    'count' => $s->profiles_count
                ];
            }); 

        // Sidebar: Metro stations grouped by alphabet
        $metroStations = MetroStation::where('status', true)
        ->withCount(['profiles as profiles_count' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name') // Added sort_order
            ->get()
            ->groupBy(fn($item) => mb_strtoupper(mb_substr($item->name, 0, 1, 'UTF-8')))
            ->map(fn($group) => $group->map(fn($station) => [
                'name' => $station->name,
                'slug' => $station->slug,
                'value' => $station->slug,
                'count' => $station->profiles_count
            ]))
            ->toArray();

// New filters from database
$hairColors = HairColor::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
$heights = Height::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
$weights = Weight::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
$sizes = Size::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
$prices = Price::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);
$ages = Age::where('status', true)->orderBy('sort_order')->orderBy('name')->get(['name', 'value']);

// Get active custom categories for filtering
$customCategories = CustomCategory::where('status', 1)->orderBy('name')->get();

// SEO Fallbacks if not set by specific filter
if (empty($seoTitle) || empty($seoMetaDescription) || empty($seoH1)) {
    $defaultSeo = SeoTemplate::where('page_type', $pageType)->first();
    if ($defaultSeo) {
        $placeholders = ['site_name' => config('app.name'), 'current_year' => date('Y')];
        // Add more specific placeholders if available, e.g., selected filter names
        // For example, if $serviceModel exists, add $serviceModel->name as {{service_name}}

        if (empty($seoTitle)) {
            $seoTitle = $defaultSeo->replacePlaceholders($defaultSeo->title_template, null, $placeholders);
        }
        if (empty($seoMetaDescription)) {
            $seoMetaDescription = $defaultSeo->replacePlaceholders($defaultSeo->meta_description_template, null, $placeholders);
        }
        if (empty($seoH1)) {
            $seoH1 = $defaultSeo->replacePlaceholders($defaultSeo->h1_template, null, $placeholders);
        }
    }
}
// Final fallbacks if still empty
if (empty($seoTitle)) $seoTitle = config('app.name') . ' - Главная';
if (empty($seoMetaDescription)) $seoMetaDescription = 'Описание сайта по умолчанию.';
if (empty($seoH1)) $seoH1 = 'Проститутки услуги в Санкт-Петербурге';


        // Set filter information for the view
        $filter = [
            'type' => 'custom_category',
            'value' => $customCategory->name,
            'title' => $customCategory->h1 ?: $customCategory->name,
            'description' => $customCategory->description
        ];

        $sort = request('sort', 'default');

        $topMenus = TopMenu::all();
        $footerMenus = FooterMenu::all();

        return view('home.index', compact('services',
         'metroStations', 
         'neighborhoods',
          'profiles',
          'filter',
          'sort',
          'topMenus',
          'footerMenus',
          'customCategories',
          'hairColors',
          'heights',
          'weights',
          'sizes',
          'prices',
          'ages',
          'seoTitle',
          'seoMetaDescription',
          'seoH1'

        ));
    }

    public function show($id)
    {
        $profile = Profile::with(['metroStations', 'services', 'images', 'video', 'neighborhoods'])->findOrFail($id);
        $profile->incrementViewCount();

        // Fetch SEO Template for 'profile' pages
        $seoTemplate = SeoTemplate::where('page_type', 'profile')->first();
        $seoTitle = null;
        $seoMetaDescription = null;
        $seoH1 = null;

        if ($seoTemplate) {
            $seoTitle = $seoTemplate->replacePlaceholders($seoTemplate->title_template, $profile);
            $seoMetaDescription = $seoTemplate->replacePlaceholders($seoTemplate->meta_description_template, $profile);
            $seoH1 = $seoTemplate->replacePlaceholders($seoTemplate->h1_template, $profile);
        }

        // Fallbacks if templates are not set or don't produce output
        if (empty($seoTitle)) {
            $seoTitle = $profile->name . ', ' . $profile->age . ' - Анкета на сайте';
        }
        if (empty($seoMetaDescription)) {
            $seoMetaDescription = 'Подробная информация об анкете ' . $profile->name . '. Возраст: ' . $profile->age . '. Услуги и контакты доступны на странице.';
        }
        if (empty($seoH1)) {
            $seoH1 = $profile->name . ', ' . $profile->age;
        }

        return view('profiles.profile', compact('profile', 'seoTitle', 'seoMetaDescription', 'seoH1'));
    }
    
  
}