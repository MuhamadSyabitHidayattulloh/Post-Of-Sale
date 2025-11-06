<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tier;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $roleFilter = $request->string('role')->toString(); // admin|kasir|member|''

        $usersQuery = User::query();

        if ($q !== '') {
            $usersQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        if (in_array($roleFilter, ['admin', 'kasir', 'member'], true)) {
            $usersQuery->where('role', $roleFilter);
        }

        $users = $usersQuery->orderByDesc('id')->paginate(12)->withQueryString();

        // Counts by role
        $adminCount = User::where('role', 'admin')->count();
        $kasirCount = User::where('role', 'kasir')->count();
        $memberCount = User::where('role', 'member')->count();

        // Tiers with member counts
        $tiers = Tier::query()
            ->withCount(['users as members_count' => function ($q) {
                $q->where('role', 'member');
            }])
            ->orderBy('sort_order')
            ->get();

        $activeTab = 'users';

        // Navbar context (stubbed for now)
        $role = 'admin';
        $userName = 'Admin User';

        return view('admin.users', compact(
            'users', 'adminCount', 'kasirCount', 'memberCount', 'tiers', 'activeTab', 'role', 'userName'
        ));
    }

    public function tiers(Request $request)
    {
        // Reuse index data but set activeTab to tiers
        $response = $this->index($request);
        $data = $response->getData();
        $data['activeTab'] = 'tiers';
        return view('admin.users', (array) $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,kasir,member',
            'member_tier_id' => 'nullable|exists:tiers,id',
            'status' => 'nullable|in:active,inactive',
        ]);

        $user = new \App\Models\User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
        $user->role = $data['role'];
        $user->status = $data['status'] ?? 'active';
        $user->member_tier_id = $data['role'] === 'member' ? ($data['member_tier_id'] ?? null) : null;
        $user->save();

        return redirect()->route('admin.users')->with('status', 'User berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        // prevent deleting the only admin
        if ($user->role === 'admin' && \App\Models\User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir');
        }
        $user->delete();
        return back()->with('status', 'User dihapus');
    }

    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,kasir,member',
            'member_tier_id' => 'nullable|exists:tiers,id',
            'status' => 'nullable|in:active,inactive',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
        }
        $user->role = $data['role'];
        $user->status = $data['status'] ?? 'active';
        $user->member_tier_id = $data['role'] === 'member' ? ($data['member_tier_id'] ?? null) : null;
        $user->save();

        return redirect()->route('admin.users')->with('status', 'User berhasil diperbarui');
    }
}
