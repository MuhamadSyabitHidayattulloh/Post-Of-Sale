@extends('layouts.dashboard')

@section('title', 'Point of Sale - Kasir')

@section('page-title', 'Point of Sale')
@section('page-subtitle', 'Sistem kasir untuk transaksi')

@section('page-content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data='posApp(@json($posPayload))'
    x-init="init()">
    
    <script>
    function posApp(initial){
        return {
            products: initial.products || [],
            categories: initial.categories || [],
            members: initial.members || [],
            selectedCategoryId: null,
            cart: [],
            searchQuery: '',
            barcodeInput: '',
            selectedMember: null,
            paymentMethod: 'cash',
            cashReceived: 0,
            init(){},
            get filteredProducts(){
                const q = this.searchQuery.toLowerCase();
                return this.products.filter(p => {
                    const byCat = this.selectedCategoryId ? (p.category_id === this.selectedCategoryId) : true;
                    const byName = q ? (p.name.toLowerCase().includes(q) || (p.sku||'').toLowerCase().includes(q)) : true;
                    return byCat && byName;
                });
            },
            addToCart(product) {
                const existingItem = this.cart.find(item => item.id === product.id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    this.cart.push({...product, quantity: 1});
                }
            },
            removeFromCart(index) {
                this.cart.splice(index, 1);
            },
            updateQuantity(index, change) {
                this.cart[index].quantity += change;
                if (this.cart[index].quantity <= 0) {
                    this.removeFromCart(index);
                }
            },
            get subtotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },
            get discount() {
                if (!this.selectedMember) return 0;
                const tier = (this.selectedMember.tier||'').toLowerCase();
                const discountRate = tier === 'gold' ? 0.15 : tier === 'silver' ? 0.10 : 0.05;
                return this.subtotal * discountRate;
            },
            get tax() {
                return (this.subtotal - this.discount) * 0.11;
            },
            get total() {
                return this.subtotal - this.discount + this.tax;
            },
            get change() {
                return this.cashReceived - this.total;
            },
            clearCart() {
                this.cart = [];
                this.selectedMember = null;
                this.cashReceived = 0;
                this.barcodeInput = '';
            },
                    async checkout() {
                if (this.cart.length === 0) {
                    alert('Keranjang masih kosong!');
                    return;
                }
                if (this.paymentMethod === 'cash' && this.cashReceived < this.total) {
                    alert('Uang yang diterima kurang!');
                    return;
                }

                const payload = {
                    items: this.cart.map(i => ({ product_id: i.id, quantity: i.quantity })),
                    member_id: this.selectedMember ? this.selectedMember.id : null,
                    payment_method: this.paymentMethod,
                    paid_amount: this.paymentMethod === 'cash' ? this.cashReceived : this.total,
                };
                try {
                    const res = await fetch('{{ route('kasir.checkout') }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (!res.ok || !data.success) throw new Error(data.message || 'Gagal checkout');
                    alert('Transaksi berhasil! Kode: ' + data.transaction.code + ' Total: Rp ' + Number(data.transaction.total).toLocaleString('id-ID'));
                    this.clearCart();
                } catch (e) {
                    alert(e.message);
                }
            },
            async scanBarcode() {
                if (!this.barcodeInput) return;
                try {
                    const url = new URL('{{ route('kasir.barcode') }}', window.location.origin);
                    url.searchParams.set('code', this.barcodeInput);
                    const res = await fetch(url.toString());
                    const data = await res.json();
                    if (data.success && data.product) {
                        this.addToCart({ id: data.product.id, name: data.product.name, price: Number(data.product.price), stock: Number(data.product.stock), sku: data.product.sku });
                        this.barcodeInput = '';
                    } else {
                        alert(data.message || 'Produk tidak ditemukan');
                    }
                } catch (e) {
                    alert('Gagal scan barcode');
                }
            }
        };
    }
    </script>
    
    <!-- Left Section - Products -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Barcode Scanner & Search -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Barcode Scanner -->
                <div>
                    <label class="block text-sm font-medium mb-2">Scan Barcode</label>
                    <div class="flex space-x-2">
                        <div class="relative flex-1">
                            <i class="fas fa-barcode absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                            <input 
                                type="text" 
                                x-model="barcodeInput"
                                @keyup.enter="scanBarcode()"
                                placeholder="Scan atau ketik barcode..." 
                                class="w-full bg-neutral-800 border border-neutral-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors">
                        </div>
                        <button 
                            @click="scanBarcode()"
                            class="px-4 py-3 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Manual Search -->
                <div>
                    <label class="block text-sm font-medium mb-2">Cari Produk</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                        <input 
                            type="text" 
                            x-model="searchQuery"
                            placeholder="Cari nama produk..." 
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Categories (Quick Filter) -->
        <div class="flex space-x-2 overflow-x-auto pb-2">
            <button @click="selectedCategoryId = null" :class="selectedCategoryId===null ? 'bg-white text-black' : 'bg-neutral-800 text-neutral-400'" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap">Semua</button>
            <template x-for="cat in categories" :key="cat.id">
                <button @click="selectedCategoryId = cat.id" :class="selectedCategoryId===cat.id ? 'bg-white text-black' : 'bg-neutral-800 text-neutral-400'" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap hover:bg-neutral-700 transition-colors" x-text="cat.name"></button>
            </template>
        </div>
        
        <!-- Products Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            <template x-for="p in filteredProducts" :key="p.id">
                <button @click="addToCart(p)" class="bg-neutral-900 border border-neutral-800 rounded-xl p-4 hover:border-neutral-600 transition-all duration-200 hover:scale-105 text-left">
                    <div class="aspect-square bg-neutral-800 rounded-lg flex items-center justify-center mb-3">
                        <i class="fas fa-box text-4xl text-neutral-600"></i>
                    </div>
                    <h4 class="font-semibold text-sm mb-1 truncate" x-text="p.name"></h4>
                    <p class="text-xs text-neutral-400 mb-2" x-text="'Stok: ' + p.stock"></p>
                    <p class="font-bold text-sm" x-text="'Rp ' + Number(p.price).toLocaleString('id-ID')"></p>
                </button>
            </template>
        </div>
    </div>
    
    <!-- Right Section - Cart & Checkout -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Member Selection -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-4">
            <label class="block text-sm font-medium mb-2">Member (Opsional)</label>
            <div x-data="{ showMemberModal: false }">
                <button 
                    @click="showMemberModal = true"
                    class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 rounded-lg text-left hover:bg-neutral-700 transition-colors">
                    <div class="flex items-center justify-between">
                        <span x-text="selectedMember ? selectedMember.name : 'Pilih Member'" 
                              :class="selectedMember ? 'text-white' : 'text-neutral-400'"></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>
                
                <template x-if="selectedMember">
                    <div class="mt-2 p-3 bg-gradient-to-r from-yellow-500/10 to-transparent border border-yellow-500/30 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold" x-text="selectedMember.name"></p>
                                <p class="text-xs text-neutral-400">
                                    Tier: <span class="capitalize" x-text="selectedMember.tier"></span> - 
                                    Diskon: <span x-text="selectedMember.tier === 'gold' ? '15%' : selectedMember.tier === 'silver' ? '10%' : '5%'"></span>
                                </p>
                            </div>
                            <button @click="selectedMember = null" class="text-red-500 hover:text-red-400">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </template>
                
                <!-- Member Selection Modal -->
                <div x-show="showMemberModal"
                     x-transition
                     @click.self="showMemberModal = false"
                     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                     style="display: none;">
                    <div class="bg-neutral-900 border border-neutral-800 rounded-xl w-full max-w-md max-h-[80vh] overflow-hidden">
                        <div class="px-6 py-4 border-b border-neutral-800 flex items-center justify-between">
                            <h3 class="text-lg font-bold">Pilih Member</h3>
                            <button @click="showMemberModal = false">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <input type="text" placeholder="Cari member..." 
                                   class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-2 mb-4 focus:outline-none focus:border-neutral-500">
                            <div class="space-y-2 max-h-96 overflow-y-auto">
                                <template x-for="m in members" :key="m.id">
                                    <button @click="selectedMember = m; showMemberModal = false" class="w-full p-3 bg-neutral-800 hover:bg-neutral-700 rounded-lg text-left transition-colors">
                                        <p class="font-semibold text-sm" x-text="m.name"></p>
                                        <p class="text-xs text-neutral-400">Tier: <span class="capitalize" x-text="m.tier"></span></p>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cart -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-neutral-800 flex items-center justify-between">
                <h3 class="font-semibold">Keranjang</h3>
                <span class="text-xs bg-neutral-800 px-2 py-1 rounded" x-text="cart.length + ' item'"></span>
            </div>
            
            <div class="max-h-[400px] overflow-y-auto p-4">
                <template x-if="cart.length === 0">
                    <div class="text-center py-12">
                        <i class="fas fa-shopping-cart text-6xl text-neutral-700 mb-4"></i>
                        <p class="text-neutral-400 text-sm">Keranjang masih kosong</p>
                    </div>
                </template>
                
                <div class="space-y-3">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="bg-neutral-800 rounded-lg p-3">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <p class="font-semibold text-sm" x-text="item.name"></p>
                                    <p class="text-xs text-neutral-400" x-text="'Rp ' + item.price.toLocaleString('id-ID')"></p>
                                </div>
                                <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-400 ml-2">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 bg-neutral-900 rounded-lg">
                                    <button @click="updateQuantity(index, -1)" 
                                            class="w-8 h-8 flex items-center justify-center hover:bg-neutral-700 rounded-lg transition-colors">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span class="w-8 text-center font-semibold" x-text="item.quantity"></span>
                                    <button @click="updateQuantity(index, 1)" 
                                            class="w-8 h-8 flex items-center justify-center hover:bg-neutral-700 rounded-lg transition-colors">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="font-bold text-sm" x-text="'Rp ' + (item.price * item.quantity).toLocaleString('id-ID')"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        
        <!-- Summary -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-4">
            <div class="space-y-3 mb-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-neutral-400">Subtotal</span>
                    <span class="font-semibold" x-text="'Rp ' + subtotal.toLocaleString('id-ID')">Rp 0</span>
                </div>
                
                <template x-if="selectedMember">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-green-500">Diskon Member</span>
                        <span class="font-semibold text-green-500" x-text="'- Rp ' + discount.toLocaleString('id-ID')">Rp 0</span>
                    </div>
                </template>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-neutral-400">Pajak (11%)</span>
                    <span class="font-semibold" x-text="'Rp ' + tax.toLocaleString('id-ID')">Rp 0</span>
                </div>
                
                <div class="pt-3 border-t border-neutral-800 flex items-center justify-between">
                    <span class="font-semibold">Total</span>
                    <span class="text-2xl font-bold" x-text="'Rp ' + total.toLocaleString('id-ID')">Rp 0</span>
                </div>
            </div>
            
            <!-- Payment Method -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-2">
                    <button 
                        @click="paymentMethod = 'cash'"
                        :class="paymentMethod === 'cash' ? 'bg-white text-black' : 'bg-neutral-800 text-neutral-400'"
                        class="px-4 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-money-bill-wave mr-2"></i>Cash
                    </button>
                    <button 
                        @click="paymentMethod = 'transfer'"
                        :class="paymentMethod === 'transfer' ? 'bg-white text-black' : 'bg-neutral-800 text-neutral-400'"
                        class="px-4 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-credit-card mr-2"></i>Transfer
                    </button>
                </div>
            </div>
            
            <!-- Cash Input -->
            <div x-show="paymentMethod === 'cash'" class="mb-4">
                <label class="block text-sm font-medium mb-2">Uang Diterima</label>
                <input 
                    type="number" 
                    x-model.number="cashReceived"
                    placeholder="0"
                    class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors">
                <template x-if="cashReceived >= total && total > 0">
                    <p class="text-sm text-green-500 mt-2">
                        Kembalian: <span class="font-bold" x-text="'Rp ' + change.toLocaleString('id-ID')"></span>
                    </p>
                </template>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <button 
                    @click="clearCart()"
                    class="flex-1 px-4 py-3 bg-neutral-800 text-white rounded-lg font-semibold hover:bg-neutral-700 transition-colors">
                    Clear
                </button>
                <button 
                    @click="checkout()"
                    :disabled="cart.length === 0"
                    :class="cart.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neutral-200'"
                    class="flex-1 px-4 py-3 bg-white text-black rounded-lg font-semibold transition-colors">
                    Checkout
                </button>
            </div>
        </div>
    </div>
</div>

@php($role = $role ?? 'kasir')
@php($userName = $userName ?? 'Kasir')
@endsection
