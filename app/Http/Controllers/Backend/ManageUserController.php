<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\AccountCreatedMail;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ManageUserController extends Controller
{
    //manage users Index page
    public function index(){

        return view('admin.manage-user.index');

    }


        public function create(Request $request)
    {
        $request->validate([
            // 'name' => ['required', 'max:200'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['required']
        ]);

        $user = new User();

        if($request->role === 'user'){
            $user->name = 'user';
            $user->email = $request->email;
            $user->balance = '0.00';
            $user->password = bcrypt($request->password);
            $user->role = 'client';
            $user->status = 'active';
            $user->save();

            Mail::to($request->email)->send(new AccountCreatedMail($request->name, $request->email, $request->password));

            toastr()->success('Created Successfully!');
            return redirect()->back();

        }else{
            $user->name = 'Admin';
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->balance = '0.00';
            $user->role = 'admin';
            $user->status = 'active';
            $user->save();

            Mail::to($request->email)->send(new AccountCreatedMail($request->name, $request->email, $request->password));

            toastr()->success('Created Successfully!');
            return redirect()->back();
        }
    }
}
