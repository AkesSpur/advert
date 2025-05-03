<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
   public function index() 
   {
    $transactions = User::findOrFail(Auth::id())->transactions()->latest()->get();
    return view('transactions.index', compact('transactions'));
   }
}
