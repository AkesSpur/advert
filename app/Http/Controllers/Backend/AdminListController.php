<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminListController extends Controller
{
    //return adminlist view page
    public function index(){

        $admins = User::where('role','=','admin')->get();

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
}
