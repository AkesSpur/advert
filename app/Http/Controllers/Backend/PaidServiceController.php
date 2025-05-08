<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaidService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaidServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paidServices = PaidService::all();
        return view('admin.paid-services.index', compact('paidServices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.paid-services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        PaidService::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        toastr()->success('Paid Service created successfully!');
        return redirect()->route('admin.paid-services.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paidService = PaidService::findOrFail($id);
        return view('admin.paid-services.edit', compact('paidService'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $paidService = PaidService::findOrFail($id);
        $paidService->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        toastr()->success('Paid Service updated successfully!');
        return redirect()->route('admin.paid-services.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paidService = PaidService::findOrFail($id);
        $paidService->delete();

        return response(['status' => 'success', 'message' => 'Paid Service deleted successfully!']);
    }
}