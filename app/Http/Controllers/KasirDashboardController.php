<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\User;

class KasirDashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->startOfDay();

        $totalSalesToday = (float) Transaction::whereDate('created_at', $today)->sum('total');
        $transactionsToday = (int) Transaction::whereDate('created_at', $today)->count();
        $totalProducts = (int) Product::count();
        $totalMembers = (int) User::where('role', 'member')->count();

        $recentTransactions = Transaction::orderByDesc('created_at')->limit(6)->get();

        $topProductsToday = TransactionItem::selectRaw('product_id, SUM(quantity) as qty, SUM(total) as revenue')
            ->whereHas('transaction', fn($q) => $q->whereDate('created_at', $today)->where('status', 'paid'))
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->with('product')
            ->limit(6)
            ->get();

        // Build hourly series (08:00 - 17:00)
        $hourlyLabels = [];
        $hourlyData = [];
        for ($h = 8; $h <= 17; $h++) {
            $from = $today->copy()->addHours($h);
            $to = $from->copy()->addHour()->subSecond();
            $hourlyLabels[] = $from->format('H:00');
            $hourlyData[] = (float) Transaction::whereBetween('created_at', [$from, $to])->sum('total');
        }

        return view('kasir.dashboard', compact(
            'totalSalesToday','transactionsToday','totalProducts','totalMembers','recentTransactions','topProductsToday','hourlyLabels','hourlyData'
        ));
    }
}
