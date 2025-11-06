<!-- Sidebar Component -->
<aside 
    :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:static inset-y-0 left-0 z-40 bg-neutral-900 border-r border-neutral-800 transition-all duration-300 ease-in-out"
    :style="sidebarOpen ? 'width: 16rem' : 'width: 5rem'"
    @click.away="if (window.innerWidth < 1024) mobileSidebarOpen = false">
    
    <!-- Logo -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-neutral-800">
        <div class="flex items-center space-x-3" x-show="sidebarOpen" x-transition>
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-cash-register text-black text-xl"></i>
            </div>
            <span class="text-xl font-bold">Kasir<span class="text-neutral-400">App</span></span>
        </div>
        <div x-show="!sidebarOpen" class="hidden lg:flex items-center justify-center w-full" x-transition>
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-cash-register text-black text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @php
            $role = Auth::user()->role ?? 'admin';
            
            $menus = [
                'admin' => [
                    ['icon' => 'fa-home', 'label' => 'Dashboard', 'route' => 'admin.dashboard'],
                    ['icon' => 'fa-box', 'label' => 'Produk', 'route' => 'admin.products'],
                    ['icon' => 'fa-users', 'label' => 'User & Tier Member', 'route' => 'admin.users', 'routes' => 'admin.tiers'],
                    ['icon' => 'fa-file-alt', 'label' => 'Laporan', 'route' => 'admin.reports'],
                ],
                'kasir' => [
                    ['icon' => 'fa-home', 'label' => 'Dashboard', 'route' => 'kasir.dashboard'],
                    ['icon' => 'fa-shopping-cart', 'label' => 'Point of Sale', 'route' => 'kasir.pos'],
                    ['icon' => 'fa-user-plus', 'label' => 'Tambah Member', 'route' => 'kasir.members'],
                    ['icon' => 'fa-box', 'label' => 'Produk', 'route' => 'kasir.products'],
                    ['icon' => 'fa-file-alt', 'label' => 'Laporan', 'route' => 'kasir.reports'],
                ],
                'member' => [
                    ['icon' => 'fa-home', 'label' => 'Dashboard', 'route' => 'member.dashboard'],
                    ['icon' => 'fa-history', 'label' => 'Riwayat Pembelian', 'route' => 'member.history'],
                    ['icon' => 'fa-trophy', 'label' => 'Tier & Benefit', 'route' => 'member.tier'],
                ]
            ];
            
            $currentMenu = $menus[$role] ?? $menus['admin'];
        @endphp
        
        @foreach($currentMenu as $menu)
        <a href="{{ route($menu['route']) }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs($menu['route']) || (isset($menu['routes']) && request()->routeIs($menu['routes']))
                     ? 'bg-white text-black' 
                     : 'text-neutral-400 hover:bg-neutral-800 hover:text-white' }}"
           x-tooltip="!sidebarOpen ? '{{ $menu['label'] }}' : ''">
            <i class="fas {{ $menu['icon'] }} text-lg w-5 text-center"></i>
            <span x-show="sidebarOpen" class="font-medium" x-transition>{{ $menu['label'] }}</span>
        </a>
        @endforeach
    </nav>
    
    <!-- Sidebar Footer -->
    <div class="px-4 py-4 border-t border-neutral-800">
        <a href="#settings" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-neutral-400 hover:bg-neutral-800 hover:text-white"
           x-tooltip="!sidebarOpen ? 'Pengaturan' : ''">
            <i class="fas fa-cog text-lg w-5 text-center"></i>
            <span x-show="sidebarOpen" class="font-medium" x-transition>Pengaturan</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-red-400 hover:bg-red-950 hover:text-red-300"
                x-tooltip="!sidebarOpen ? 'Logout' : ''">
                <i class="fas fa-sign-out-alt text-lg w-5 text-center"></i>
                <span x-show="sidebarOpen" class="font-medium" x-transition>Logout</span>
            </button>
        </form>
    </div>
    
    <!-- Toggle Button -->
    <button 
        @click="sidebarOpen = !sidebarOpen"
        class="hidden lg:flex absolute -right-3 top-20 w-6 h-6 bg-white rounded-full items-center justify-center shadow-lg hover:scale-110 transition-transform z-50">
        <i class="fas fa-chevron-left text-black text-xs transition-transform duration-300" :class="{ 'rotate-180': !sidebarOpen }"></i>
    </button>
</aside>
