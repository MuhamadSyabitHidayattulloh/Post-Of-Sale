<?php

use Illuminate\Support\Facades\Route;

// Home & Authentication
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/products', function () {
        return view('admin.products');
    })->name('products');
    
    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');
    
    Route::get('/tiers', function () {
        return view('admin.users'); // Same view with tabs
    })->name('tiers');
    
    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('reports');
});

// Kasir Routes
Route::prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', function () {
        return view('kasir.dashboard');
    })->name('dashboard');
    
    Route::get('/pos', function () {
        return view('kasir.pos');
    })->name('pos');
    
    Route::get('/members', function () {
        return view('kasir.pos'); // POS can add members
    })->name('members');
    
    Route::get('/products', function () {
        return view('admin.products'); // Reuse admin products view (read-only for kasir)
    })->name('products');
    
    Route::get('/reports', function () {
        return view('admin.reports'); // Reuse admin reports view
    })->name('reports');
});

// Member Routes
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', function () {
        return view('member.dashboard');
    })->name('dashboard');
    
    Route::get('/history', function () {
        return view('member.dashboard'); // Same view, scroll to history
    })->name('history');
    
    Route::get('/tier', function () {
        return view('member.dashboard'); // Same view, scroll to tier
    })->name('tier');
});
