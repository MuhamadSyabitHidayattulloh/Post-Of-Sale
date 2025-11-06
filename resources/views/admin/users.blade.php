@extends('layouts.dashboard')

@section('title', 'Manajemen User - Admin')

@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola admin, kasir, dan member')

@section('page-content')
<div class="space-y-6" x-data="{ 
    activeTab: 'users', 
    showModal: false, 
    modalMode: 'add', 
    userType: 'kasir',
    showTierModal: false,
    editingTier: 'bronze'
}">
    <!-- Tabs -->
    <div class="flex items-center space-x-4 border-b border-neutral-800">
        <button @click="activeTab = 'users'" 
                :class="activeTab === 'users' ? 'border-white text-white' : 'border-transparent text-neutral-400 hover:text-white'"
                class="px-4 py-3 border-b-2 font-semibold transition-colors">
            <i class="fas fa-users mr-2"></i>User Management
        </button>
        <button @click="activeTab = 'tiers'" 
                :class="activeTab === 'tiers' ? 'border-white text-white' : 'border-transparent text-neutral-400 hover:text-white'"
                class="px-4 py-3 border-b-2 font-semibold transition-colors">
            <i class="fas fa-crown mr-2"></i>Member Tiers
        </button>
    </div>
    
    <!-- User Management Tab -->
    <div x-show="activeTab === 'users'" class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-shield text-purple-500 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold">3</span>
                </div>
                <h3 class="font-semibold mb-1">Admin</h3>
                <p class="text-sm text-neutral-400">Full Access</p>
            </div>
            
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cash-register text-blue-500 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold">12</span>
                </div>
                <h3 class="font-semibold mb-1">Kasir</h3>
                <p class="text-sm text-neutral-400">POS Access</p>
            </div>
            
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-green-500 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold">75</span>
                </div>
                <h3 class="font-semibold mb-1">Member</h3>
                <p class="text-sm text-neutral-400">Customer</p>
            </div>
        </div>
        
        <!-- Filter & Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <button @click="showModal = true; modalMode = 'add'; userType = 'admin'" 
                        class="px-4 py-2.5 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah User</span>
                </button>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                    <input type="text" placeholder="Cari user..." 
                           class="bg-neutral-900 border border-neutral-800 rounded-lg pl-12 pr-4 py-2.5 focus:outline-none focus:border-neutral-600 transition-colors w-64">
                </div>
                <select class="bg-neutral-900 border border-neutral-800 rounded-lg px-4 py-2.5 focus:outline-none focus:border-neutral-600 transition-colors">
                    <option>Semua Role</option>
                    <option>Admin</option>
                    <option>Kasir</option>
                    <option>Member</option>
                </select>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-800 border-b border-neutral-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">User</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Role</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Bergabung</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800">
                        @php
                            $users = [
                                ['name' => 'Admin Utama', 'email' => 'admin@kasir.com', 'role' => 'Admin', 'status' => 'active', 'date' => '2024-01-15'],
                                ['name' => 'Budi Santoso', 'email' => 'budi@kasir.com', 'role' => 'Kasir', 'status' => 'active', 'date' => '2024-02-20'],
                                ['name' => 'Siti Nurhaliza', 'email' => 'siti@kasir.com', 'role' => 'Kasir', 'status' => 'active', 'date' => '2024-03-10'],
                                ['name' => 'Ahmad Rizki', 'email' => 'ahmad@gmail.com', 'role' => 'Member', 'status' => 'active', 'date' => '2024-04-05'],
                                ['name' => 'Dewi Lestari', 'email' => 'dewi@gmail.com', 'role' => 'Member', 'status' => 'inactive', 'date' => '2024-05-12'],
                            ];
                        @endphp
                        
                        @foreach($users as $index => $user)
                        <tr class="hover:bg-neutral-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-bold">{{ substr($user['name'], 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $user['name'] }}</p>
                                        <p class="text-xs text-neutral-400">ID: USR-{{ str_pad($index + 1, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $user['email'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $user['role'] === 'Admin' ? 'bg-purple-500/10 text-purple-500' : '' }}
                                    {{ $user['role'] === 'Kasir' ? 'bg-blue-500/10 text-blue-500' : '' }}
                                    {{ $user['role'] === 'Member' ? 'bg-green-500/10 text-green-500' : '' }}">
                                    {{ $user['role'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user['status'] === 'active' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                                    {{ $user['status'] === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-400">{{ date('d M Y', strtotime($user['date'])) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <button @click="showModal = true; modalMode = 'edit'" 
                                            class="p-2 hover:bg-neutral-700 rounded-lg transition-colors" 
                                            title="Edit">
                                        <i class="fas fa-edit text-blue-500"></i>
                                    </button>
                                    <button class="p-2 hover:bg-neutral-700 rounded-lg transition-colors" 
                                            title="Hapus">
                                        <i class="fas fa-trash text-red-500"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Member Tiers Tab -->
    <div x-show="activeTab === 'tiers'" class="space-y-6">
        <!-- Tier Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Bronze Tier -->
            <div class="bg-gradient-to-br from-orange-900/20 to-transparent border-2 border-orange-800/50 rounded-xl p-8 hover:border-orange-600/50 transition-all">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-medal text-orange-500 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Bronze</h3>
                    <p class="text-neutral-400">Tier Pemula</p>
                </div>
                
                <div class="space-y-4 mb-6">
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-800">
                        <span class="text-sm text-neutral-400">Member</span>
                        <span class="font-semibold">32</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-800">
                        <span class="text-sm text-neutral-400">Min. Pembelian</span>
                        <span class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-400">Diskon</span>
                        <span class="font-semibold text-orange-500">5%</span>
                    </div>
                </div>
                
                <div class="space-y-2 mb-6">
                    <h4 class="font-semibold text-sm mb-3">Benefit:</h4>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Diskon 5% setiap pembelian</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Akumulasi poin</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Info promo eksklusif</span>
                    </div>
                </div>
                
                <button @click="showTierModal = true; editingTier = 'bronze'" class="w-full px-4 py-3 bg-orange-500/10 border border-orange-500/30 text-orange-500 rounded-lg font-semibold hover:bg-orange-500/20 transition-colors">
                    Edit Tier
                </button>
            </div>
            
            <!-- Silver Tier -->
            <div class="bg-gradient-to-br from-gray-400/20 to-transparent border-2 border-gray-400/50 rounded-xl p-8 hover:border-gray-400/70 transition-all transform scale-105">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gray-400/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-medal text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Silver</h3>
                    <p class="text-neutral-400">Tier Menengah</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-yellow-500/10 text-yellow-500 text-xs font-semibold rounded-full">Popular</span>
                </div>
                
                <div class="space-y-4 mb-6">
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-800">
                        <span class="text-sm text-neutral-400">Member</span>
                        <span class="font-semibold">28</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-800">
                        <span class="text-sm text-neutral-400">Min. Pembelian</span>
                        <span class="font-semibold">Rp 1jt</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-400">Diskon</span>
                        <span class="font-semibold text-gray-400">10%</span>
                    </div>
                </div>
                
                <div class="space-y-2 mb-6">
                    <h4 class="font-semibold text-sm mb-3">Benefit:</h4>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Diskon 10% setiap pembelian</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Akumulasi poin 2x</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Gratis ongkir 1x/bulan</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Priority support</span>
                    </div>
                </div>
                
                <button @click="showTierModal = true; editingTier = 'silver'" class="w-full px-4 py-3 bg-gray-400/10 border border-gray-400/30 text-gray-300 rounded-lg font-semibold hover:bg-gray-400/20 transition-colors">
                    Edit Tier
                </button>
            </div>
            
            <!-- Gold Tier -->
            <div class="bg-gradient-to-br from-yellow-500/20 to-transparent border-2 border-yellow-500/50 rounded-xl p-8 hover:border-yellow-500/70 transition-all">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-crown text-yellow-500 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Gold</h3>
                    <p class="text-neutral-400">Tier Premium</p>
                </div>
                
                <div class="space-y-4 mb-6">
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-800">
                        <span class="text-sm text-neutral-400">Member</span>
                        <span class="font-semibold">15</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-800">
                        <span class="text-sm text-neutral-400">Min. Pembelian</span>
                        <span class="font-semibold">Rp 5jt</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-400">Diskon</span>
                        <span class="font-semibold text-yellow-500">15%</span>
                    </div>
                </div>
                
                <div class="space-y-2 mb-6">
                    <h4 class="font-semibold text-sm mb-3">Benefit:</h4>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Diskon 15% setiap pembelian</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Akumulasi poin 3x</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Gratis ongkir unlimited</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">VIP support 24/7</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <span class="text-sm text-neutral-400">Early access produk baru</span>
                    </div>
                </div>
                
                <button @click="showTierModal = true; editingTier = 'gold'" class="w-full px-4 py-3 bg-yellow-500/10 border border-yellow-500/30 text-yellow-500 rounded-lg font-semibold hover:bg-yellow-500/20 transition-colors">
                    Edit Tier
                </button>
            </div>
        </div>
        
        <!-- Tier Settings -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-6">Pengaturan Tier</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-neutral-800">
                    <div>
                        <p class="font-medium">Auto Upgrade Tier</p>
                        <p class="text-sm text-neutral-400">Otomatis upgrade tier berdasarkan total pembelian</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-neutral-800">
                    <div>
                        <p class="font-medium">Poin Rewards</p>
                        <p class="text-sm text-neutral-400">Aktifkan sistem poin untuk member</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div>
                        <p class="font-medium">Email Notifikasi Upgrade</p>
                        <p class="text-sm text-neutral-400">Kirim email saat member naik tier</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit User Modal -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         style="display: none;"
         @click.self="showModal = false">
        
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-neutral-900 border border-neutral-800 rounded-xl w-full max-w-md">
            
            <div class="px-6 py-4 border-b border-neutral-800 flex items-center justify-between">
                <h3 class="text-xl font-bold" x-text="modalMode === 'add' ? 'Tambah User Baru' : 'Edit User'"></h3>
                <button @click="showModal = false" class="p-2 hover:bg-neutral-800 rounded-lg transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                    <input type="text" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="Masukkan nama lengkap">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="email@example.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="••••••••">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Role</label>
                    <select x-model="userType" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                        <option value="member">Member</option>
                    </select>
                </div>
                
                <div x-show="userType === 'member'">
                    <label class="block text-sm font-medium mb-2">Tier Member</label>
                    <select class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                        <option value="bronze">Bronze</option>
                        <option value="silver">Silver</option>
                        <option value="gold">Gold</option>
                    </select>
                </div>
                
                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" checked class="w-5 h-5 bg-neutral-800 border-neutral-700 rounded">
                        <span class="text-sm font-medium">Aktifkan user</span>
                    </label>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-neutral-800">
                    <button type="button" @click="showModal = false" class="px-6 py-2.5 bg-neutral-800 rounded-lg font-medium hover:bg-neutral-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-colors">
                        <span x-text="modalMode === 'add' ? 'Tambah User' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Tier Modal -->
    <div x-show="showTierModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         style="display: none;"
         @click.self="showTierModal = false">
        
        <div x-show="showTierModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-neutral-900 border border-neutral-800 rounded-xl w-full max-w-md">
            
            <div class="px-6 py-4 border-b border-neutral-800 flex items-center justify-between">
                <h3 class="text-xl font-bold">
                    Edit Tier <span x-text="editingTier.charAt(0).toUpperCase() + editingTier.slice(1)" class="capitalize"></span>
                </h3>
                <button @click="showTierModal = false" class="p-2 hover:bg-neutral-800 rounded-lg transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Tier</label>
                    <input type="text" 
                           :value="editingTier.charAt(0).toUpperCase() + editingTier.slice(1)"
                           class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" 
                           placeholder="Nama tier">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Deskripsi</label>
                    <input type="text" 
                           class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" 
                           :placeholder="editingTier === 'bronze' ? 'Tier Pemula' : editingTier === 'silver' ? 'Tier Menengah' : 'Tier Premium'">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Minimum Pembelian (Rp)</label>
                    <input type="number" 
                           class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" 
                           :value="editingTier === 'bronze' ? '0' : editingTier === 'silver' ? '1000000' : '5000000'"
                           placeholder="0">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Diskon (%)</label>
                    <input type="number" 
                           class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" 
                           :value="editingTier === 'bronze' ? '5' : editingTier === 'silver' ? '10' : '15'"
                           placeholder="0" 
                           min="0" 
                           max="100">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Multiplier Poin</label>
                    <select class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                        <option value="1" :selected="editingTier === 'bronze'">1x</option>
                        <option value="2" :selected="editingTier === 'silver'">2x</option>
                        <option value="3" :selected="editingTier === 'gold'">3x</option>
                        <option value="4">4x</option>
                        <option value="5">5x</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Warna Icon</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" 
                               :value="editingTier === 'bronze' ? '#f97316' : editingTier === 'silver' ? '#9ca3af' : '#eab308'"
                               class="w-16 h-10 bg-neutral-800 border border-neutral-700 rounded-lg cursor-pointer">
                        <span class="text-sm text-neutral-400">Pilih warna untuk tier</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Benefit (pisahkan dengan enter)</label>
                    <textarea rows="4" 
                              class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500 resize-none" 
                              placeholder="Masukkan benefit tier..."></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-neutral-800">
                    <button type="button" @click="showTierModal = false" class="px-6 py-2.5 bg-neutral-800 rounded-lg font-medium hover:bg-neutral-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $role = 'admin';
@endphp
@endsection
