@extends('layouts.dashboard')

@section('title', 'Laporan - Admin')

@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Export dan analisis data penjualan')

@section('page-content')
<div class="space-y-6" x-data="{ reportType: 'sales', dateRange: 'today' }">
    <!-- Report Type Selection -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <button @click="reportType = 'sales'" 
                :class="reportType === 'sales' ? 'bg-white text-black' : 'bg-neutral-900 text-white border border-neutral-800'"
                class="p-6 rounded-xl hover:scale-105 transition-all duration-200">
            <i class="fas fa-chart-line text-3xl mb-3"></i>
            <h3 class="font-semibold">Laporan Penjualan</h3>
        </button>
        <button @click="reportType = 'products'" 
                :class="reportType === 'products' ? 'bg-white text-black' : 'bg-neutral-900 text-white border border-neutral-800'"
                class="p-6 rounded-xl hover:scale-105 transition-all duration-200">
            <i class="fas fa-box text-3xl mb-3"></i>
            <h3 class="font-semibold">Laporan Produk</h3>
        </button>
        <button @click="reportType = 'transactions'" 
                :class="reportType === 'transactions' ? 'bg-white text-black' : 'bg-neutral-900 text-white border border-neutral-800'"
                class="p-6 rounded-xl hover:scale-105 transition-all duration-200">
            <i class="fas fa-receipt text-3xl mb-3"></i>
            <h3 class="font-semibold">Laporan Transaksi</h3>
        </button>
        <button @click="reportType = 'members'" 
                :class="reportType === 'members' ? 'bg-white text-black' : 'bg-neutral-900 text-white border border-neutral-800'"
                class="p-6 rounded-xl hover:scale-105 transition-all duration-200">
            <i class="fas fa-users text-3xl mb-3"></i>
            <h3 class="font-semibold">Laporan Member</h3>
        </button>
    </div>
    
    <!-- Filters -->
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Filter Laporan</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium mb-2">Rentang Waktu</label>
                <select x-model="dateRange" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="week">7 Hari Terakhir</option>
                    <option value="month">30 Hari Terakhir</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            
            <!-- Start Date -->
            <div x-show="dateRange === 'custom'">
                <label class="block text-sm font-medium mb-2">Tanggal Mulai</label>
                <input type="date" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
            </div>
            
            <!-- End Date -->
            <div x-show="dateRange === 'custom'">
                <label class="block text-sm font-medium mb-2">Tanggal Akhir</label>
                <input type="date" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
            </div>
            
            <!-- Additional Filter -->
            <div x-show="reportType === 'products'">
                <label class="block text-sm font-medium mb-2">Kategori</label>
                <select class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                    <option>Semua Kategori</option>
                    <option>Elektronik</option>
                    <option>Fashion</option>
                    <option>Makanan</option>
                    <option>Minuman</option>
                </select>
            </div>
        </div>
        
        <!-- Export Buttons -->
        <div class="flex items-center space-x-4 mt-6 pt-6 border-t border-neutral-800">
            <button class="px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-all duration-200 flex items-center space-x-2">
                <i class="fas fa-file-excel"></i>
                <span>Export ke Excel</span>
            </button>
            <button class="px-6 py-3 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-all duration-200 flex items-center space-x-2">
                <i class="fas fa-file-pdf"></i>
                <span>Export ke PDF</span>
            </button>
            <button class="px-6 py-3 bg-neutral-800 text-white rounded-lg font-semibold hover:bg-neutral-700 transition-all duration-200 flex items-center space-x-2 border border-neutral-700">
                <i class="fas fa-print"></i>
                <span>Print</span>
            </button>
        </div>
    </div>
    
    <!-- Sales Report -->
    <div x-show="reportType === 'sales'" class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Total Penjualan</p>
                <h3 class="text-2xl font-bold">Rp 45.000.000</h3>
                <span class="text-xs text-green-500 mt-2 inline-block">+12.5% dari periode sebelumnya</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Total Transaksi</p>
                <h3 class="text-2xl font-bold">1,247</h3>
                <span class="text-xs text-green-500 mt-2 inline-block">+8.3% dari periode sebelumnya</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Rata-rata Transaksi</p>
                <h3 class="text-2xl font-bold">Rp 36.087</h3>
                <span class="text-xs text-green-500 mt-2 inline-block">+4.2% dari periode sebelumnya</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Produk Terjual</p>
                <h3 class="text-2xl font-bold">3,891</h3>
                <span class="text-xs text-green-500 mt-2 inline-block">+15.7% dari periode sebelumnya</span>
            </div>
        </div>
        
        <!-- Chart -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-6">Grafik Penjualan</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="reportChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Products Report -->
    <div x-show="reportType === 'products'" class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-800 border-b border-neutral-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Produk</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Terjual</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Total Penjualan</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Stok Tersisa</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Performa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800">
                    @for($i = 1; $i <= 10; $i++)
                    <tr class="hover:bg-neutral-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-neutral-800 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-neutral-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Produk {{ $i }}</p>
                                    <p class="text-xs text-neutral-400">SKU-{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold">{{ 100 - ($i * 5) }} unit</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format((100 - ($i * 5)) * 100000, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">{{ 50 - $i }} unit</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center">
                                <div class="w-24 bg-neutral-800 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ 100 - ($i * 8) }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Transactions Report -->
    <div x-show="reportType === 'transactions'" class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-800 border-b border-neutral-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">ID Transaksi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kasir</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Member</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Total</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Metode</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800">
                    @for($i = 1; $i <= 15; $i++)
                    <tr class="hover:bg-neutral-800/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-sm">TRX-{{ str_pad($i, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 text-sm">{{ date('d M Y H:i', strtotime('-' . $i . ' hours')) }}</td>
                        <td class="px-6 py-4 text-sm">Kasir {{ ($i % 3) + 1 }}</td>
                        <td class="px-6 py-4 text-sm">{{ $i % 2 === 0 ? 'Member ' . $i : '-' }}</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format(50000 + ($i * 10000), 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $i % 2 === 0 ? 'bg-blue-500/10 text-blue-500' : 'bg-green-500/10 text-green-500' }}">
                                {{ $i % 2 === 0 ? 'Transfer' : 'Cash' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold bg-green-500/10 text-green-500 rounded">Success</span>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Members Report -->
    <div x-show="reportType === 'members'" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Total Member</p>
                <h3 class="text-2xl font-bold">75</h3>
                <span class="text-xs text-green-500 mt-2 inline-block">+5 member baru bulan ini</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Total Pembelian Member</p>
                <h3 class="text-2xl font-bold">Rp 28.500.000</h3>
                <span class="text-xs text-green-500 mt-2 inline-block">63% dari total penjualan</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Rata-rata per Member</p>
                <h3 class="text-2xl font-bold">Rp 380.000</h3>
                <span class="text-xs text-green-500 mt-2 inline-block">+7.2% dari bulan lalu</span>
            </div>
        </div>
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-800 border-b border-neutral-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Member</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Tier</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Total Transaksi</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Total Belanja</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Last Purchase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800">
                        @for($i = 1; $i <= 10; $i++)
                        <tr class="hover:bg-neutral-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold">M{{ $i }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Member {{ $i }}</p>
                                        <p class="text-xs text-neutral-400">ID: MBR-{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $tiers = ['Bronze', 'Silver', 'Gold'];
                                    $tier = $tiers[$i % 3];
                                    $colors = ['orange', 'gray', 'yellow'];
                                    $color = $colors[$i % 3];
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-500/10 text-{{ $color }}-500">
                                    {{ $tier }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold">{{ 20 - $i }}</td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format((20 - $i) * 50000, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center text-sm text-neutral-400">{{ $i }} hari lalu</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@php
    $role = 'admin';
@endphp

<script>
    const reportCtx = document.getElementById('reportChart');
    if (reportCtx) {
        const reportChart = new Chart(reportCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Penjualan 2024',
                    data: [3500000, 4200000, 3800000, 5100000, 4600000, 5500000, 6200000, 5800000, 6500000, 7100000, 6800000, 7500000],
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
    }
</script>
@endsection
