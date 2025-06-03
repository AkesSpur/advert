<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ProfileAdTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of all profiles with filtering options
     */
    public function index(Request $request)
    {
        $query = Profile::with(['user', 'verificationPhoto', 'primaryImage', 'tariffs'])
            ->withCount('tariffs');

        // Apply filters
        if ($request->has('filter')) {
            $filter = $request->filter;
            
            if ($filter == 'verified') {
                $query->where('is_verified', true);
            } elseif ($filter == 'unverified') {
                $query->where('is_verified', false);
            } elseif ($filter == 'active') {
                $query->where('is_active', true);
            } elseif ($filter == 'disabled') {
                $query->where('is_active', false);
            } elseif ($filter == 'with_tariffs') {
                $query->has('tariffs');
            } elseif ($filter == 'deleted') {
                $query->onlyTrashed();
            } elseif ($filter == 'vip') {
                $query->where('is_vip', true);
            }
        }

        // Apply search if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $profiles = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Get counts for the sidebar
        $counts = [
            'all' => Profile::count(),
            'verified' => Profile::where('is_verified', true)->count(),
            'unverified' => Profile::where('is_verified', false)->count(),
            'active' => Profile::where('is_active', true)->count(),
            'disabled' => Profile::where('is_active', false)->count(),
            'with_tariffs' => Profile::has('tariffs')->count(),
            'deleted' => Profile::onlyTrashed()->count(),
            'vip' => Profile::where('is_vip', true)->count(),
        ];

        $vipProfiles = Profile::where('is_vip', true)->orderBy('created_at', 'desc')->get();

        return view('admin.profiles.index', compact('profiles', 'counts', 'vipProfiles'));
    }

    /**
     * Display the specified profile
     */
    public function show($id)
    {
        $profile = Profile::with(['user', 'verificationPhoto', 'images', 'services', 'tariffs'])
            ->withTrashed()
            ->findOrFail($id);

        return view('admin.profiles.show', compact('profile'));
    }

    /**
     * Disable a profile (soft delete)
     */
    public function disable($id)
    {
        $profile = Profile::findOrFail($id);
        
        // Disable the profile
        $profile->is_active = false;
        $profile->is_vip = false;
        $profile->save();
        
        // Soft delete the profile
        $profile->delete();
        
        // Disable all associated tariffs
        foreach ($profile->tariffs as $ad) {
            if($ad->is_active == true){
                $ad->is_paused = true;
                $ad->is_active = false;
                $ad->save();    
            }
        }

        return redirect()->route('admin.profiles.index')
            ->with('success', 'Анкета успешно отключена');
    }

    /**
     * Restore a soft-deleted profile
     */
    public function restore($id)
    {
        $profile = Profile::withTrashed()->findOrFail($id);
        
        // Restore the profile
        $profile->restore();

        // Reactivate the profile's latest ad tariff if it exists
        // Reactivate all paused ad tariffs for the profile
        $pausedAdTariffs = $profile->tariffs()->where('is_paused', true)->get();

        foreach ($pausedAdTariffs as $adTariff) {
            $adTariff->update(['is_active' => true, 'is_paused' => false]);
            if ($adTariff->ad_tariff_id == 3) {
                $profile->is_vip = true;
                $profile->is_active = true;
                $profile->save();
            }else{
                $profile->is_active = true;
                $profile->save();
            }
        }
        
        return redirect()->route('admin.profiles.index')
            ->with('success', 'Анкета успешно восстановлена');
    }

    /**
     * Permanently delete a profile
     */
    public function destroy($id)
    {
        $profile = Profile::withTrashed()->findOrFail($id);
        
        // Delete all associated tariffs
        foreach ($profile->tariffs as $ad) {
            $ad->forceDelete();
        }
        
        // Delete verification photo if exists
        if ($profile->verificationPhoto) {
            $profile->verificationPhoto->delete();
        }
        
        // Delete all profile images
        foreach ($profile->images as $image) {
            // Delete the file from storage
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
        
        // Force delete the profile
        $profile->forceDelete();

        return redirect()->route('admin.profiles.index')
            ->with('success', 'Анкета успешно удалена');
    }
}