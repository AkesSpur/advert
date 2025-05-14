<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $name = Str::before($request->email, '@');


        $user = User::create([
            'name' => $name ?? 'user',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // $user->balance = 15000;
        // $user->save();

        // $user->transactions()->create([
        //     'amount' => +15000,
        //     'status' => 'completed',
        // ]);

        event(new Registered($user));
        
        // Send welcome notification
        $user->notify(new WelcomeNotification());

        Auth::login($user);

        return redirect(route('user.profiles.index', absolute: false));
    }
}
