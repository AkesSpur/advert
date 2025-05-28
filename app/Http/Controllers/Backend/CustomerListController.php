<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class CustomerListController extends Controller
{
    // return cutomer list view page
    public function index(){

        $customers = User::where('role','=','client')->with('profiles')->get();
        
        return view('admin.customer-list.index',compact('customers'));
        
    }

    public function changeStatus(Request $request){
        
        $customer = User::findOrFail($request->id);
        $customer->status = $request->status == 'true' ? 'active' : 'inactive';
        $customer->save();

        return response(['message' => 'Статус был обновлен!']);
    }

    public function updateEmail(Request $request, string $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$id
        ]);

        $customer = User::findOrFail($id);
        $customer->email = $request->email;
        $customer->email_verified_at = now();
        $customer->save();

        return response(['status' => 'success', 'message' => 'Электронная почта успешно обновлена']);
    }

    public function verifyEmail(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();

        return response(['status' => 'success', 'message' => 'Электронная почта пользователя успешно проверена']);
    }

    public function sendResetLink(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $status = Password::sendResetLink(
            ['email' => $user->email]
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response(['status' => 'success', 'message' => __($status)]);
        } else {
            return response(['status' => 'error', 'message' => __($status)], 422);
        }
    }
}
