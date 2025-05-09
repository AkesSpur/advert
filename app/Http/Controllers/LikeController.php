<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle($profileId)
    {
        $user = User::findOrFail(Auth::id());
        $profile = Profile::findOrFail($profileId);
    
        if ($user->likedProfiles()->where('profile_id', $profileId)->exists()) {
            // Unlike
            $user->likedProfiles()->detach($profileId);
            return response()->json(['status' => 'unliked']);
        } else {
            // Like
            $user->likedProfiles()->attach($profileId);
            return response()->json(['status' => 'liked']);
        }
    }
    
    public function likedProfiles()
    {
        $user = User::findOrFail(Auth::id());
        $likedProfiles = $user->likedProfiles()->latest()->get();
    
        return view('user.liked_profiles', compact('likedProfiles'));
    }
}
