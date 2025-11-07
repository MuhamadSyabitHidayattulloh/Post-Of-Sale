@extends('layouts.main')

@section('title', 'Login - Kasir App')

@section('content')
<div class="min-h-screen bg-neutral-950 flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl mb-4">
                <i class="fas fa-cash-register text-black text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold">Selamat Datang</h1>
            <p class="text-neutral-400 mt-2">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Login Form -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-8">
            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email atau Username</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                        <input
                            type="text"
                            id="email"
                            name="email"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors"
                            placeholder="Masukkan email atau username"
                            required>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Password</label>
                    <div class="relative" x-data="{ showPassword: false }">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="password"
                            name="password"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-lg pl-12 pr-12 py-3 focus:outline-none focus:border-neutral-500 transition-colors"
                            placeholder="Masukkan password"
                            required>
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-neutral-500 hover:text-neutral-300">
                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 bg-neutral-800 border-neutral-700 rounded">
                        <span class="ml-2 text-sm text-neutral-400">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-neutral-400 hover:text-white transition-colors">Lupa password?</a>
                </div>

                <!-- Role selection demo removed in favor of real authentication -->

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-white text-black rounded-lg py-3 font-semibold hover:bg-neutral-200 transition-all duration-200 hover:scale-105">
                    Masuk
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-neutral-800"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-neutral-900 text-neutral-400">Atau masuk dengan</span>
                </div>
            </div>

            <!-- Social Login -->
            <div class="grid grid-cols-2 gap-4">
                <button class="flex items-center justify-center px-4 py-3 bg-neutral-800 hover:bg-neutral-700 border border-neutral-700 rounded-lg transition-colors">
                    <i class="fab fa-google mr-2"></i>
                    Google
                </button>
                <button class="flex items-center justify-center px-4 py-3 bg-neutral-800 hover:bg-neutral-700 border border-neutral-700 rounded-lg transition-colors">
                    <i class="fab fa-facebook mr-2"></i>
                    Facebook
                </button>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Home
            </a>
        </div>

        <!-- Alerts -->
        <div class="mt-6">
            <x-alerts />
        </div>
    </div>
</div>
@endsection
