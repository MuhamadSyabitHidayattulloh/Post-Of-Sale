@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Overview dan statistik bisnis Anda')

@section('page-content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Penjualan -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 hover:border-neutral-600 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                </div>
                <span class="text-xs text-green-500 bg-green-500/10 px-2 py-1 rounded-full">+12.5%</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Rp {{ number_format((int) ($totalRevenueToday ?? 0), 0, ',', '.') }}</h3>
            <p class="text-sm text-neutral-400">Total Penjualan</p>
        </div>
        
        <!-- Total Produk -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 hover:border-neutral-600 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-blue-500 text-xl"></i>
                </div>
                <span class="text-xs text-blue-500 bg-blue-500/10 px-2 py-1 rounded-full">+5</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $totalProducts ?? 0 }}</h3>
            <p class="text-sm text-neutral-400">Total Produk</p>
        </div>
        
        <!-- Transaksi Hari Ini -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 hover:border-neutral-600 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-purple-500 text-xl"></i>
                </div>
                <span class="text-xs text-purple-500 bg-purple-500/10 px-2 py-1 rounded-full">+8</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $totalTransactionsToday ?? 0 }}</h3>
            <p class="text-sm text-neutral-400">Transaksi Hari Ini</p>
        </div>
        
        <!-- Total Users -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 hover:border-neutral-600 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-orange-500 text-xl"></i>
                </div>
                <span class="text-xs text-orange-500 bg-orange-500/10 px-2 py-1 rounded-full">+3</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $totalUsers ?? 0 }}</h3>
            <p class="text-sm text-neutral-400">Kasir & Member</p>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold">Statistik Penjualan</h3>
                    <p class="text-sm text-neutral-400">Data penjualan 7 hari terakhir</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-neutral-400">7 Hari terakhir</span>
                </div>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-6">Produk Populer</h3>
            <div class="space-y-4">
                @forelse(($topProducts ?? []) as $row)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-neutral-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-neutral-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ $row->product->name ?? 'Produk' }}</p>
                            <p class="text-xs text-neutral-400">{{ (int) $row->qty_sold }} terjual</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold">Rp {{ number_format((int) $row->amount, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-neutral-400 text-sm">Belum ada data transaksi.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Monthly Sales & Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Sales -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold">Penjualan Bulanan</h3>
                    <p class="text-sm text-neutral-400">Perbandingan bulan ini vs bulan lalu</p>
                </div>
            </div>
            <div class="relative" style="height: 250px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold">Transaksi Terbaru</h3>
                <a href="#" class="text-sm text-neutral-400 hover:text-white">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse(($recentTransactions ?? []) as $trx)
                <div class="flex items-center justify-between pb-4 border-b border-neutral-800 last:border-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ $trx->code }}</p>
                            <p class="text-xs text-neutral-400">{{ $trx->created_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-sm">Rp {{ number_format((int) $trx->total, 0, ',', '.') }}</p>
                        <span class="text-xs text-green-500">{{ ucfirst($trx->status) }}</span>
                    </div>
                </div>
                @empty
                <p class="text-neutral-400 text-sm">Belum ada transaksi.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Member Tiers Stats -->
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-6">Distribusi Member Tier</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(($tiers ?? []) as $tier)
            <div class="rounded-xl p-6 border" style="border-color: {{ ($tier->color ?? '#a3a3a3') }}33; background-image: linear-gradient(to bottom right, {{ ($tier->color ?? '#a3a3a3') }}22, transparent)">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: {{ ($tier->color ?? '#a3a3a3') }}22;">
                        <i class="fas fa-medal text-xl" style="color: {{ $tier->color ?? '#a3a3a3' }};"></i>
                    </div>
                    <span class="text-2xl font-bold">{{ $tier->members_count ?? 0 }}</span>
                </div>
                <h4 class="font-semibold mb-1">{{ $tier->name }}</h4>
                <p class="text-sm text-neutral-400">Diskon {{ (float) ($tier->discount_percent ?? 0) }}%</p>
                <div class="mt-4 bg-neutral-800 rounded-full h-2">
                    @php
                        $totalMembers = max(1, ($tiers->sum('members_count') ?? 1));
                        $pct = min(100, round((($tier->members_count ?? 0) / $totalMembers) * 100));
                    @endphp
                    <div class="h-2 rounded-full" style="width: {{ $pct }}%; background-color: {{ $tier->color ?? '#a3a3a3' }};"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@php($role = $role ?? 'admin')

<script>
    // Sales Chart (7 Days)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($sales7Labels ?? []) !!},
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($sales7Data ?? []) !!},
                borderColor: '#fff',
                backgroundColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#737373',
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000) + 'jt';
                        }
                    },
                    grid: {
                        color: '#262626'
                    }
                },
                x: {
                    ticks: {
                        color: '#737373'
                    },
                    grid: {
                        color: '#262626'
                    }
                }
            }
        }
    });
    
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [
                {
                    label: 'Bulan Ini',
                    data: {!! json_encode($monthlyThisData ?? []) !!},
                    backgroundColor: '#fff',
                },
                {
                    label: 'Bulan Lalu',
                    data: {!! json_encode($monthlyPrevData ?? []) !!},
                    backgroundColor: '#404040',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#737373',
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#737373',
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000) + 'jt';
                        }
                    },
                    grid: {
                        color: '#262626'
                    }
                },
                x: {
                    ticks: {
                        color: '#737373'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endsection
