@props(['type' => 'success'])

@php
    $types = [
        'success' => [
            'bg' => 'bg-green-500/10',
            'border' => 'border-green-600',
            'text' => 'text-green-400',
            'icon' => 'fas fa-check-circle',
            'iconBg' => 'bg-green-500/20',
        ],
        'error' => [
            'bg' => 'bg-red-500/10',
            'border' => 'border-red-600',
            'text' => 'text-red-400',
            'icon' => 'fas fa-exclamation-circle',
            'iconBg' => 'bg-red-500/20',
        ],
        'warning' => [
            'bg' => 'bg-yellow-500/10',
            'border' => 'border-yellow-600',
            'text' => 'text-yellow-400',
            'icon' => 'fas fa-exclamation-triangle',
            'iconBg' => 'bg-yellow-500/20',
        ],
        'info' => [
            'bg' => 'bg-blue-500/10',
            'border' => 'border-blue-600',
            'text' => 'text-blue-400',
            'icon' => 'fas fa-info-circle',
            'iconBg' => 'bg-blue-500/20',
        ],
    ];

    $config = $types[$type] ?? $types['success'];
@endphp

<div x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     x-init="setTimeout(() => show = false, 5000)"
     {{ $attributes->merge(['class' => "relative {$config['bg']} border {$config['border']} rounded-lg overflow-hidden"]) }}>

    <div class="flex items-start p-4 gap-3">
        <!-- Icon -->
        <div class="flex-shrink-0 {{ $config['iconBg'] }} w-10 h-10 rounded-lg flex items-center justify-center">
            <i class="{{ $config['icon'] }} {{ $config['text'] }}"></i>
        </div>

        <!-- Content -->
        <div class="flex-1 pt-1">
            <p class="{{ $config['text'] }} font-medium">
                {{ $slot }}
            </p>
        </div>

        <!-- Close Button -->
        <button @click="show = false"
                class="{{ $config['text'] }} hover:opacity-75 transition-opacity flex-shrink-0">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Progress Bar -->
    <div class="h-1 {{ $config['bg'] }} relative overflow-hidden">
        <div class="{{ str_replace('/10', '', $config['bg']) }} h-full"
             x-data="{ width: '100%' }"
             x-init="setTimeout(() => width = '0%', 100)"
             :style="`width: ${width}; transition: width 5s linear;`">
        </div>
    </div>
</div>
