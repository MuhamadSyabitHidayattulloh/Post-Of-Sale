{{-- Toast Notification Component --}}
<div x-data="{
    toasts: [],
    add(message, type = 'info', duration = 5000) {
        const id = Date.now();
        this.toasts.push({ id, message, type, duration });
        setTimeout(() => this.remove(id), duration);
    },
    remove(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}"
x-on:toast.window="add($event.detail.message, $event.detail.type, $event.detail.duration)"
class="fixed top-4 right-4 z-[200] space-y-3 pointer-events-none">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             class="pointer-events-auto w-80 rounded-lg shadow-2xl overflow-hidden"
             :class="{
                 'bg-green-500/10 border border-green-600': toast.type === 'success',
                 'bg-red-500/10 border border-red-600': toast.type === 'error',
                 'bg-yellow-500/10 border border-yellow-600': toast.type === 'warning',
                 'bg-blue-500/10 border border-blue-600': toast.type === 'info',
             }">

            <div class="flex items-start p-4 gap-3">
                <!-- Icon -->
                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                     :class="{
                         'bg-green-500/20 text-green-400': toast.type === 'success',
                         'bg-red-500/20 text-red-400': toast.type === 'error',
                         'bg-yellow-500/20 text-yellow-400': toast.type === 'warning',
                         'bg-blue-500/20 text-blue-400': toast.type === 'info',
                     }">
                    <i :class="{
                        'fas fa-check-circle': toast.type === 'success',
                        'fas fa-exclamation-circle': toast.type === 'error',
                        'fas fa-exclamation-triangle': toast.type === 'warning',
                        'fas fa-info-circle': toast.type === 'info',
                    }"></i>
                </div>

                <!-- Content -->
                <div class="flex-1 pt-1">
                    <p class="font-medium"
                       :class="{
                           'text-green-400': toast.type === 'success',
                           'text-red-400': toast.type === 'error',
                           'text-yellow-400': toast.type === 'warning',
                           'text-blue-400': toast.type === 'info',
                       }"
                       x-text="toast.message"></p>
                </div>

                <!-- Close Button -->
                <button @click="remove(toast.id)"
                        class="flex-shrink-0 hover:opacity-75 transition-opacity"
                        :class="{
                            'text-green-400': toast.type === 'success',
                            'text-red-400': toast.type === 'error',
                            'text-yellow-400': toast.type === 'warning',
                            'text-blue-400': toast.type === 'info',
                        }">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="h-1 relative overflow-hidden"
                 :class="{
                     'bg-green-500/10': toast.type === 'success',
                     'bg-red-500/10': toast.type === 'error',
                     'bg-yellow-500/10': toast.type === 'warning',
                     'bg-blue-500/10': toast.type === 'info',
                 }">
                <div class="h-full"
                     :class="{
                         'bg-green-500': toast.type === 'success',
                         'bg-red-500': toast.type === 'error',
                         'bg-yellow-500': toast.type === 'warning',
                         'bg-blue-500': toast.type === 'info',
                     }"
                     x-data="{ width: '100%' }"
                     x-init="setTimeout(() => width = '0%', 100)"
                     :style="`width: ${width}; transition: width ${toast.duration}ms linear;`">
                </div>
            </div>
        </div>
    </template>
</div>

<script>
// Global helper function for toast
window.showToast = function(message, type = 'info', duration = 5000) {
    window.dispatchEvent(new CustomEvent('toast', {
        detail: { message, type, duration }
    }));
};
</script>
