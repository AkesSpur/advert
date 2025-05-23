<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomCategory;
use App\Models\MetroStation;
use App\Models\Neighborhood;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customCategories = CustomCategory::all();
        return view('admin.custom-category.index', compact('customCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        $metroStations = MetroStation::all();
        $neighborhoods = Neighborhood::all();
        
        return view('admin.custom-category.create', compact('services', 'metroStations', 'neighborhoods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:custom_categories',
            'description' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $data = $request->all();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Process filter arrays
        $data['age_filters'] = $request->age_filters ?? [];
        $data['weight_filters'] = $request->weight_filters ?? [];
        $data['size_filters'] = $request->size_filters ?? [];
        $data['hair_color_filters'] = $request->hair_color_filters ?? [];
        $data['price_filters'] = $request->price_filters ?? [];
        $data['height_filters'] = $request->height_filters ?? [];
        $data['service_ids'] = $request->service_ids ?? [];
        $data['metro_station_ids'] = $request->metro_station_ids ?? [];
        $data['neighborhood_ids'] = $request->neighborhood_ids ?? [];

        // Add new boolean filters
        $data['filter_is_vip'] = $request->has('filter_is_vip');
        $data['filter_is_new'] = $request->has('filter_is_new');
        $data['filter_is_verified'] = $request->has('filter_is_verified');
        $data['filter_has_video'] = $request->has('filter_has_video');
        $data['filter_is_cheapest'] = $request->has('filter_is_cheapest');
        $data['show_in_top_menu'] = $request->has('show_in_top_menu');
        $data['show_in_footer_menu'] = $request->has('show_in_footer_menu');

        CustomCategory::create($data);

        return redirect()->route('admin.custom-category.index')
            ->with('success', 'Пользовательская категория успешно создана');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomCategory $customCategory)
    {
        $services = Service::all();
        $metroStations = MetroStation::all();
        $neighborhoods = Neighborhood::all();
        
        return view('admin.custom-category.edit', compact('customCategory', 'services', 'metroStations', 'neighborhoods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomCategory $customCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:custom_categories,slug,' . $customCategory->id,
            'description' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $data = $request->all();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Process filter arrays
        $data['age_filters'] = $request->age_filters ?? [];
        $data['weight_filters'] = $request->weight_filters ?? [];
        $data['size_filters'] = $request->size_filters ?? [];
        $data['hair_color_filters'] = $request->hair_color_filters ?? [];
        $data['price_filters'] = $request->price_filters ?? [];
        $data['height_filters'] = $request->height_filters ?? [];
        $data['service_ids'] = $request->service_ids ?? [];
        $data['metro_station_ids'] = $request->metro_station_ids ?? [];
        $data['neighborhood_ids'] = $request->neighborhood_ids ?? [];

        // Add new boolean filters
        $data['filter_is_vip'] = $request->has('filter_is_vip');
        $data['filter_is_new'] = $request->has('filter_is_new');
        $data['filter_is_verified'] = $request->has('filter_is_verified');
        $data['filter_has_video'] = $request->has('filter_has_video');
        $data['filter_is_cheapest'] = $request->has('filter_is_cheapest');
        $data['show_in_top_menu'] = $request->has('show_in_top_menu');
        $data['show_in_footer_menu'] = $request->has('show_in_footer_menu');

        $customCategory->update($data);

        return redirect()->route('admin.custom-category.index')
            ->with('success', 'Пользовательская категория успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomCategory $customCategory)
    {
        // $customCategory = CustomCategory::findOrFail($id);
        // $customCategory->delete();

        return response(['status' => 'success', 'Удалено успешно!']);
    }

    public function changeStatus(Request $request){
        
        $category = CustomCategory::findOrFail($request->id);
        $category->status = $request->status == 'true' ? 1 : 0;
        $category->save();

        return response(['message' => 'Статус был обновлен!']);
    }

    public function changeMenuStatus(Request $request)
    {
        $category = CustomCategory::findOrFail($request->id);
        $menuType = $request->menu_type; // 'top' or 'footer'
        $status = $request->status == 'true' ? 1 : 0;

        if ($menuType == 'top') {
            $category->show_in_top_menu = $status;
        } elseif ($menuType == 'footer') {
            $category->show_in_footer_menu = $status;
        } else {
            return response(['message' => 'Неверный тип меню'], 400);
        }

        $category->save();

        return response(['message' => 'Статус меню успешно обновлен']);
    }
}