@extends('layouts.main')

@section('title', 'Home - Kasir App')

@section('content')
<div class="min-h-screen bg-neutral-950">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full bg-neutral-900/80 backdrop-blur-md border-b border-neutral-800 z-50" x-data="{ scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 10">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-cash-register text-black text-xl"></i>
                    </div>
                    <span class="text-xl font-bold">Kasir<span class="text-neutral-400">App</span></span>
                </div>
                
                <!-- Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-white hover:text-neutral-300 transition-colors font-medium">Home</a>
                    <a href="#products" class="text-neutral-400 hover:text-white transition-colors">Produk</a>
                    <a href="#about" class="text-neutral-400 hover:text-white transition-colors">Tentang</a>
                    <a href="#contact" class="text-neutral-400 hover:text-white transition-colors">Kontak</a>
                </div>
                
                <!-- Login Button -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-all duration-200 hover:scale-105">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-6">
        <div class="container mx-auto">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                <!-- Left Content -->
                <div class="flex-1 text-center lg:text-left">
                    <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Solusi Modern<br>
                        <span class="text-neutral-400">Untuk Bisnis Anda</span>
                    </h1>
                    <p class="text-xl text-neutral-400 mb-8 max-w-2xl">
                        Sistem kasir yang efisien dan mudah digunakan dengan fitur lengkap untuk mengelola bisnis retail Anda.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#products" class="px-8 py-4 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-all duration-200 hover:scale-105">
                            Lihat Produk
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-neutral-800 text-white rounded-lg font-semibold hover:bg-neutral-700 transition-all duration-200 border border-neutral-700">
                            Mulai Sekarang
                        </a>
                    </div>
                </div>
                
                <!-- Right Content - Image/Illustration -->
                <div class="flex-1 relative">
                    <div class="relative w-full h-96 bg-gradient-to-br from-neutral-800 to-neutral-900 rounded-2xl flex items-center justify-center border border-neutral-700">
                        <i class="fas fa-cash-register text-9xl text-neutral-700"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="py-16 px-6 border-y border-neutral-800">
        <div class="container mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <h3 class="text-4xl font-bold mb-2">500+</h3>
                    <p class="text-neutral-400">Produk Tersedia</p>
                </div>
                <div class="text-center">
                    <h3 class="text-4xl font-bold mb-2">1000+</h3>
                    <p class="text-neutral-400">Transaksi/Bulan</p>
                </div>
                <div class="text-center">
                    <h3 class="text-4xl font-bold mb-2">50+</h3>
                    <p class="text-neutral-400">Member Aktif</p>
                </div>
                <div class="text-center">
                    <h3 class="text-4xl font-bold mb-2">24/7</h3>
                    <p class="text-neutral-400">Support</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Products Section -->
    <section id="products" class="py-20 px-6">
        <div class="container mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Produk Populer</h2>
                <p class="text-neutral-400 text-lg">Pilihan produk terbaik dan terfavorit</p>
            </div>
            
            <!-- Filter & Search -->
            <div class="flex flex-col md:flex-row gap-4 mb-8">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                    <input type="text" placeholder="Cari produk..." 
                           class="w-full bg-neutral-900 border border-neutral-800 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:border-neutral-600 transition-colors">
                </div>
                <select class="bg-neutral-900 border border-neutral-800 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-600 transition-colors">
                    <option>Semua Kategori</option>
                    <option>Elektronik</option>
                    <option>Fashion</option>
                    <option>Makanan</option>
                    <option>Minuman</option>
                </select>
            </div>
            
            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @for($i = 1; $i <= 8; $i++)
                <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden hover:border-neutral-600 transition-all duration-300 hover:shadow-xl hover:scale-105">
                    <!-- Product Image -->
                    <div class="relative h-48 bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-box text-6xl text-neutral-700"></i>
                        <span class="absolute top-3 right-3 px-3 py-1 bg-white text-black text-xs font-semibold rounded-full">
                            Populer
                        </span>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Produk {{ $i }}</h3>
                        <p class="text-sm text-neutral-400 mb-3">Kategori Produk</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold">Rp 100.000</span>
                            <span class="text-sm text-neutral-400">Stok: 50</span>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            
            <!-- View More -->
            <div class="text-center mt-12">
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-neutral-800 text-white rounded-lg font-semibold hover:bg-neutral-700 transition-all duration-200 border border-neutral-700">
                    Login untuk Melihat Semua Produk
                </a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="py-20 px-6 bg-neutral-900/50">
        <div class="container mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Fitur Unggulan</h2>
                <p class="text-neutral-400 text-lg">Sistem yang dirancang untuk kemudahan bisnis Anda</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-8 text-center hover:border-neutral-600 transition-all">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tachometer-alt text-black text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Dashboard Lengkap</h3>
                    <p class="text-neutral-400">Monitoring real-time untuk semua aktivitas bisnis Anda</p>
                </div>
                
                <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-8 text-center hover:border-neutral-600 transition-all">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-black text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Multi Role</h3>
                    <p class="text-neutral-400">Akses berbeda untuk Admin, Kasir, dan Member</p>
                </div>
                
                <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-8 text-center hover:border-neutral-600 transition-all">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-black text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Laporan Detail</h3>
                    <p class="text-neutral-400">Export laporan ke Excel dan PDF dengan mudah</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="py-12 px-6 border-t border-neutral-800">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center space-x-3 mb-4 md:mb-0">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-cash-register text-black text-xl"></i>
                    </div>
                    <span class="text-xl font-bold">Kasir<span class="text-neutral-400">App</span></span>
                </div>
                
                <div class="text-neutral-400 text-sm">
                    © 2025 KasirApp. All rights reserved.
                </div>
                
                <div class="flex items-center space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-neutral-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-neutral-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-neutral-400 hover:text-white transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
@endsection
