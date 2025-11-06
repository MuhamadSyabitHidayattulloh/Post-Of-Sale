@extends('layouts.dashboard')

@section('title', 'Dashboard Member')

@section('page-title', 'Dashboard Member')
@section('page-subtitle', 'Selamat datang, Ahmad Rizki')

@section('page-content')
<div class="space-y-6">
    <!-- Member Info Card -->
    <div class="bg-gradient-to-br from-yellow-500/20 to-transparent border-2 border-yellow-500/50 rounded-xl p-6">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="w-24 h-24 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-3xl font-bold">AR</span>
            </div>
            
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold mb-2">Ahmad Rizki</h2>
                <p class="text-neutral-400 mb-4">Member sejak Januari 2024</p>
                
                <div class="flex flex-wrap items-center gap-4 justify-center md:justify-start">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-crown text-yellow-500"></i>
                        <span class="font-semibold text-yellow-500">Gold Member</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-star text-yellow-500"></i>
                        <span>1,250 Poin</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-shopping-bag text-green-500"></i>
                        <span>45 Transaksi</span>
                    </div>
                </div>
            </div>
            
            <div class="text-center md:text-right">
                <p class="text-sm text-neutral-400 mb-2">Member ID</p>
                <p class="text-xl font-bold font-mono">MBR-0042</p>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-purple-500 text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">45</h3>
            <p class="text-sm text-neutral-400">Total Pembelian</p>
        </div>
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">Rp 8.500.000</h3>
            <p class="text-sm text-neutral-400">Total Belanja</p>
        </div>
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-percent text-blue-500 text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">Rp 1.275.000</h3>
            <p class="text-sm text-neutral-400">Total Hemat</p>
        </div>
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-yellow-500 text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">1,250</h3>
            <p class="text-sm text-neutral-400">Poin Rewards</p>
        </div>
    </div>
    
    <!-- Tier Benefits & Progress -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Current Tier Benefits -->
        <div class="bg-gradient-to-br from-yellow-500/10 to-transparent border border-yellow-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold mb-1">Gold Member Benefits</h3>
                    <p class="text-sm text-neutral-400">Keuntungan yang Anda dapatkan</p>
                </div>
                <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-crown text-yellow-500 text-2xl"></i>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-check text-green-500 text-xs"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Diskon 15%</p>
                        <p class="text-sm text-neutral-400">Untuk semua pembelian</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-check text-green-500 text-xs"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Poin 3x Lipat</p>
                        <p class="text-sm text-neutral-400">Akumulasi poin lebih cepat</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-check text-green-500 text-xs"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Gratis Ongkir Unlimited</p>
                        <p class="text-sm text-neutral-400">Tanpa minimum pembelian</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-check text-green-500 text-xs"></i>
                    </div>
                    <div>
                        <p class="font-semibold">VIP Support 24/7</p>
                        <p class="text-sm text-neutral-400">Priority customer service</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-check text-green-500 text-xs"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Early Access</p>
                        <p class="text-sm text-neutral-400">Akses pertama produk baru</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Points & Rewards -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <h3 class="text-xl font-bold mb-6">Poin & Rewards</h3>
            
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-neutral-400">Poin Anda</span>
                    <span class="text-2xl font-bold">1,250</span>
                </div>
                <div class="bg-neutral-800 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 h-full rounded-full" style="width: 62.5%"></div>
                </div>
                <p class="text-xs text-neutral-400 mt-2">750 poin lagi untuk reward berikutnya</p>
            </div>
            
            <div class="space-y-3">
                <h4 class="font-semibold text-sm mb-3">Tukar Poin</h4>
                
                <div class="bg-neutral-800 rounded-lg p-4 hover:bg-neutral-700 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-gift text-purple-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Voucher Rp 50.000</p>
                                <p class="text-xs text-neutral-400">500 poin</p>
                            </div>
                        </div>
                        <button class="px-4 py-2 bg-white text-black text-sm font-semibold rounded-lg hover:bg-neutral-200 transition-colors">
                            Tukar
                        </button>
                    </div>
                </div>
                
                <div class="bg-neutral-800 rounded-lg p-4 hover:bg-neutral-700 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-gift text-blue-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Voucher Rp 100.000</p>
                                <p class="text-xs text-neutral-400">1.000 poin</p>
                            </div>
                        </div>
                        <button class="px-4 py-2 bg-white text-black text-sm font-semibold rounded-lg hover:bg-neutral-200 transition-colors">
                            Tukar
                        </button>
                    </div>
                </div>
                
                <div class="bg-neutral-800 rounded-lg p-4 opacity-50 cursor-not-allowed">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-gift text-green-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Voucher Rp 200.000</p>
                                <p class="text-xs text-neutral-400">2.000 poin</p>
                            </div>
                        </div>
                        <span class="text-xs text-neutral-500">Poin tidak cukup</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Purchase History -->
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-800 flex items-center justify-between">
            <h3 class="text-lg font-semibold">Riwayat Pembelian</h3>
            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
                <button class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-semibold hover:bg-red-600 transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
            </div>
        </div>
        
        <!-- Filter -->
        <div class="px-6 py-4 border-b border-neutral-800">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                    <input type="text" placeholder="Cari transaksi..." 
                           class="w-full bg-neutral-800 border border-neutral-700 rounded-lg pl-12 pr-4 py-2 focus:outline-none focus:border-neutral-500 text-sm">
                </div>
                <select class="bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-2 focus:outline-none focus:border-neutral-500 text-sm">
                    <option>30 Hari Terakhir</option>
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-800 border-b border-neutral-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">ID Transaksi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Item</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Subtotal</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Diskon</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Total</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800">
                    @for($i = 1; $i <= 10; $i++)
                    <tr class="hover:bg-neutral-800/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-sm">TRX-{{ str_pad($i, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 text-sm">{{ date('d M Y', strtotime('-' . $i . ' days')) }}</td>
                        <td class="px-6 py-4 text-sm">{{ rand(1, 5) }} item</td>
                        <td class="px-6 py-4 text-right text-sm">Rp {{ number_format(200000 + ($i * 50000), 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-sm text-green-500">-Rp {{ number_format((200000 + ($i * 50000)) * 0.15, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format((200000 + ($i * 50000)) * 0.85, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-yellow-500/10 text-yellow-500 text-xs font-semibold rounded">
                                +{{ rand(20, 100) }}
                            </span>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-neutral-800 flex items-center justify-between">
            <p class="text-sm text-neutral-400">Menampilkan 1-10 dari 45 transaksi</p>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-2 bg-neutral-800 rounded-lg hover:bg-neutral-700 transition-colors text-sm">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-3 py-2 bg-white text-black rounded-lg font-semibold text-sm">1</button>
                <button class="px-3 py-2 bg-neutral-800 rounded-lg hover:bg-neutral-700 transition-colors text-sm">2</button>
                <button class="px-3 py-2 bg-neutral-800 rounded-lg hover:bg-neutral-700 transition-colors text-sm">3</button>
                <button class="px-3 py-2 bg-neutral-800 rounded-lg hover:bg-neutral-700 transition-colors text-sm">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@php
    $role = 'member';
    $userName = 'Ahmad Rizki';
@endphp
@endsection
