<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdTariffCharge;
use App\Models\Profile;
use App\Models\User;
use App\Models\VerificationPhoto;
use App\Models\Comment;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index()
    {
        // Get total users count
        $totalUsers = User::where('role', '!=', 'admin')->count();
        
        // Get total profiles count
        $totalProfiles = Profile::count();
        $activeProfiles = Profile::where('is_active', true)->count();
        
        // Get VIP profiles (profiles with active ad tariffs)
        $activeVips = Profile::whereHas('tariffs', function($query) {
            $query->where('is_active', true);
        })->where('is_vip', true)
        ->count();
        
        // Get VIPs in queue (profiles with pending ad tariffs)
        $vipsInQueue = Profile::whereHas('tariffs', function($query) {
            $query->where('is_active', false)
            ->where('queue_position', '>' , 0 );
        })->count();
        
        // Get revenue from ad charges
        $revenueToday = AdTariffCharge::whereDate('charged_at', Carbon::today())
            ->sum('amount');
            
        $revenueThisMonth = AdTariffCharge::whereMonth('charged_at', Carbon::now()->month)
            ->whereYear('charged_at', Carbon::now()->year)
            ->sum('amount');
            
        $revenueThisYear = AdTariffCharge::whereYear('charged_at', Carbon::now()->year)
            ->sum('amount');

        // Get pending verifications count
        $pendingVerifications = VerificationPhoto::where('status', 'pending')->count();

        // Get total comments count
        $totalComments = Comment::count();
        $pendingComments = Comment::where('approved', false)->count();

        // Get total reviews count
        $totalReviews = Review::count();
        $pendingReviews = Review::where('approved', false)->count();
        
        // Get monthly revenue for chart
        $monthlyRevenue = AdTariffCharge::select(
                DB::raw('MONTH(charged_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('charged_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return round($item->total, 2);
            });
        
        // Fill in missing months with zero
        $chartData = [];
        $monthNames = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRevenue[$i] ?? 0;
            $monthNames[] = Carbon::create(null, $i, 1)->format('M');
        }
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProfiles',
            'activeProfiles',
            'activeVips',
            'vipsInQueue',
            'revenueToday',
            'revenueThisMonth',
            'revenueThisYear',
            'chartData',
            'monthNames',
            'pendingVerifications',
            'totalComments',
            'pendingComments',
            'totalReviews',
            'pendingReviews'
        ));
    }
}