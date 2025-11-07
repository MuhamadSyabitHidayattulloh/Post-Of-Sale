{{-- Custom Confirmation Modal Component --}}
@props(['message' => 'Apakah Anda yakin?', 'confirmText' => 'Ya, Hapus', 'cancelText' => 'Batal'])

<div x-data="{
    show: false,
    resolve: null,
    message: '{{ $message }}',
    confirmText: '{{ $confirmText }}',
    cancelText: '{{ $cancelText }}',
    confirm() {
        return new Promise((res) => {
            this.resolve = res;
            this.show = true;
        });
    },
    handleConfirm() {
        this.show = false;
        if (this.resolve) this.resolve(true);
    },
    handleCancel() {
        this.show = false;
        if (this.resolve) this.resolve(false);
    }
}"
x-on:confirm-dialog.window="message = $event.detail.message || message; confirmText = $event.detail.confirmText || confirmText; cancelText = $event.detail.cancelText || cancelText; confirm().then(result => { if(result && $event.detail.callback) $event.detail.callback(); })">

    <!-- Modal Overlay -->
    <div x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/70 z-[100] flex items-center justify-center p-4"
         style="display: none;"
         @click.self="handleCancel()">

        <!-- Modal Content -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-neutral-900 border border-neutral-800 rounded-xl w-full max-w-md overflow-hidden shadow-2xl">

            <!-- Icon -->
            <div class="p-6 pb-4 text-center">
                <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>

                <!-- Message -->
                <h3 class="text-xl font-bold mb-2">Konfirmasi</h3>
                <p class="text-neutral-400" x-text="message"></p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 p-6 pt-4 bg-neutral-800/50">
                <button @click="handleCancel()"
                        class="flex-1 px-4 py-2.5 bg-neutral-800 text-white rounded-lg font-medium hover:bg-neutral-700 transition-colors border border-neutral-700">
                    <span x-text="cancelText"></span>
                </button>
                <button @click="handleConfirm()"
                        class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">
                    <span x-text="confirmText"></span>
                </button>
            </div>
        </div>
    </div>
</div>
