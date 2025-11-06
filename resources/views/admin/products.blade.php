@extends('layouts.dashboard')

@section('title', 'Produk - Admin')

@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'Kelola semua produk di toko Anda')

@section('page-content')
<div class="space-y-6" x-data="{
    viewMode: 'grid', showModal: false, modalMode: 'add',
    // form state
    storeUrl: '{{ route('admin.products.store') }}',
    updateUrlTemplate: '{{ url('/admin/products/__ID__') }}',
    formAction: '{{ route('admin.products.store') }}',
    editId: null,
    name: '', sku: '', category_id: '', price: '', stock: '', barcode: '', description: '', is_active: true,
    openAdd() {
        this.modalMode = 'add'; this.formAction = this.storeUrl; this.editId = null;
        this.name=''; this.sku=''; this.category_id=''; this.price=''; this.stock=''; this.barcode=''; this.description=''; this.is_active=true;
        this.showModal = true;
    },
    openEdit(p) {
        this.modalMode = 'edit'; this.editId = p.id; this.formAction = this.updateUrlTemplate.replace('__ID__', p.id);
        this.name = p.name || ''; this.sku = p.sku || ''; this.category_id = p.category_id || ''; this.price = p.price || '';
        this.stock = p.stock || ''; this.barcode = p.barcode || ''; this.description = p.description || ''; this.is_active = !!p.is_active;
        this.showModal = true;
    }
}">
    @if(session('status'))
        <div class="bg-green-500/10 border border-green-600 text-green-400 px-4 py-3 rounded-lg">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-500/10 border border-red-600 text-red-400 px-4 py-3 rounded-lg">{{ $errors->first() }}</div>
    @endif
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <button @click="openAdd()" 
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
            <form method="GET" class="md:col-span-2 relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." 
                       class="w-full bg-neutral-800 border border-neutral-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors">
            </form>
            
            <!-- Category Filter -->
            <form method="GET">
                <select name="category_id" class="bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id')==$cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </form>
            
            <!-- Stock Filter -->
            <form method="GET">
                <select name="stock" class="bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors" onchange="this.form.submit()">
                    <option value="">Semua Stok</option>
                    <option value="available" @selected(request('stock')==='available')>Stok Tersedia</option>
                    <option value="low" @selected(request('stock')==='low')>Stok Rendah (&lt;10)</option>
                    <option value="out" @selected(request('stock')==='out')>Stok Habis</option>
                </select>
            </form>
        </div>
        
        <!-- View Mode Toggle -->
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-neutral-800">
            <p class="text-sm text-neutral-400">Menampilkan <span class="font-semibold text-white">{{ $products->total() }}</span> produk</p>
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
        @forelse($products as $product)
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden hover:border-neutral-600 transition-all duration-300 group">
            <!-- Product Image -->
            <div class="relative h-48 bg-neutral-800 flex items-center justify-center overflow-hidden">
                <i class="fas fa-box text-6xl text-neutral-700 group-hover:scale-110 transition-transform duration-300"></i>
                @if($product->stock > 0 && $product->stock < 10)
                <span class="absolute top-3 right-3 px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded">
                    Stok Rendah
                </span>
                @elseif($product->sold_count > 50)
                <span class="absolute top-3 right-3 px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded">
                    Best Seller
                </span>
                @endif
                
                <!-- Hover Actions -->
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-2">
                    <button type="button"
                            data-product="{{ htmlspecialchars(json_encode(['id'=>$product->id,'name'=>$product->name,'sku'=>$product->sku,'category_id'=>$product->category_id,'price'=>$product->price,'stock'=>$product->stock,'barcode'=>$product->barcode,'description'=>$product->description,'is_active'=>$product->is_active]), ENT_QUOTES, 'UTF-8') }}" 
                            @click="openEdit(JSON.parse($event.target.closest('button').getAttribute('data-product')))" 
                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:scale-110 transition-transform">
                        <i class="fas fa-edit text-black"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Hapus produk ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center hover:scale-110 transition-transform">
                            <i class="fas fa-trash text-white"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                    <span class="text-xs px-2 py-1 bg-neutral-800 rounded">{{ $product->sku }}</span>
                </div>
                <p class="text-sm text-neutral-400 mb-3">{{ $product->category?->name }}</p>
                
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xl font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-neutral-400">Stok:</span>
                    <span class="font-semibold {{ $product->stock < 10 ? 'text-red-500' : 'text-green-500' }}">
                        {{ $product->stock }} unit
                    </span>
                </div>
                
                <div class="flex items-center justify-between text-sm mt-2">
                    <span class="text-neutral-400">Terjual:</span>
                    <span class="font-semibold">{{ $product->sold_count }} unit</span>
                </div>
            </div>
        </div>
        @empty
            <div class="col-span-4 text-center text-neutral-400">Tidak ada produk.</div>
        @endforelse
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
                    @forelse($products as $product)
                    <tr class="hover:bg-neutral-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-neutral-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-box text-neutral-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $product->name }}</p>
                                    <p class="text-xs text-neutral-400">ID: PRD-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $product->sku }}</td>
                        <td class="px-6 py-4 text-sm">{{ $product->category?->name }}</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $product->stock < 10 ? 'bg-red-500/10 text-red-500' : 'bg-green-500/10 text-green-500' }}">
                                {{ $product->stock }} unit
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">{{ $product->sold_count }} unit</td>
                        <td class="px-6 py-4 text-center">
                            @if($product->is_active)
                            <span class="px-2 py-1 text-xs font-semibold bg-green-500/10 text-green-500 rounded">Aktif</span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold bg-neutral-700 text-neutral-400 rounded">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button"
                                        data-product="{{ htmlspecialchars(json_encode(['id'=>$product->id,'name'=>$product->name,'sku'=>$product->sku,'category_id'=>$product->category_id,'price'=>$product->price,'stock'=>$product->stock,'barcode'=>$product->barcode,'description'=>$product->description,'is_active'=>$product->is_active]), ENT_QUOTES, 'UTF-8') }}" 
                                        @click="openEdit(JSON.parse($event.target.closest('button').getAttribute('data-product')))" 
                                        class="p-2 hover:bg-neutral-700 rounded-lg transition-colors" 
                                        title="Edit">
                                    <i class="fas fa-edit text-blue-500"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline" onsubmit="return confirm('Hapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-neutral-700 rounded-lg transition-colors" title="Hapus">
                                        <i class="fas fa-trash text-red-500"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-neutral-400">Tidak ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-neutral-800 flex items-center justify-between">
            <p class="text-sm text-neutral-400">Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari {{ $products->total() }} produk</p>
            <div class="text-right">{{ $products->onEachSide(1)->links() }}</div>
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
            <form class="p-6 space-y-6" method="POST" :action="formAction">
                @csrf
                <template x-if="modalMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
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
                        <input type="text" name="name" x-model="name" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="Masukkan nama produk" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">SKU</label>
                        <input type="text" name="sku" x-model="sku" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="SKU-001" required>
                    </div>
                </div>
                
                <!-- Category & Price -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Kategori</label>
                        <select name="category_id" x-model="category_id" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Harga</label>
                        <input type="number" name="price" step="0.01" x-model="price" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="100000" required>
                    </div>
                </div>
                
                <!-- Stock & Barcode -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Stok</label>
                        <input type="number" name="stock" x-model="stock" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="50" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Barcode</label>
                        <input type="text" name="barcode" x-model="barcode" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="1234567890">
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium mb-2">Deskripsi</label>
                    <textarea rows="4" name="description" x-model="description" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="Deskripsi produk..."></textarea>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="is_active" :checked="is_active" value="1" class="w-5 h-5 bg-neutral-800 border-neutral-700 rounded">
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
