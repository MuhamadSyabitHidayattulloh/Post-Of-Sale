<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminReportsController;
use App\Http\Controllers\AuthController;

// Home & Authentication
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    
    Route::get('/tiers', [AdminUserController::class, 'tiers'])->name('tiers');
    
    Route::get('/reports', [AdminReportsController::class, 'index'])->name('reports');
    Route::get('/reports/export/csv', [AdminReportsController::class, 'exportCsv'])->name('reports.export.csv');
});

// Kasir Routes
Route::middleware(['auth','role:kasir,admin'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\KasirDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/pos', [\App\Http\Controllers\KasirPosController::class, 'index'])->name('pos');
    
    Route::get('/members', [\App\Http\Controllers\KasirPosController::class, 'members'])->name('members');
    Route::post('/members', [\App\Http\Controllers\KasirPosController::class, 'storeMember'])->name('members.store');
    
    Route::get('/products', [\App\Http\Controllers\AdminProductController::class, 'index'])->name('products');
    
    Route::get('/reports', [AdminReportsController::class, 'index'])->name('reports');
    Route::get('/reports/export/csv', [AdminReportsController::class, 'exportCsv'])->name('reports.export.csv');

    // POS API
    \Illuminate\Support\Facades\Route::post('/checkout', [\App\Http\Controllers\KasirPosController::class, 'checkout'])->name('checkout');
    \Illuminate\Support\Facades\Route::get('/barcode', [\App\Http\Controllers\KasirPosController::class, 'barcode'])->name('barcode');
    \Illuminate\Support\Facades\Route::get('/api/products', [\App\Http\Controllers\KasirPosController::class, 'apiProducts'])->name('api.products');
});

// Member Routes
Route::middleware(['auth','role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\MemberDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/history', [\App\Http\Controllers\MemberDashboardController::class, 'index'])->name('history');
    
    Route::get('/tier', [\App\Http\Controllers\MemberDashboardController::class, 'index'])->name('tier');
});
