<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminListController extends Controller
{
    //return adminlist view page
    public function index(){

        $admins = User::where('role','=','admin')->with('profiles')->get();

        return view('admin.admin-list.index',compact('admins'));

    }

    public function changeStatus(Request $request){
        
        $customer = User::findOrFail($request->id);
        $customer->status = $request->status == 'true' ? 'active' : 'inactive';
        $customer->save();

        return response(['message' => 'Status has been updated!']);
    }

    // delete an admin
    public function destroy(string $id)
    {
        
        $admin = User::findOrFail($id);
        $admin->delete();

        return response(['status' => 'success', 'message' => 'Deleted successfully']);

    }

    public function updateEmail(Request $request, string $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$id
        ]);

        $admin = User::findOrFail($id);
        $admin->email = $request->email;
        $admin->email_verified_at = now();
        $admin->save();

        return response(['status' => 'success', 'message' => 'Email updated successfully']);
    }
}
