<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdTariff;
use App\Models\ProfileAdTariff;
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

        // Update daily charges for associated profile ads if basic ad price changes
        if ($adTariff->slug == 'basic' && isset($validatedData['base_price'])) {
            ProfileAdTariff::where('ad_tariff_id', $adTariff->id)
                ->where(function ($query) {
                    $query->where('is_active', true)
                          ->orWhere('is_paused', true);
                })
                ->update(['daily_charge' => $validatedData['base_price']]);
        }

        // Update daily charges for associated profile ads if priority price changes
        if ($adTariff->slug == 'priority' && isset($validatedData['base_price'])) {
            ProfileAdTariff::where('ad_tariff_id', $adTariff->id)
                ->where(function ($query) {
                    $query->where('is_active', true)
                          ->orWhere('is_paused', true);
                })
                ->each(function ($profileAdTariff) use ($validatedData) {
                    $newDailyCharge = $profileAdTariff->priority_level * $validatedData['base_price'] + $validatedData['base_price'];
                    $profileAdTariff->update(['daily_charge' => $newDailyCharge]);
                });
        }

        return redirect()->route('admin.ad-tariffs.index')->with('success', 'Тариф успешно обновлен');
    }
}