<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the response
        $response = $next($request);
        
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Update the last_active timestamp on the user's profile
            $profile = Profile::where('user_id', $user->id)->first();
            
            if ($profile) {
                $profile->update(['last_active' => now()]);
            }
        }
        
        return $response;
    }
}