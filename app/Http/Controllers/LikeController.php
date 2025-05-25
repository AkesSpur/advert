<?php

namespace App\Http\Controllers;

use App\Models\CustomCategory;
use App\Models\FooterMenu;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle($profileId)
    {
        $profile = Profile::findOrFail($profileId);

        if (Auth::check()) {
            $user = User::findOrFail(Auth::id());
            if ($user->likedProfiles()->where('profile_id', $profileId)->exists()) {
                $user->likedProfiles()->detach($profileId);
                return response()->json(['status' => 'unliked', 'count' => $user->likedProfiles()->count()]);
            } else {
                $user->likedProfiles()->attach($profileId);
                return response()->json(['status' => 'liked', 'count' => $user->likedProfiles()->count()]);
            }
        } else {
            $liked = session()->get('liked_profiles', []);
            if (in_array($profileId, $liked)) {
                $liked = array_diff($liked, [$profileId]);
                session()->put('liked_profiles', $liked);
                return response()->json(['status' => 'unliked', 'count' => count($liked)]);
            } else {
                $liked[] = $profileId;
                session()->put('liked_profiles', $liked);
                return response()->json(['status' => 'liked', 'count' => count($liked)]);
            }
        }
    }

    public function likedProfiles()
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::id());
            $likedProfiles = $user->likedProfiles()->where('is_archived', false)->latest()->get();
        } else {
            $likedProfileIds = session()->get('liked_profiles', []);
            $likedProfiles = Profile::whereIn('id', $likedProfileIds)->where('is_archived', false)->latest()->get();
        }
        $customCategories = CustomCategory::where('status', 1)->orderBy('name')->get();

        // footer Menu
        $footerMenus = FooterMenu::all();
        return view('user.liked_profiles', 
        compact(
            'likedProfiles',
            'footerMenus',
            'customCategories'
        ));
    }
}
