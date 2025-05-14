<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
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
            $id = Auth::id();
            $user = User::find($id);
            $lastUpdateKey = 'last_active_update_' . $user->id;
            $lastUpdateTime = $request->session()->get($lastUpdateKey);

            // Update last_active if 5 minutes have passed or if it's the first time
            if (!$lastUpdateTime || Carbon::parse($lastUpdateTime)->addMinutes(5)->isPast()) {
                $currentTime = Carbon::now();
                $user->last_active = $currentTime;
                $user->save(); // Explicitly save the user model
                $request->session()->put($lastUpdateKey, $currentTime->toDateTimeString());
            }
        }

        return $response;
    }
}