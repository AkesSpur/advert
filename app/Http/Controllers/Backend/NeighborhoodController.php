<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NeighborhoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $neighborhoods = Neighborhood::withCount('profiles')
        ->orderBy('name', 'asc')
        ->paginate(25);
        return view('admin.neighborhoods.index', compact('neighborhoods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.neighborhoods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Neighborhood::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        toastr()->success('Neighborhood created successfully!');
        return redirect()->route('admin.neighborhoods.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $neighborhood = Neighborhood::findOrFail($id);
        return view('admin.neighborhoods.edit', compact('neighborhood'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $neighborhood = Neighborhood::findOrFail($id);
        $neighborhood->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        toastr()->success('Neighborhood updated successfully!');
        return redirect()->route('admin.neighborhoods.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $neighborhood = Neighborhood::findOrFail($id);
        $neighborhood->delete();

        return response(['status' => 'success', 'message' => 'Neighborhood deleted successfully!']);
    }
}