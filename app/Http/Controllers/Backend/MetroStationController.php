<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MetroStation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MetroStationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metroStations = MetroStation::withCount('profiles')
        ->orderBy('name', 'asc')
        ->paginate(25);
        return view('admin.metro-stations.index', compact('metroStations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.metro-stations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1_header' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        MetroStation::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'title' => $request->title,
            'meta_description' => $request->meta_description,
            'h1_header' => $request->h1_header,
            'status' => $request->status,
        ]);

        toastr()->success('Станция метро создана успешно!');
        return redirect()->route('admin.metro-stations.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $metroStation = MetroStation::findOrFail($id);
        return view('admin.metro-stations.edit', compact('metroStation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1_header' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $metroStation = MetroStation::findOrFail($id);
        $metroStation->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'title' => $request->title,
            'meta_description' => $request->meta_description,
            'h1_header' => $request->h1_header,
            'status' => $request->status,
        ]);

        toastr()->success('Станция метро успешно обновлена!');
        return redirect()->route('admin.metro-stations.index');
    }

    /** 
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $metroStation = MetroStation::findOrFail($id);
        $metroStation->delete();

        return response(['status' => 'success', 'message' => 'Станция метро удалена успешно!']);
    }
}