<!-- Card Component -->
@php
    $bgColor = $bgColor ?? 'bg-neutral-900';
    $borderColor = $borderColor ?? 'border-neutral-800';
    $padding = $padding ?? 'p-6';
@endphp

<div class="{{ $bgColor }} {{ $borderColor }} border rounded-xl {{ $padding }} {{ $class ?? '' }}">
    @if(isset($title))
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">{{ $title }}</h3>
        @if(isset($action))
        <div>{{ $action }}</div>
        @endif
    </div>
    @endif
    
    {{ $slot }}
</div>
