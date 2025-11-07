@extends('layouts.dashboard')

@section('title', 'Member - Kasir')

@section('page-title', 'Manajemen Member')
@section('page-subtitle', 'Daftarkan dan kelola member')

@section('page-content')
<div class="space-y-6" x-data="{
    showModal: false,
    name: '', email: '', password: '', phone: '', address: '', tier_id: {{ $tiers->first()->id ?? 1 }},
    openAdd() {
        this.name=''; this.email=''; this.password=''; this.phone=''; this.address=''; this.tier_id={{ $tiers->first()->id ?? 1 }};
        this.showModal = true;
    }
}">
    <x-alerts />

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <button @click="openAdd()"
                    class="px-4 py-2.5 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-all duration-200 flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>Tambah Member</span>
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
        <form method="GET" class="relative">
            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari member..."
                   class="w-full bg-neutral-800 border border-neutral-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:border-neutral-500 transition-colors">
        </form>
    </div>

    <!-- Members Table -->
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">No. Telepon</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tier</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Poin</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tgl Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800">
                    @forelse($members as $member)
                        <tr class="hover:bg-neutral-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $member->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-neutral-400">{{ $member->email }}</td>
                            <td class="px-6 py-4 text-neutral-400">{{ $member->phone ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($member->tier && $member->tier->code === 'bronze') bg-orange-500/10 text-orange-500
                                    @elseif($member->tier && $member->tier->code === 'silver') bg-gray-500/10 text-gray-400
                                    @elseif($member->tier && $member->tier->code === 'gold') bg-yellow-500/10 text-yellow-500
                                    @else bg-neutral-700 text-neutral-400
                                    @endif">
                                    {{ $member->tier ? strtoupper($member->tier->code) : 'NONE' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-neutral-400">{{ number_format($member->points ?? 0) }}</td>
                            <td class="px-6 py-4 text-neutral-400">{{ $member->created_at?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-neutral-500">
                                <i class="fas fa-users text-4xl mb-4 block"></i>
                                Belum ada member terdaftar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="px-6 py-4 border-t border-neutral-800">
                {{ $members->links() }}
            </div>
        @endif
    </div>

    <!-- Add Member Modal -->
    <div x-show="showModal"
         x-cloak
         @click.self="showModal = false"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-neutral-800">
                <h3 class="text-xl font-bold">Tambah Member Baru</h3>
                <button @click="showModal = false" class="text-neutral-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form method="POST" action="{{ route('kasir.members.store') }}" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Lengkap *</label>
                        <input type="text" name="name" x-model="name" required
                               class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Email *</label>
                        <input type="email" name="email" x-model="email" required
                               class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Password *</label>
                        <input type="password" name="password" x-model="password" required
                               class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium mb-2">No. Telepon</label>
                        <input type="text" name="phone" x-model="phone"
                               class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                    </div>

                    <!-- Tier -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Tier Member *</label>
                        <select name="member_tier_id" x-model="tier_id" required
                                class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                            @foreach($tiers as $tier)
                                <option value="{{ $tier->id }}">{{ strtoupper($tier->code) }} - {{ $tier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Alamat</label>
                        <textarea name="address" x-model="address" rows="3"
                                  class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-neutral-800">
                    <button type="button" @click="showModal = false"
                            class="px-6 py-2.5 bg-neutral-800 hover:bg-neutral-700 rounded-lg font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 bg-white text-black hover:bg-neutral-200 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
