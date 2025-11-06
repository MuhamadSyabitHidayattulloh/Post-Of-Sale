<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class MemberDashboardController extends Controller
{
    public function index(Request $request)
    {
        $member = $request->user();
        $member->load('tier');

        $totalTransactions = Transaction::where('member_id', $member->id)->count();
        $totalSpent = (float) Transaction::where('member_id', $member->id)->sum('total');
        $totalSaved = (float) Transaction::where('member_id', $member->id)->sum('discount');

        $recent = Transaction::where('member_id', $member->id)
            ->withCount('items')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Navbar/user context
        $role = 'member';
        $userName = $member->name;

        return view('member.dashboard', compact(
            'member', 'totalTransactions', 'totalSpent', 'totalSaved', 'recent', 'role', 'userName'
        ));
    }
}
