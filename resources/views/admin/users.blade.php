@extends('layouts.dashboard')

@section('title', 'Manajemen User - Admin')

@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola admin, kasir, dan member')

@section('page-content')
<div class="space-y-6" x-data="{
    activeTab: '{{ $activeTab ?? 'users' }}',
    showModal: false,
    modalMode: 'add',
    userType: 'kasir',
    showTierModal: false,
    editingTier: 'bronze',
    // form state
    storeUrl: '{{ route('admin.users.store') }}',
    updateUrlTemplate: '{{ url('/admin/users/__ID__') }}',
    formAction: '{{ route('admin.users.store') }}',
    editId: null,
    formName: '',
    formEmail: '',
    formPassword: '',
    member_tier_id: null,
    statusChecked: true,
    openAdd() {
        this.modalMode = 'add';
        this.formAction = this.storeUrl;
        this.editId = null;
        this.formName = '';
        this.formEmail = '';
        this.formPassword = '';
        this.userType = 'admin';
        this.member_tier_id = null;
        this.statusChecked = true;
        this.showModal = true;
    },
    openEdit(u) {
        this.modalMode = 'edit';
        this.editId = u.id;
        this.formAction = this.updateUrlTemplate.replace('__ID__', u.id);
        this.formName = u.name ?? '';
        this.formEmail = u.email ?? '';
        this.formPassword = '';
        this.userType = u.role ?? 'member';
        this.member_tier_id = u.member_tier_id ?? null;
        this.statusChecked = (u.status ?? 'active') === 'active';
        this.showModal = true;
    }
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
        @if(session('status'))
            <div class="bg-green-500/10 border border-green-600 text-green-400 px-4 py-3 rounded-lg">{{ session('status') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-600 text-red-400 px-4 py-3 rounded-lg">{{ session('error') }}</div>
        @endif
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-shield text-purple-500 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold">{{ $adminCount ?? 0 }}</span>
                </div>
                <h3 class="font-semibold mb-1">Admin</h3>
                <p class="text-sm text-neutral-400">Full Access</p>
            </div>

            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cash-register text-blue-500 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold">{{ $kasirCount ?? 0 }}</span>
                </div>
                <h3 class="font-semibold mb-1">Kasir</h3>
                <p class="text-sm text-neutral-400">POS Access</p>
            </div>

            <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-green-500 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold">{{ $memberCount ?? 0 }}</span>
                </div>
                <h3 class="font-semibold mb-1">Member</h3>
                <p class="text-sm text-neutral-400">Customer</p>
            </div>
        </div>

        <!-- Filter & Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <button @click="openAdd()"
                        class="px-4 py-2.5 bg-white text-black rounded-lg font-semibold hover:bg-neutral-200 transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah User</span>
                </button>
            </div>

            <form method="GET" action="{{ route('admin.users') }}" class="flex items-center space-x-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-neutral-500"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari user..."
                           class="bg-neutral-900 border border-neutral-800 rounded-lg pl-12 pr-4 py-2.5 focus:outline-none focus:border-neutral-600 transition-colors w-64">
                </div>
                <select name="role" class="bg-neutral-900 border border-neutral-800 rounded-lg px-4 py-2.5 focus:outline-none focus:border-neutral-600 transition-colors">
                    <option value="">Semua Role</option>
                    <option value="admin" @selected(request('role')==='admin')>Admin</option>
                    <option value="kasir" @selected(request('role')==='kasir')>Kasir</option>
                    <option value="member" @selected(request('role')==='member')>Member</option>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-neutral-800 rounded-lg font-medium hover:bg-neutral-700 transition-colors">Filter</button>
            </form>
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
                        @forelse($users as $user)
                        <tr class="hover:bg-neutral-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $user->name }}</p>
                                        <p class="text-xs text-neutral-400">ID: USR-{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $roleColor = [
                                        'admin' => 'bg-purple-500/10 text-purple-500',
                                        'kasir' => 'bg-blue-500/10 text-blue-500',
                                        'member' => 'bg-green-500/10 text-green-500',
                                    ][$user->role] ?? 'bg-neutral-700 text-neutral-300';
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $roleColor }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->status === 'active' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                                    {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-400">{{ optional($user->created_at)->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button"
                                            data-user="{{ htmlspecialchars(json_encode(['id'=>$user->id,'name'=>$user->name,'email'=>$user->email,'role'=>$user->role,'status'=>$user->status,'member_tier_id'=>$user->member_tier_id]), ENT_QUOTES, 'UTF-8') }}"
                                            @click="openEdit(JSON.parse($event.target.closest('button').getAttribute('data-user')))"
                                            class="p-2 hover:bg-neutral-700 rounded-lg transition-colors"
                                            title="Edit">
                                        <i class="fas fa-edit text-blue-500"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDelete(this, 'user')"
                                                class="p-2 hover:bg-neutral-700 rounded-lg transition-colors"
                                                title="Hapus">
                                            <i class="fas fa-trash text-red-500"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-neutral-400">Tidak ada user ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($users) && $users instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="px-6 py-4 border-t border-neutral-800">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Member Tiers Tab -->
    <div x-show="activeTab === 'tiers'" class="space-y-6">
        <!-- Tier Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(($tiers ?? []) as $tier)
            <div class="border-2 rounded-xl p-8 transition-all" style="border-color: {{ $tier->color ?? '#525252' }};">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: {{ ($tier->color ?? '#a3a3a3') }}22;">
                        <i class="fas fa-medal text-4xl" style="color: {{ $tier->color ?? '#a3a3a3' }};"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">{{ $tier->name }}</h3>
                    <p class="text-neutral-400">{{ $tier->description ?? ('Tier ' . $tier->name) }}</p>
                </div>
                <div class="space-y-4 mb-6">
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-800">
                        <span class="text-sm text-neutral-400">Member</span>
                        <span class="font-semibold">{{ $tier->members_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-800">
                        <span class="text-sm text-neutral-400">Min. Pembelian</span>
                        <span class="font-semibold">Rp {{ number_format((int) ($tier->min_total ?? 0), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-400">Diskon</span>
                        <span class="font-semibold" style="color: {{ $tier->color ?? '#a3a3a3' }};">{{ (float) ($tier->discount_percent ?? 0) }}%</span>
                    </div>
                </div>
                <button @click="showTierModal = true; editingTier = '{{ strtolower($tier->name) }}'" class="w-full px-4 py-3 border rounded-lg font-semibold transition-colors" style="border-color: {{ $tier->color ?? '#a3a3a3' }}; color: {{ $tier->color ?? '#a3a3a3' }}; background-color: {{ ($tier->color ?? '#a3a3a3') }}1A;">
                    Edit Tier
                </button>
            </div>
            @endforeach
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

            <form class="p-6 space-y-4" method="POST" :action="formAction">
                @csrf
                <template x-if="modalMode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                    <input type="text" name="name" x-model="formName" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="Masukkan nama lengkap" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" x-model="formEmail" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="email@example.com" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" x-model="formPassword" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500" placeholder="••••••••" :required="modalMode === 'add'">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Role</label>
                    <select x-model="userType" name="role" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                        <option value="member">Member</option>
                    </select>
                </div>

                <div x-show="userType === 'member'">
                    <label class="block text-sm font-medium mb-2">Tier Member</label>
                    <select name="member_tier_id" x-model="member_tier_id" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 focus:outline-none focus:border-neutral-500">
                        @foreach(($tiers ?? []) as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="status" value="active" :checked="statusChecked" class="w-5 h-5 bg-neutral-800 border-neutral-700 rounded">
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

<script>
function confirmDelete(button, type = 'user') {
    const form = button.closest('form');
    const messages = {
        user: 'Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.',
        tier: 'Apakah Anda yakin ingin menghapus tier ini? Tindakan ini tidak dapat dibatalkan.'
    };

    window.dispatchEvent(new CustomEvent('confirm-dialog', {
        detail: {
            message: messages[type] || messages.user,
            confirmText: 'Ya, Hapus',
            cancelText: 'Batal',
            callback: () => form.submit()
        }
    }));
}
</script>

@endsection
