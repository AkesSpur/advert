<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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

        return response(['message' => 'Status has been updated!']);
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

        return response(['status' => 'success', 'message' => 'Email updated successfully']);
    }
}
