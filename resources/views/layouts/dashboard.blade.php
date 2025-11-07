@extends('layouts.main')

@section('content')
<div class="flex h-screen overflow-hidden"
     x-data="{
         sidebarOpen: window.innerWidth >= 1024,
         mobileSidebarOpen: false,
         init() {
             // Listen to window resize
             window.addEventListener('resize', () => {
                 if (window.innerWidth >= 1024) {
                     this.mobileSidebarOpen = false;
                 }
             });
         }
     }">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-neutral-950 p-6">
            <div class="container mx-auto">
                @yield('page-content')
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileSidebarOpen"
         @click="mobileSidebarOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
         style="display: none;">
    </div>

    <!-- Global Components -->
    <x-toast />
    <x-confirm-dialog />
</div>
@endsection
