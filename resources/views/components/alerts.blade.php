<div class="space-y-3">
    {{-- Success Message --}}
    @if(session('status') || session('success'))
        <x-alert type="success">
            {{ session('status') ?? session('success') }}
        </x-alert>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <x-alert type="error">
            {{ session('error') }}
        </x-alert>
    @endif

    {{-- Warning Message --}}
    @if(session('warning'))
        <x-alert type="warning">
            {{ session('warning') }}
        </x-alert>
    @endif

    {{-- Info Message --}}
    @if(session('info'))
        <x-alert type="info">
            {{ session('info') }}
        </x-alert>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        @foreach($errors->all() as $error)
            <x-alert type="error">
                {{ $error }}
            </x-alert>
        @endforeach
    @endif
</div>
