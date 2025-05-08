<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class FundManagementController extends Controller
{
    // return view page
    public function addIndex(string $id)
    {
        $userId = $id;

        return view('admin.fund-management.add-fund',compact('userId'));
    }

    // run deposit function
    public function addFund(Request $request, string $id)
    {
        $request->validate([
            'amount'=> ['required','integer','min:100','max:600000']
        ]);

        $user = User::findOrFail($id);
        
        $oldBalance = $user->balance;
        $newBalance = $oldBalance + $request->amount;

        $user->balance = $newBalance;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $id;
        $transaction->amount = $request->amount;
        $transaction->type = 'topup';
        $transaction->status = 'completed';
        $transaction->save();

        toastr()->success('Wallet topped up successfully');

        return redirect()->route('admin.dashboard');
    }
    
    // return view page
    public function withdrawIndex(string $id)
    {
        $userId = $id;
        
        return view('admin.fund-management.withdraw-fund',compact('userId'));
    }
    
    // run withdraw function
    
        public function withdrawFund(Request $request, string $id)
        {
            $request->validate([
                'amount'=> ['required','integer','min:1','max:600000']
            ]);

            $user = User::findOrFail($id);
        
        $oldBalance = $user->balance;

        if ($request->amount > $oldBalance) {
            toastr('User does not have up to ' . $request->amount . ' in his wallet','error');

            return redirect()->back();
        }

        $newBalance = $oldBalance - $request->amount;

        $user->balance = $newBalance;
        $user->save();

            
            $transaction = new Transaction();
            $transaction->user_id = $id;
            $transaction->amount = $request->amount;
            $transaction->status = 'completed';
            $transaction->save();

            toastr()->success('Amount withdrew successfully');
    
            return redirect()->route('admin.dashboard');                       
        }
}
