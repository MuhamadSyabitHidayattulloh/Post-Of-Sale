<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            // Redirect based on role
            $role = Auth::user()->role ?? 'admin';
            return match ($role) {
                'kasir' => redirect()->route('kasir.dashboard'),
                'member' => redirect()->route('member.dashboard'),
                default => redirect()->route('admin.dashboard'),
            };
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $role = Auth::user()->role ?? 'admin';
            return match ($role) {
                'kasir' => redirect()->route('kasir.dashboard')->with('status', 'Selamat datang kembali'),
                'member' => redirect()->route('member.dashboard')->with('status', 'Selamat datang kembali'),
                default => redirect()->route('admin.dashboard')->with('status', 'Selamat datang kembali'),
            };
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak valid.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
