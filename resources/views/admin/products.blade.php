@extends('layouts.dashboard')

@section('title', 'Produk - Admin')

@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'Kelola semua produk di toko Anda')

@section('page-content')
<div class="space-y-6" x-data="{ viewMode: 'grid', showModal: false, modalMode: 'add' }">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <button @click="showModal = true; modalMode = 'add'" 
                    class="px-4 py-2.5 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-all duration-200 flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Produk</span>
            </button>
            <button class="px-4 py-2.5 bg-neutral-800 text-white rounded-lg font-medium hover:bg-neutral-700 transition-all duration-200 flex items-center space-x-2 border border-neutral-700">
                <i class="fas fa-upload"></i>
                <span>Import</span>
            </button>
            <button class="px-4 py-2.5 bg-neutral-800 text-white rounded-lg font-medium hover:bg-neutral-700 transition-all duration-200 flex items-center space-x-2 border border-neutral-700">
                <i class="fas fa-download"></i>
                <span>Export</span>
            </button>
        </div>
    </div>
    
    <!-- Filters & Search -->
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2 relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                <input type="text" placeholder="Cari produk..." 
                       class="w-full bg-neutral-800 border border-neutral-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors">
            </div>
            
            <!-- Category Filter -->
            <select class="bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors">
                <option>Semua Kategori</option>
                <option>Elektronik</option>
                <option>Fashion</option>
                <option>Makanan</option>
                <option>Minuman</option>
                <option>Peralatan</option>
            </select>
            
            <!-- Stock Filter -->
            <select class="bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors">
                <option>Semua Stok</option>
                <option>Stok Tersedia</option>
                <option>Stok Rendah (&lt;10)</option>
                <option>Stok Habis</option>
            </select>
        </div>
        
        <!-- View Mode Toggle -->
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-neutral-800">
            <p class="text-sm text-neutral-400">Menampilkan <span class="font-semibold text-white">248</span> produk</p>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-neutral-400 mr-2">Tampilan:</span>
                <button @click="viewMode = 'grid'" 
                        :class="viewMode === 'grid' ? 'bg-white text-black' : 'bg-neutral-800 text-neutral-400 hover:text-white'"
                        class="p-2 rounded-lg transition-all duration-200">
                    <i class="fas fa-th"></i>
                </button>
                <button @click="viewMode = 'list'" 
                        :class="viewMode === 'list' ? 'bg-white text-black' : 'bg-neutral-800 text-neutral-400 hover:text-white'"
                        class="p-2 rounded-lg transition-all duration-200">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Products Grid View -->
    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @for($i = 1; $i <= 12; $i++)
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden hover:border-neutral-600 transition-all duration-300 group">
            <!-- Product Image -->
            <div class="relative h-48 bg-neutral-800 flex items-center justify-center overflow-hidden">
                <i class="fas fa-box text-6xl text-neutral-700 group-hover:scale-110 transition-transform duration-300"></i>
                @if($i % 3 === 0)
                <span class="absolute top-3 right-3 px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded">
                    Stok Rendah
                </span>
                @elseif($i % 2 === 0)
                <span class="absolute top-3 right-3 px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded">
                    Best Seller
                </span>
                @endif
                
                <!-- Hover Actions -->
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-2">
                    <button @click="showModal = true; modalMode = 'edit'" 
                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:scale-110 transition-transform">
                        <i class="fas fa-edit text-black"></i>
                    </button>
                    <button class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center hover:scale-110 transition-transform">
                        <i class="fas fa-trash text-white"></i>
                    </button>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="font-semibold text-lg">Produk {{ $i }}</h3>
                    <span class="text-xs px-2 py-1 bg-neutral-800 rounded">SKU-{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <p class="text-sm text-neutral-400 mb-3">Kategori {{ $i % 5 === 0 ? 'Elektronik' : 'Fashion' }}</p>
                
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xl font-bold">Rp {{ number_format(100000 * $i, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-neutral-400">Stok:</span>
                    <span class="font-semibold {{ $i % 3 === 0 ? 'text-red-500' : 'text-green-500' }}">
                        {{ $i % 3 === 0 ? '5' : '50' }} unit
                    </span>
                </div>
                
                <div class="flex items-center justify-between text-sm mt-2">
                    <span class="text-neutral-400">Terjual:</span>
                    <span class="font-semibold">{{ 100 - ($i * 5) }} unit</span>
                </div>
            </div>
        </div>
        @endfor
    </div>
    
    <!-- Products List View -->
    <div x-show="viewMode === 'list'" class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-800 border-b border-neutral-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Produk</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">SKU</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kategori</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Harga</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Stok</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Terjual</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800">
                    @for($i = 1; $i <= 12; $i++)
                    <tr class="hover:bg-neutral-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-neutral-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-box text-neutral-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Produk {{ $i }}</p>
                                    <p class="text-xs text-neutral-400">ID: PRD-{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">SKU-{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 text-sm">{{ $i % 5 === 0 ? 'Elektronik' : 'Fashion' }}</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format(100000 * $i, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $i % 3 === 0 ? 'bg-red-500/10 text-red-500' : 'bg-green-500/10 text-green-500' }}">
                                {{ $i % 3 === 0 ? '5' : '50' }} unit
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">{{ 100 - ($i * 5) }} unit</td>
                        <td class="px-6 py-4 text-center">
                            @if($i % 2 === 0)
                            <span class="px-2 py-1 text-xs font-semibold bg-green-500/10 text-green-500 rounded">Aktif</span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold bg-neutral-700 text-neutral-400 rounded">Draft</span>
                            @endif
                        </td>
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
                    @endfor
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-neutral-800 flex items-center justify-between">
            <p class="text-sm text-neutral-400">Menampilkan 1-12 dari 248 produk</p>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-2 bg-neutral-800 rounded-lg hover:bg-neutral-700 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-3 py-2 bg-white text-black rounded-lg font-semibold">1</button>
                <button class="px-3 py-2 bg-neutral-800 rounded-lg hover:bg-neutral-700 transition-colors">2</button>
                <button class="px-3 py-2 bg-neutral-800 rounded-lg hover:bg-neutral-700 transition-colors">3</button>
                <span class="px-3 py-2 text-neutral-400">...</span>
                <button class="px-3 py-2 bg-neutral-800 rounded-lg hover:bg-neutral-700 transition-colors">21</button>
                <button class="px-3 py-2 bg-neutral-800 rounded-lg hover:bg-neutral-700 transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Product Modal -->
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
             class="bg-neutral-900 border border-neutral-800 rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-neutral-800 flex items-center justify-between sticky top-0 bg-neutral-900 z-10">
                <h3 class="text-xl font-bold" x-text="modalMode === 'add' ? 'Tambah Produk Baru' : 'Edit Produk'"></h3>
                <button @click="showModal = false" class="p-2 hover:bg-neutral-800 rounded-lg transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form class="p-6 space-y-6">
                <!-- Product Image -->
                <div>
                    <label class="block text-sm font-medium mb-2">Foto Produk</label>
                    <div class="border-2 border-dashed border-neutral-700 rounded-lg p-8 text-center hover:border-neutral-600 transition-colors cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-neutral-600 mb-3"></i>
                        <p class="text-sm text-neutral-400">Klik untuk upload atau drag & drop</p>
                        <p class="text-xs text-neutral-500 mt-1">PNG, JPG hingga 2MB</p>
                    </div>
                </div>
                
                <!-- Product Name & SKU -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Produk</label>
                        <input type="text" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="Masukkan nama produk">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">SKU</label>
                        <input type="text" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="SKU-001">
                    </div>
                </div>
                
                <!-- Category & Price -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Kategori</label>
                        <select class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                            <option>Pilih Kategori</option>
                            <option>Elektronik</option>
                            <option>Fashion</option>
                            <option>Makanan</option>
                            <option>Minuman</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Harga</label>
                        <input type="number" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="100000">
                    </div>
                </div>
                
                <!-- Stock & Barcode -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Stok</label>
                        <input type="number" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Barcode</label>
                        <input type="text" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="1234567890">
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium mb-2">Deskripsi</label>
                    <textarea rows="4" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="Deskripsi produk..."></textarea>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" checked class="w-5 h-5 bg-neutral-800 border-neutral-700 rounded">
                        <span class="text-sm font-medium">Aktifkan produk</span>
                    </label>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-neutral-800">
                    <button type="button" @click="showModal = false" class="px-6 py-2.5 bg-neutral-800 rounded-lg font-medium hover:bg-neutral-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-colors">
                        <span x-text="modalMode === 'add' ? 'Tambah Produk' : 'Simpan Perubahan'"></span>
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
