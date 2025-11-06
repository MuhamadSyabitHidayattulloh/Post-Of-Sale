<!-- Navbar Component -->
<header class="h-16 bg-neutral-900 border-b border-neutral-800 flex items-center justify-between px-6">
    <!-- Left Section -->
    <div class="flex items-center space-x-4">
        <!-- Mobile Menu Button -->
        <button 
            @click="mobileSidebarOpen = !mobileSidebarOpen"
            class="lg:hidden p-2 rounded-lg hover:bg-neutral-800 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold">@yield('page-title', 'Dashboard')</h1>
            <p class="text-sm text-neutral-400">@yield('page-subtitle', 'Selamat datang kembali')</p>
        </div>
    </div>
    
    <!-- Right Section -->
    <div class="flex items-center space-x-4">
        <!-- Search Bar (Desktop) -->
        <div class="hidden md:flex items-center bg-neutral-800 rounded-lg px-4 py-2 w-64">
            <i class="fas fa-search text-neutral-400 mr-2"></i>
            <input 
                type="text" 
                placeholder="Cari..." 
                class="bg-transparent outline-none text-sm w-full placeholder-neutral-500">
        </div>
        
        <!-- Notifications -->
        <div x-data="{ open: false }" class="relative">
            <button 
                @click="open = !open"
                class="relative p-2 rounded-lg hover:bg-neutral-800 transition-colors">
                <i class="fas fa-bell text-xl"></i>
                @if(($navbarNotifications ?? collect())->count() > 0)
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </button>
            
            <!-- Dropdown -->
            <div 
                x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="absolute right-0 mt-2 w-80 bg-neutral-800 rounded-lg shadow-xl border border-neutral-700 overflow-hidden z-50"
                style="display: none;">
                
                <div class="p-4 border-b border-neutral-700">
                    <h3 class="font-semibold">Notifikasi</h3>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    @forelse(($navbarNotifications ?? collect()) as $n)
                    <div class="block p-4 hover:bg-neutral-700 transition-colors border-b border-neutral-700">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Transaksi {{ $n->code }}</p>
                                <p class="text-xs text-neutral-400 mt-1">Total Rp {{ number_format((int) $n->total, 0, ',', '.') }}</p>
                                <p class="text-xs text-neutral-500 mt-1">{{ $n->created_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-sm text-neutral-400">Tidak ada notifikasi.</div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- User Profile -->
        <div x-data="{ open: false }" class="relative">
            <button 
                @click="open = !open"
                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-neutral-800 transition-colors">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                    <span class="text-sm font-bold">{{ substr(Auth::user()->name ?? 'User', 0, 1) }}</span>
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-neutral-400">{{ ucfirst(Auth::user()->role ?? 'user') }}</p>
                </div>
                <i class="fas fa-chevron-down text-sm"></i>
            </button>
            
            <!-- Dropdown -->
            <div 
                x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="absolute right-0 mt-2 w-48 bg-neutral-800 rounded-lg shadow-xl border border-neutral-700 overflow-hidden z-50"
                style="display: none;">
                
                <a href="#profile" class="block px-4 py-3 hover:bg-neutral-700 transition-colors">
                    <i class="fas fa-user mr-2"></i> Profil
                </a>
                <a href="#settings" class="block px-4 py-3 hover:bg-neutral-700 transition-colors">
                    <i class="fas fa-cog mr-2"></i> Pengaturan
                </a>
                <hr class="border-neutral-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-neutral-700 transition-colors text-red-400">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
