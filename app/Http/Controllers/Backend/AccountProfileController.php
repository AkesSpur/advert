<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AccountProfileController extends Controller
{
    // view admin profile
    public function index() {
        
        return view('admin.profile.index');
    }


    public function updateProfile(Request $request)
    {
        $request->merge([
            'email' => strtolower($request->input('email')),
        ]);
       $request->validate([
        'name' => ['required', 'max:100'],
        'email' => ['required', 'email', 'unique:users,email,'.Auth::user()->id]
       ]);

       $user = Auth::user();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->save();

       toastr()->success('Профиль успешно обновлен!');
       return redirect()->back();
    }

    /** Update Password */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required','confirmed', 'min:8']
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        toastr()->success('Пароль профиля успешно обновлен!');

        return redirect()->back();
    }
}
