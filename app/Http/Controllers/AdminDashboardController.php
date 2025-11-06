<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Tier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->startOfDay();

        $totalRevenueToday = (float) Transaction::whereDate('created_at', $today)->sum('total');
        $totalTransactionsToday = (int) Transaction::whereDate('created_at', $today)->count();
        $totalProducts = (int) Product::count();
        $totalUsers = (int) User::whereIn('role', ['kasir', 'member'])->count();

        // Top products in last 30 days
        $topProducts = TransactionItem::query()
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'paid')->where('created_at', '>=', now()->subDays(30));
            })
            ->selectRaw('product_id, SUM(quantity) as qty_sold, SUM(total) as amount')
            ->groupBy('product_id')
            ->orderByDesc('qty_sold')
            ->with('product')
            ->limit(5)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::with(['cashier', 'member'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Tiers distribution
        $tiers = Tier::withCount(['users as members_count' => function ($q) {
            $q->where('role', 'member');
        }])->orderBy('sort_order')->get();

        // Build 7-day sales series
        $sales7Labels = [];
        $sales7Data = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->startOfDay();
            $sales7Labels[] = $day->format('d M');
            $sales7Data[] = (float) Transaction::whereDate('created_at', $day)->sum('total');
        }

        // Build monthly buckets (current vs previous month)
        $startThis = now()->copy()->startOfMonth();
        $endThis = now()->copy()->endOfMonth();
        $startPrev = now()->copy()->subMonth()->startOfMonth();
        $endPrev = now()->copy()->subMonth()->endOfMonth();

        $rangesThis = [
            [$startThis->copy()->day(1)->startOfDay(), $startThis->copy()->day(7)->endOfDay()],
            [$startThis->copy()->day(8)->startOfDay(), $startThis->copy()->day(14)->endOfDay()],
            [$startThis->copy()->day(15)->startOfDay(), $startThis->copy()->day(21)->endOfDay()],
            [$startThis->copy()->day(22)->startOfDay(), $endThis->copy()->endOfDay()],
        ];
        $rangesPrev = [
            [$startPrev->copy()->day(1)->startOfDay(), $startPrev->copy()->day(7)->endOfDay()],
            [$startPrev->copy()->day(8)->startOfDay(), $startPrev->copy()->day(14)->endOfDay()],
            [$startPrev->copy()->day(15)->startOfDay(), $startPrev->copy()->day(21)->endOfDay()],
            [$startPrev->copy()->day(22)->startOfDay(), $endPrev->copy()->endOfDay()],
        ];

        $monthlyThisData = array_map(function ($range) {
            return (float) Transaction::whereBetween('created_at', $range)->sum('total');
        }, $rangesThis);
        $monthlyPrevData = array_map(function ($range) {
            return (float) Transaction::whereBetween('created_at', $range)->sum('total');
        }, $rangesPrev);

        return view('admin.dashboard', compact(
            'totalRevenueToday',
            'totalTransactionsToday',
            'totalProducts',
            'totalUsers',
            'topProducts',
            'recentTransactions',
            'tiers',
            'sales7Labels',
            'sales7Data',
            'monthlyThisData',
            'monthlyPrevData'
        ));
    }
}
