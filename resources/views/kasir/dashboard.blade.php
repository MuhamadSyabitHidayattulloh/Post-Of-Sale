@extends('layouts.dashboard')

@section('title', 'Dashboard Kasir')

@section('page-title', 'Dashboard Kasir')
@section('page-subtitle', 'Kelola transaksi dan penjualan')

@section('page-content')
<div class="space-y-6">
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('kasir.pos') }}" class="bg-gradient-to-br from-white to-neutral-200 text-black rounded-xl p-6 hover:scale-105 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-black rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-right text-2xl group-hover:translate-x-2 transition-transform"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Point of Sale</h3>
            <p class="text-sm text-neutral-700">Mulai transaksi baru</p>
        </a>
        
        <a href="{{ route('kasir.members') }}" class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 hover:border-neutral-600 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-green-500 text-2xl"></i>
                </div>
                <i class="fas fa-arrow-right text-neutral-400 text-2xl group-hover:translate-x-2 transition-transform"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Tambah Member</h3>
            <p class="text-sm text-neutral-400">Daftarkan member baru</p>
        </a>
        
        <a href="{{ route('kasir.products') }}" class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 hover:border-neutral-600 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-blue-500 text-2xl"></i>
                </div>
                <i class="fas fa-arrow-right text-neutral-400 text-2xl group-hover:translate-x-2 transition-transform"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Lihat Produk</h3>
            <p class="text-sm text-neutral-400">Cek stok dan harga</p>
        </a>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                </div>
                <span class="text-xs text-green-500 bg-green-500/10 px-2 py-1 rounded-full">Hari Ini</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Rp {{ number_format((int) ($totalSalesToday ?? 0), 0, ',', '.') }}</h3>
            <p class="text-sm text-neutral-400">Total Penjualan</p>
        </div>
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-purple-500 text-xl"></i>
                </div>
                <span class="text-xs text-purple-500 bg-purple-500/10 px-2 py-1 rounded-full">Hari Ini</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $transactionsToday ?? 0 }}</h3>
            <p class="text-sm text-neutral-400">Transaksi Selesai</p>
        </div>
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-blue-500 text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $totalProducts ?? 0 }}</h3>
            <p class="text-sm text-neutral-400">Total Produk</p>
        </div>
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-500/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-orange-500 text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $totalMembers ?? 0 }}</h3>
            <p class="text-sm text-neutral-400">Total Member</p>
        </div>
    </div>
    
    <!-- Recent Transactions & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold">Transaksi Terakhir</h3>
                <a href="#all-transactions" class="text-sm text-neutral-400 hover:text-white">Lihat Semua</a>
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
                        <span class="text-xs text-green-500">{{ ucfirst($trx->payment_method) }}</span>
                    </div>
                </div>
                @empty
                <p class="text-neutral-400 text-sm">Belum ada transaksi.</p>
                @endforelse
            </div>
        </div>
        
        <!-- Top Products Today -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold">Produk Terlaris Hari Ini</h3>
            </div>
            <div class="space-y-4">
                @forelse(($topProductsToday ?? []) as $idx => $row)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-neutral-800 rounded-lg flex items-center justify-center text-sm font-bold">
                            #{{ $idx + 1 }}
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ $row->product->name ?? 'Produk' }}</p>
                            <p class="text-xs text-neutral-400">{{ (int) $row->qty }} terjual</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold">Rp {{ number_format((int) $row->revenue, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-neutral-400 text-sm">Belum ada data produk.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Hourly Sales Chart -->
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold">Penjualan Per Jam</h3>
                <p class="text-sm text-neutral-400">Data hari ini</p>
            </div>
        </div>
        <div class="relative" style="height: 250px;">
            <canvas id="hourlySalesChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Hourly Sales Chart
    const hourlyCtx = document.getElementById('hourlySalesChart').getContext('2d');
    const hourlySalesChart = new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($hourlyLabels ?? []) !!},
            datasets: [{
                label: 'Penjualan (Rp)',
                data: {!! json_encode($hourlyData ?? []) !!},
                backgroundColor: '#fff',
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
                            return 'Rp ' + (value / 1000) + 'k';
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
