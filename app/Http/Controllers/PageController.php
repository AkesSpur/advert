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
use App\Models\GeneralSetting;
use App\Models\HeroSectionSetting;
use App\Models\HeroSectionOverride;
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
        $filterParam = $request->input('filter', 'all'); // Renamed to avoid conflict with $filter variable later
        $sort = $request->input('sort');
        $district = $request->input('district');

        // SEO variables
        $seoTitle = null;
        $seoMetaDescription = null;
        $seoH1 = null;
        $pageType = 'search_results'; // Default page type for SEO

        $settings = GeneralSetting::firstOrFail(); // Ensure settings are loaded
        $perPage = $settings->profiles_per_page ?? 15; // Default if not set

        // Initialize filter variables
        $serviceSlug = $request->input('service');
        $metroSlug = $request->input('metro');
        $priceSlug = $request->input('price'); // Use a consistent naming, e.g. priceSlug for slug-based filters
        $ageSlug = $request->input('age');
        $hairColorSlug = $request->input('hair_color');
        $heightSlug = $request->input('height');
        $weightSlug = $request->input('weight');
        $sizeSlug = $request->input('size'); // Assuming 'size' from request is a slug for breast size
        $neighborhoodSlug = $request->input('neighborhood');
        $customCategorySlug = $request->input('custom_category'); // For custom category filter

        // Initialize model variables for filters
        $serviceModel = null;
        $metroModel = null;
        $priceModel = null;
        $ageModel = null;
        $hairColorModel = null;
        $heightModel = null;
        $weightModel = null;
        $sizeModel = null;
        $neighborhoodModel = null;
        $customCategoryModel = null; // For custom category filter on index page

        // Override with route-based slugs if present
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
                    $priceSlug = $slug;
                    $priceModel = Price::where('value', $priceSlug)->first();
                    if ($priceModel) {
                        $seoTitle = $priceModel->title;
                        $seoMetaDescription = $priceModel->meta_description;
                        $seoH1 = $priceModel->h1_header;
                        $pageType = 'price';
                    }
                    break;
                case 'home.age':
                    $ageSlug = $slug;
                    $ageModel = Age::where('value', $ageSlug)->first();
                    if ($ageModel) {
                        $seoTitle = $ageModel->title;
                        $seoMetaDescription = $ageModel->meta_description;
                        $seoH1 = $ageModel->h1_header;
                        $pageType = 'age';
                    }
                    break;
                case 'home.hair_color':
                    $hairColorSlug = $slug;
                    $hairColorModel = HairColor::where('value', $hairColorSlug)->first();
                    if ($hairColorModel) {
                        $seoTitle = $hairColorModel->title;
                        $seoMetaDescription = $hairColorModel->meta_description;
                        $seoH1 = $hairColorModel->h1_header;
                        $pageType = 'hair_color';
                        // $hairColorValue = $hairColorModel->type; // This will be handled in the filter application logic
                    }
                    break;
                case 'home.height':
                    $heightSlug = $slug;
                    $heightModel = Height::where('value', $heightSlug)->first();
                    if ($heightModel) {
                        $seoTitle = $heightModel->title;
                        $seoMetaDescription = $heightModel->meta_description;
                        $seoH1 = $heightModel->h1_header;
                        $pageType = 'height';
                    }
                    break;
                case 'home.weight':
                    $weightSlug = $slug;
                    $weightModel = Weight::where('value', $weightSlug)->first();
                    if ($weightModel) {
                        $seoTitle = $weightModel->title;
                        $seoMetaDescription = $weightModel->meta_description;
                        $seoH1 = $weightModel->h1_header;
                        $pageType = 'weight';
                    }
                    break;
                case 'home.breast-size': // Assuming 'size' is for breast size
                    $sizeSlug = $slug;
                    $sizeModel = Size::where('value', $sizeSlug)->first();
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
        }

        // Load models based on query parameter slugs if not already loaded by a route parameter
        if (!$serviceModel && $serviceSlug) $serviceModel = Service::where('slug', $serviceSlug)->first();
        if (!$metroModel && $metroSlug) $metroModel = MetroStation::where('slug', $metroSlug)->first();
        if (!$priceModel && $priceSlug) $priceModel = Price::where('value', $priceSlug)->first(); // Assuming 'value' is the lookup column
        if (!$ageModel && $ageSlug) $ageModel = Age::where('value', $ageSlug)->first();
        if (!$hairColorModel && $hairColorSlug) $hairColorModel = HairColor::where('value', $hairColorSlug)->first();
        if (!$heightModel && $heightSlug) $heightModel = Height::where('value', $heightSlug)->first();
        if (!$weightModel && $weightSlug) $weightModel = Weight::where('value', $weightSlug)->first();
        if (!$sizeModel && $sizeSlug) $sizeModel = Size::where('value', $sizeSlug)->first();
        if (!$neighborhoodModel && $neighborhoodSlug) $neighborhoodModel = Neighborhood::where('slug', $neighborhoodSlug)->first();
        if (!$customCategoryModel && $customCategorySlug) {
            $customCategoryModel = CustomCategory::where('slug', $customCategorySlug)->first();
            // If a custom category is filtered on the index page, and no other pageType is set, consider its SEO implications
            if ($customCategoryModel && empty($pageType)) {
                // $seoTitle = $customCategoryModel->title; // Or appropriate SEO fields
                // $seoMetaDescription = $customCategoryModel->meta_description;
                // $seoH1 = $customCategoryModel->h1_header;
                // $pageType = 'custom_category_filter'; // A page type for when index is filtered by a custom category
            }
        }

        // Main query for profiles
        $profilesQuery = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
            ->where('profiles.is_active', true);

        // Apply filters
        if ($serviceSlug) {
            $profilesQuery->whereHas('services', function ($query) use ($serviceSlug) {
                $query->where('slug', $serviceSlug);
            });
        }

        if ($metroSlug) {
            $profilesQuery->whereHas('metroStations', function ($query) use ($metroSlug) {
                $query->where('slug', $metroSlug);
            });
        }

        if ($hairColorSlug) {
            $hairColorModel = HairColor::where('value', $hairColorSlug)->first();
            if ($hairColorModel) {
                $profilesQuery->where('hair_color', $hairColorModel->type);
            }
        }

        if ($heightSlug) {
            // $heightFilter = Height::where('value', $heightSlug)->first(); // Not needed if using switch directly
            // if ($heightFilter) { // Logic based on slug value directly
                switch ($heightSlug) {
                    case 'height-under-150':
                        $profilesQuery->where('height', '<=', 150);
                        break;
                    case 'height-under-165':
                        $profilesQuery->where('height', '<=', 165);
                        break;
                    case 'height-165-180':
                        $profilesQuery->whereBetween('height', [165, 180]);
                        break;
                    case 'height-over-180':
                        $profilesQuery->where('height', '>', 180);
                        break;
                }
            // }
        }

        // Note: The following 'if ($heightValue)' block was part of the original code.
        // It seems redundant if $heightSlug is already processed.
        // This section will be removed or consolidated to avoid double filtering.
        // if ($heightValue) { ... switch ($heightValue) ... } -> This is now handled by $heightSlug

                //     case 'height-under-150':
                //         $filteredQuery->where('height', '<=', 150);
                //         break;
                //     case 'height-under-165':
                //         $filteredQuery->where('height', '<=', 165);
                //         break;
                //     case 'height-165-180':
                //         $filteredQuery->whereBetween('height', [165, 180]);
                //         break;
                //     case 'height-over-180':
                //         $filteredQuery->where('height', '>', 180);
                //         break;
        //         // }
            // }
        // }

        if ($weightSlug) {
            // $weightFilter = Weight::where('value', $weightSlug)->first(); // Not needed
            // if ($weightFilter) {
                switch ($weightSlug) {
                    case 'weight-under-45':
                        $profilesQuery->where('weight', '<=', 45);
                        break;
                    case 'weight-under-50':
                        $profilesQuery->where('weight', '<=', 50);
                        break;
                    case 'weight-50-65':
                        $profilesQuery->whereBetween('weight', [50, 65]);
                        break;
                    case 'weight-over-65':
                        $profilesQuery->where('weight', '>', 65);
                        break;
                }
            // }
        }

        if ($sizeSlug) {
            // $sizeFilter = Size::where('value', $sizeSlug)->first(); // Not needed
            // if ($sizeFilter) {
                switch ($sizeSlug) {
                    case 'size-under-1':
                        $profilesQuery->where('size', '<', 1);
                        break;
                    case 'size-1-2':
                        $profilesQuery->whereBetween('size', [1, 2]);
                        break;
                    case 'size-2-3':
                        $profilesQuery->whereBetween('size', [2, 3]);
                        break;
                    case 'size-over-3':
                        $profilesQuery->where('size', '>', 3);
                        break;
                }
            // }
        }

        if ($neighborhoodSlug) {
            $profilesQuery->whereHas('neighborhoods', function ($query) use ($neighborhoodSlug) {
                $query->where('slug', $neighborhoodSlug);
            });
        }

        if ($district) {
            $profilesQuery->whereHas('neighborhoods', function ($query) use ($district) {
                $query->where('name', $district); // Assuming district name is unique enough
            });
        }

        if ($priceSlug) {
            // $priceFilter = Price::where('value', $priceSlug)->first(); // Not needed
            // if ($priceFilter) {
                switch ($priceSlug) {
                    case 'price-under-2000':
                        $profilesQuery->where('profiles.vyezd_1hour', '<=', 2000);
                        break;
                    case 'price-1500':
                        $profilesQuery->where('profiles.vyezd_1hour', '=', 1500);
                        break;
                    case 'price-under-2500':
                        $profilesQuery->where('profiles.vyezd_1hour', '<=', 2500);
                        break;
                    case 'price-under-5000':
                        $profilesQuery->where('profiles.vyezd_1hour', '<=', 5000);
                        break;
                    case 'price-under-8000':
                        $profilesQuery->where('profiles.vyezd_1hour', '<=', 8000);
                        break;
                    case 'price-over-5000':
                        $profilesQuery->where('profiles.vyezd_1hour', '>', 5000);
                        break;
                    case 'price-over-8000':
                        $profilesQuery->where('profiles.vyezd_1hour', '>', 8000);
                        break;
                    case 'price-over-10000':
                        $profilesQuery->where('profiles.vyezd_1hour', '>', 10000);
                        break;
                }
            // }
        }

        if ($ageSlug) {
            // $ageFilter = Age::where('value', $ageSlug)->first(); // Not needed
            // if ($ageFilter) {
                switch ($ageSlug) {
                    case 'age-under-22':
                        $profilesQuery->where('profiles.age', '<=', 22);
                        break;
                    case 'age-18':
                        $profilesQuery->where('profiles.age', '=', 18);
                        break;
                    case 'age-under-20':
                        $profilesQuery->where('profiles.age', '<=', 20);
                        break;
                    case 'age-under-25':
                        $profilesQuery->where('profiles.age', '<=', 25);
                        break;
                    case 'age-under-30':
                        $profilesQuery->where('profiles.age', '<=', 30);
                        break;
                    case 'age-30-35':
                        $profilesQuery->whereBetween('profiles.age', [30, 35]);
                        break;
                    case 'age-35-40':
                        $profilesQuery->whereBetween('profiles.age', [35, 40]);
                        break;
                    case 'age-28-40':
                        $profilesQuery->whereBetween('profiles.age', [28, 40]);
                        break;
                    case 'age-over-40':
                        $profilesQuery->where('profiles.age', '>', 40);
                        break;
                }
            // }
        }

        // The following filter blocks using request('...') are now redundant and removed.
        // $price = request('price'); ...
        // $height = request('height'); ...
        // $weight = request('weight'); ...
        // $size = request('size'); ...
        // $hairColor = request('hair-color'); ...

        // Apply additional filters based on filterParam (e.g., 'video', 'new', 'cheap', 'verified')
        // Note: 'vip' filter is handled differently by focusing the query on VIPs or excluding them.
        if ($filterParam !== 'all' && $filterParam !== 'vip') {
            switch ($filterParam) {
                case 'video':
                    $profilesQuery->hasVideo();
                    break;
                case 'new':
                    $profilesQuery->isNew();
                    break;
                case 'cheap':
                    $profilesQuery->isCheap(); // Pass threshold from settings
                    break;
                case 'verified':
                    $profilesQuery->isVerified();
                    break;
            }

                    }            // Fetch top 3 active VIPs separately
        $topVipsQuery = Profile::where('is_active', true)
            ->where('is_vip', true)
            ->whereHas('activeAds', function ($query) {
                $query->whereHas('adTariff', fn($q) => $q->where('slug', 'vip'))
                      ->where('is_paused', 0);
            })
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
            ->orderByDesc('views_count') // Fallback sort for top VIPs
            ->limit(3);
        
        $topVips = $topVipsQuery->get();
        $topVipIds = $topVips->pluck('id')->toArray();

        // If filterParam is 'vip', we ensure only VIPs are shown (excluding the top 3 already fetched if they match other criteria).
        if ($filterParam === 'vip') {
            $profilesQuery->where('is_vip', true);
        }
        // Exclude the top 3 VIPs from the main query to avoid duplication
        $profilesQuery->whereNotIn('profiles.id', $topVipIds);

        // Add counts for sorting for the main query
        $profilesQuery->withCount(['activeAds as vip_score' => function ($query) {
            $query->whereHas('adTariff', fn($q) => $q->where('slug', 'vip'))
                  ->where('is_paused', 0);
        }]);

        $profilesQuery->withCount(['activeAds as priority_score' => function ($query) {
            $query->whereHas('adTariff', fn($q) => $q->where('slug', 'priority'))
                  ->where('is_paused', 0)
                  ->select(DB::raw('MAX(priority_level)'));
        }]);

        // Apply sorting for the main query
        // Default sorting: VIPs first (among remaining), then by priority score, then by views_count.
        $profilesQuery->orderByDesc('is_vip');
        $profilesQuery->orderByDesc('vip_score');
        $profilesQuery->orderByDesc('priority_score');

        if ($sort) {
            switch ($sort) {
                case 'popular':
                    $profilesQuery->orderByDesc('profiles.views_count');
                    break;
                case 'cheapest':
                    $profilesQuery->orderBy('profiles.vyezd_1hour', 'asc');
                    $profilesQuery->orderBy('profiles.appartamenti_1hour', 'asc');
                    break;
                case 'expensive':
                    $profilesQuery->orderBy('profiles.vyezd_1hour', 'desc');
                    $profilesQuery->orderBy('profiles.appartamenti_1hour', 'desc');
                    break;
                default:
                    // Default sort for main query if $sort is present but not matched
                    $profilesQuery->orderByDesc('profiles.views_count');
                    break;
            }
        } else {
            // If no specific $sort parameter, rely on the default VIP/priority sorting already applied.
            // Add a final default like view count or creation date for tie-breaking for the main query.
            $profilesQuery->orderByDesc('profiles.views_count');
            $profilesQuery->orderByDesc('profiles.created_at');
        }

        // Get the current page number.
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Get the main profiles, adjusting perPage if on the first page to account for top VIPs.
        $adjustedPerPage = $perPage;
        if ($currentPage == 1) {
            $adjustedPerPage = max(0, $perPage - $topVips->count());
        }

        $otherProfiles = collect();
        if ($adjustedPerPage > 0) {
            $otherProfiles = $profilesQuery->paginate($adjustedPerPage);
        }

        // Merge top VIPs with other profiles for the first page.
        // For subsequent pages, only $otherProfiles are used.
        $mergedProfiles = collect();
        if ($currentPage == 1) {
            $mergedProfiles = $topVips->merge($otherProfiles->items());
            // Create a new Paginator instance for the merged results
            $profiles = new LengthAwarePaginator(
                $mergedProfiles,
                $otherProfiles->total() + $topVips->count(), // Total items including top VIPs
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        } else {
            // For pages other than the first, $otherProfiles is already paginated correctly
            // but we need to adjust its pagination data to reflect the true total and current page context
            $profiles = new LengthAwarePaginator(
                $otherProfiles->items(),
                $otherProfiles->total() + $topVips->count(), // Total items including top VIPs
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
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

        // Determine hero content for the index page
$defaultHeroSettings = HeroSectionSetting::first();
$heroContent = $defaultHeroSettings; // Initialize with default settings

// Priority 1: Specific filter overrides (Service, Metro, Price, etc.)
$activeFilterModels = array_filter([
    $serviceModel, $metroModel, $priceModel, $ageModel, 
    $hairColorModel, $heightModel, $weightModel, $sizeModel, $neighborhoodModel
]);

foreach ($activeFilterModels as $modelInstance) {
    if ($modelInstance) { // No need to check for method_exists if we query HeroSectionOverride directly
        // Fetch override based on the model TYPE, not a specific instance relationship
        $override = HeroSectionOverride::where('model_type', get_class($modelInstance))
                                        ->where('is_active', true)
                                        ->first();
                                        
        if ($override) {
            $heroContent = $override;
            break; // First active specific filter override wins
        }
    }
}

// Priority 2: Generic override (if no specific filter override was applied)
if ($heroContent === $defaultHeroSettings && $defaultHeroSettings && method_exists($defaultHeroSettings, 'heroSectionOverride')) {
    // Assuming a generic override is linked to the HeroSectionSetting model itself
    // Or a specific record in HeroSectionOverride, e.g., model_type = 'App\Models\HeroSectionSetting' and model_id = 0 or 1 for global
    $genericOverride = HeroSectionOverride::where('model_type', get_class($defaultHeroSettings))
                                        ->where('model_id', $defaultHeroSettings->id) // Or your specific logic for generic
                                        ->where('is_active', true)
                                        ->first();
    // Fallback: Check for a truly global override if the above isn't found
    // if (!$genericOverride) {
    //     $genericOverride = HeroSectionOverride::where('model_type', 'App\Models\HeroSectionSetting') // Or a dedicated 'global' type
    //                                             ->where('model_id', 0) // Or a sentinel ID for global
    //                                             ->where('is_active', true)
    //                                             ->first();
    // }

    if ($genericOverride) {
        $heroContent = $genericOverride;
    }
}
// At this point, $heroContent is the most specific override, or the default settings.


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
       
        if (empty($seoTitle)) $seoTitle = $settings->default_seo_title ?? config('app.name') . ' - Главная';
        if (empty($seoMetaDescription)) $seoMetaDescription = $settings->default_seo_description ?? 'Описание сайта по умолчанию.';
        if (empty($seoH1)) $seoH1 = $settings->default_h1_heading ?? 'Проститутки услуги в Санкт-Петербурге';

        $filter  = $filterParam;
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
         'heroContent',
         'seoMetaDescription',
         'seoH1'
        ));
    }


    public function profileClick($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->incrementClickCount();

        return redirect()->route('profiles.view', [
            'slug' => $profile->slug, // Assuming 'slug' is the route parameter for the profile view
            'id'=>$profile->id]);
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

        $settings = GeneralSetting::first();
        $perPage = $settings->profiles_per_page;

        $sort = $request->input('sort'); // Default sort handled by null check later
        $filterParam = $request->input('filter', 'all');

        // Unified query for profiles in this category
        // Use the getMatchingProfiles method from the CustomCategory model for the base query
        $profilesQuery = $customCategory->getMatchingProfiles()
            ->with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff']);

        // Apply additional filters based on $filterParam
        if ($filterParam !== 'all' && $filterParam !== 'vip') {
            switch ($filterParam) {
                case 'video':
                    $profilesQuery->hasVideo();
                    break;
                case 'new':
                    $profilesQuery->isNew();
                    break;
                case 'cheap':
                    // $settings is loaded at the beginning of the method
                    $profilesQuery->isCheap($settings->cheap_threshold ?? 0);
                    break;
                case 'verified':
                    $profilesQuery->isVerified();
                    break;
            }
        }

        // Fetch top 3 active VIPs separately, considering the custom category context if needed
        // For now, we fetch top 3 VIPs globally. If they must also match category, this query needs adjustment.
        $topVipsQuery = Profile::where('is_active', true)
            ->where('is_vip', true)
            ->whereHas('activeAds', function ($query) {
                $query->whereHas('adTariff', fn($q) => $q->where('slug', 'vip'))
                      ->where('is_paused', 0);
            })
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
            ->orderByDesc('views_count') // Fallback sort for top VIPs
            ->limit(3);

        $topVips = $topVipsQuery->get();
        $topVipIds = $topVips->pluck('id')->toArray();

        // If filterParam is 'vip', ensure only VIPs are shown (excluding the top 3 already fetched).
        if ($filterParam === 'vip') {
            $profilesQuery->where('is_vip', true);
        }
        // Exclude the top 3 VIPs from the main category query to avoid duplication
        $profilesQuery->whereNotIn('profiles.id', $topVipIds);

        // Add counts for sorting for the main category query
        $profilesQuery->withCount(['activeAds as vip_score' => function ($query) {
            $query->whereHas('adTariff', fn($q) => $q->where('slug', 'vip'))
                  ->where('is_paused', 0);
        }]);
        $profilesQuery->withCount(['activeAds as priority_score' => function ($query) {
            $query->whereHas('adTariff', fn($q) => $q->where('slug', 'priority'))
                  ->where('is_paused', 0)
                  ->select(DB::raw('MAX(priority_level)'));
        }]);

        // Apply sorting for the main category query
        // Default sorting: VIPs first (among remaining), then by priority score.
        $profilesQuery->orderByDesc('is_vip');
        $profilesQuery->orderByDesc('vip_score');
        $profilesQuery->orderByDesc('priority_score');

        if ($sort) {
            switch ($sort) {
                case 'popular':
                    $profilesQuery->orderByDesc('profiles.views_count');
                    break;
                case 'cheapest':
                    $profilesQuery->orderBy('profiles.vyezd_1hour', 'asc');
                    $profilesQuery->orderBy('profiles.appartamenti_1hour', 'asc');
                    break;
                case 'expensive':
                    $profilesQuery->orderBy('profiles.vyezd_1hour', 'desc');
                    $profilesQuery->orderBy('profiles.appartamenti_1hour', 'desc');
                    break;
                default:
                    $profilesQuery->orderByDesc('profiles.views_count');
                    break;
            }
        } else {
            // Default sort if $sort is not provided (already has VIP/priority)
            $profilesQuery->orderByDesc('profiles.views_count');
            $profilesQuery->orderByDesc('profiles.created_at');
        }

        // Get the current page number.
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Get the main profiles, adjusting perPage if on the first page to account for top VIPs.
        $adjustedPerPage = $perPage;
        if ($currentPage == 1) {
            $adjustedPerPage = max(0, $perPage - $topVips->count());
        }

        $otherProfiles = collect();
        if ($adjustedPerPage > 0) {
            $otherProfiles = $profilesQuery->paginate($adjustedPerPage);
        }

        // Merge top VIPs with other profiles for the first page.
        $mergedProfiles = collect();
        if ($currentPage == 1) {
            $mergedProfiles = $topVips->merge($otherProfiles->items());
            $profiles = new LengthAwarePaginator(
                $mergedProfiles,
                $otherProfiles->total() + $topVips->count(),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
            );
        } else {
            $profiles = new LengthAwarePaginator(
                $otherProfiles->items(),
                $otherProfiles->total() + $topVips->count(),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
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

if (empty($seoTitle)) $seoTitle = $settings->default_seo_title ?? config('app.name') . ' - Главная';
if (empty($seoMetaDescription)) $seoMetaDescription = $settings->default_seo_description ?? 'Описание сайта по умолчанию.';
if (empty($seoH1)) $seoH1 = $settings->default_h1_heading ?? 'Проститутки услуги в Санкт-Петербурге';


        // Set filter information for the view
        $filter = [
            'type' => 'custom_category',
            'value' => $customCategory->name,
            'title' => $customCategory->h1 ?: $customCategory->name,
            'description' => $customCategory->description
        ];

        $sort = request('sort', 'default');

        // Determine hero content for CustomCategory page
        $defaultHeroSettings = HeroSectionSetting::first();
        $heroContent = $defaultHeroSettings; // Initialize with default settings

        // Priority 1: Current CustomCategory override
        // $customCategory is the specific category for this page, passed into the method.
        if (isset($customCategory) && $customCategory instanceof CustomCategory && method_exists($customCategory, 'heroSectionOverride')) {
            $override = $customCategory->heroSectionOverride()->where('is_active', true)->first();
            if ($override) {
                $heroContent = $override;
            }
        }

        // Priority 2: Generic override (if no specific CustomCategory override was applied for this category page)
        // This applies if the current CustomCategory doesn't have its own active override.
        if ($heroContent === $defaultHeroSettings && $defaultHeroSettings && method_exists($defaultHeroSettings, 'heroSectionOverride')) {
            // Check for a generic override linked to the HeroSectionSetting model itself (model_type = HeroSectionSetting, model_id = $defaultHeroSettings->id)
            // Or, if your generic override is identified differently (e.g., a specific HeroSectionOverride record not tied to any model, or tied to HeroSectionSetting with model_id=0 or 1 for global)
            // For this example, assuming a generic override is one associated directly with the $defaultHeroSettings instance if it can have one.
            // If HeroSectionSetting itself can have an override (e.g. for 'homepage' or 'default_filters_page')
            $genericOverride = HeroSectionOverride::where('model_type', get_class($defaultHeroSettings))
                                                ->where('model_id', $defaultHeroSettings->id)
                                                ->where('is_active', true)
                                                ->first();
            // If your generic override is a single record in HeroSectionOverride with a special marker (e.g. model_type = 'App\Models\HeroSectionSetting' and model_id = 0 or 1 for global)
            // $genericOverride = HeroSectionOverride::where('model_type', 'App\Models\HeroSectionSetting')->where('model_id', 0)->where('is_active', true)->first(); 
            if ($genericOverride) {
                $heroContent = $genericOverride;
            }
        }
        // At this point, $heroContent is either an override, the $defaultHeroSettings, or null if $defaultHeroSettings was null and no overrides found.

        // If $heroContent is an override, its 'title', 'text_content', 'image' will be used.
        // If it's still the default HeroSectionSetting, its properties will be used.

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
          'seoH1',
          'heroContent'

        ));
    }

    public function show($slug, $id)
    {
        $profile = Profile::with(['metroStations', 'services', 'images', 'video', 'neighborhoods'])->findOrFail($id);

        if($profile->is_active == false) {
            return back()->with('error', 'Профиль неактивен.');
        }   

        $profile->incrementViewCount();
        $profile->incrementClickCount();

        $profiles = Profile::with(['metroStations', 'services', 'images', 'video', 'activeAds.adTariff'])
        ->where('profiles.is_active', true)
        ->where('profiles.id', '!=', $id)
        ->inRandomOrder()
        ->take(4)
        ->get();


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

                // footer Menu
                $footerMenus = FooterMenu::all();

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

        return view('profiles.profile', compact(
            'services',
            'metroStations',
            'neighborhoods',
            'hairColors',
            'heights',
            'weights',
            'sizes',
            'prices',
            'ages',
            'customCategories',
            'profiles',
            'footerMenus',
   'profile', 'seoTitle', 'seoMetaDescription', 'seoH1'));
    }
    
  
}