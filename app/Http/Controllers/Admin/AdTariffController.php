<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdTariff;
use Illuminate\Http\Request;

class AdTariffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adTariffs = AdTariff::all();
        return view('admin.ad-tariffs.index', compact('adTariffs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdTariff $adTariff)
    {
        return view('admin.ad-tariffs.edit', compact('adTariff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdTariff $adTariff)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        if ($adTariff->slug == 'vip') {
            $vipRules = [
                'fixed_price' => 'required|numeric|min:0',
                'weekly_price' => 'required|numeric|min:0',
                'monthly_price' => 'required|numeric|min:0',
            ];
            $validatedData = array_merge($validatedData, $request->validate($vipRules));
        }
         else {
            $basicPriorityRules = [
                'base_price' => 'required|numeric|min:0',
            ];
            $validatedData = array_merge($validatedData, $request->validate($basicPriorityRules));
        }
        

        $adTariff->update($validatedData);

        return redirect()->route('admin.ad-tariffs.index')->with('success', 'Тариф успешно обновлен');
    }
}