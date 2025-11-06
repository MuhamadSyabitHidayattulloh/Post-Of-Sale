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
    <form method="GET" action="{{ route('admin.reports') }}" class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Filter Laporan</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium mb-2">Rentang Waktu</label>
                <select x-model="dateRange" name="range" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                    <option value="today" @selected(($range ?? 'today')==='today')>Hari Ini</option>
                    <option value="yesterday" @selected(($range ?? '')==='yesterday')>Kemarin</option>
                    <option value="week" @selected(($range ?? '')==='week')>7 Hari Terakhir</option>
                    <option value="month" @selected(($range ?? '')==='month')>30 Hari Terakhir</option>
                    <option value="custom" @selected(($range ?? '')==='custom')>Custom Range</option>
                </select>
            </div>
            
            <!-- Start Date -->
            <div x-show="dateRange === 'custom'">
                <label class="block text-sm font-medium mb-2">Tanggal Mulai</label>
                <input type="date" name="start" value="{{ request('start') }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
            </div>
            
            <!-- End Date -->
            <div x-show="dateRange === 'custom'">
                <label class="block text-sm font-medium mb-2">Tanggal Akhir</label>
                <input type="date" name="end" value="{{ request('end') }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
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
        
        <!-- Actions -->
        <div class="flex items-center space-x-4 mt-6 pt-6 border-t border-neutral-800">
            <button type="submit" class="px-6 py-3 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-all duration-200">Terapkan</button>
            <a :href="(() => { const p = new URLSearchParams(window.location.search); p.set('type', reportType); return '{{ request()->routeIs('kasir.*') ? route('kasir.reports.export.csv') : route('admin.reports.export.csv') }}' + '?' + p.toString(); })()" class="px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-all duration-200 flex items-center space-x-2">
                <i class="fas fa-file-excel"></i>
                <span>Export ke CSV</span>
            </a>
            <button type="button" disabled class="px-6 py-3 bg-red-500/50 text-white rounded-lg font-semibold cursor-not-allowed transition-all duration-200 flex items-center space-x-2" title="Segera hadir">
                <i class="fas fa-file-pdf"></i>
                <span>Export ke PDF</span>
            </button>
            <button type="button" onclick="window.print()" class="px-6 py-3 bg-neutral-800 text-white rounded-lg font-semibold hover:bg-neutral-700 transition-all duration-200 flex items-center space-x-2 border border-neutral-700">
                <i class="fas fa-print"></i>
                <span>Print</span>
            </button>
        </div>
    </form>
    
    <!-- Sales Report -->
    <div x-show="reportType === 'sales'" class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Total Penjualan</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format((int) ($totalSales ?? 0), 0, ',', '.') }}</h3>
                <span class="text-xs text-neutral-500 mt-2 inline-block">Periode: {{ ($start ?? now())->format('d M Y') }} - {{ ($end ?? now())->format('d M Y') }}</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Total Transaksi</p>
                <h3 class="text-2xl font-bold">{{ $totalTransactions ?? 0 }}</h3>
                <span class="text-xs text-neutral-500 mt-2 inline-block">Paid</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Rata-rata Transaksi</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format((int) ($avgTransaction ?? 0), 0, ',', '.') }}</h3>
                <span class="text-xs text-neutral-500 mt-2 inline-block">Avg per transaksi</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Produk Terjual</p>
                <h3 class="text-2xl font-bold">{{ $productsSold ?? 0 }}</h3>
                <span class="text-xs text-neutral-500 mt-2 inline-block">Unit</span>
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
                    @forelse(($productRows ?? []) as $row)
                    <tr class="hover:bg-neutral-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-neutral-800 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-neutral-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $row->product->name ?? 'Produk' }}</p>
                                    <p class="text-xs text-neutral-400">SKU-{{ $row->product->sku ?? '000' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold">{{ (int) $row->qty }} unit</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format((int) $row->revenue, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">{{ (int) ($row->product->stock ?? 0) }} unit</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center">
                                @php $pct = min(100, max(0, (int) ($row->qty))); @endphp
                                <div class="w-24 bg-neutral-800 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-6 text-center text-neutral-400">Tidak ada data produk.</td></tr>
                    @endforelse
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
                    @forelse(($transactions ?? []) as $trx)
                    <tr class="hover:bg-neutral-800/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-sm">{{ $trx->code }}</td>
                        <td class="px-6 py-4 text-sm">{{ $trx->created_at?->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $trx->cashier->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $trx->member->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format((int) $trx->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @php $method = $trx->payment_method; $color = $method === 'transfer' ? 'blue' : ($method === 'qris' ? 'purple' : 'green'); @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-{{ $color }}-500/10 text-{{ $color }}-500">
                                {{ ucfirst($method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold bg-green-500/10 text-green-500 rounded">{{ ucfirst($trx->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-6 text-center text-neutral-400">Tidak ada transaksi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Members Report -->
    <div x-show="reportType === 'members'" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Total Member</p>
                <h3 class="text-2xl font-bold">{{ $totalMembers ?? 0 }}</h3>
                <span class="text-xs text-neutral-500 mt-2 inline-block">Terdaftar</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Total Pembelian Member</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format((int) ($memberSales ?? 0), 0, ',', '.') }}</h3>
                <span class="text-xs text-neutral-500 mt-2 inline-block">Periode dipilih</span>
            </div>
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <p class="text-sm text-neutral-400 mb-2">Rata-rata per Member</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format((int) ($avgPerMember ?? 0), 0, ',', '.') }}</h3>
                <span class="text-xs text-neutral-500 mt-2 inline-block">Keseluruhan</span>
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
                        @forelse(($memberRows ?? []) as $row)
                        <tr class="hover:bg-neutral-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold">{{ substr($row->member->name ?? 'M', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $row->member->name ?? '-' }}</p>
                                        <p class="text-xs text-neutral-400">ID: MBR-{{ str_pad((string) ($row->member->id ?? 0), 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $t = $row->member->tier ?? null;
                                    $color = $t?->color ?? '#9ca3af';
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $color }}22; color: {{ $color }};">
                                    {{ $t?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold">{{ (int) $row->trx_count }}</td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format((int) $row->spend, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center text-sm text-neutral-400">{{ \Carbon\Carbon::parse($row->last_at)->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-6 text-center text-neutral-400">Tidak ada data member pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@php($role = $role ?? 'admin')

<script>
    const reportCtx = document.getElementById('reportChart');
    if (reportCtx) {
        const reportChart = new Chart(reportCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? []) !!},
                datasets: [{
                    label: 'Penjualan',
                    data: {!! json_encode($chartData ?? []) !!},
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
