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
            
            if ($filter === 'verified') {
                $query->where('is_verified', true);
            } elseif ($filter === 'unverified') {
                $query->where('is_verified', false);
            } elseif ($filter === 'active') {
                $query->where('is_active', true);
            } elseif ($filter === 'disabled') {
                $query->where('is_active', false);
            } elseif ($filter === 'with_tariffs') {
                $query->has('tariffs');
            } elseif ($filter === 'deleted') {
                $query->onlyTrashed();
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
        ];

        return view('admin.profiles.index', compact('profiles', 'counts'));
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
        $profile->save();

        $tariffs = ProfileAdTariff::where('profile_id', $id)
        ->where('is_active', true)
        ->get();
        if ($tariffs) {
            foreach ($tariffs as $tariff) {
                $tariff->is_active = false;
                $tariff->save();
            }
        }
        
        // Soft delete the profile
        $profile->delete();
        
        // Disable all associated tariffs
        foreach ($profile->tariffs as $ad) {
            $ad->is_active = false;
            $ad->save();
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