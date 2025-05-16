<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::withCount('profiles')
        ->orderBy('name', 'asc')
        ->paginate(25);
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.services.create');
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

        Service::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'title' => $request->title,
            'meta_description' => $request->meta_description,
            'h1_header' => $request->h1_header,
            'status' => $request->status,
        ]);

        toastr()->success('Service created successfully!');
        return redirect()->route('admin.services.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
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

        $service = Service::findOrFail($id);
        $service->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'title' => $request->title,
            'meta_description' => $request->meta_description,
            'h1_header' => $request->h1_header,
            'status' => $request->status,
        ]);

        toastr()->success('Service updated successfully!');
        return redirect()->route('admin.services.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response(['status' => 'success', 'message' => 'Service deleted successfully!']);
    }
}