@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, success, warning
    'size' => 'md', // sm, md, lg
    'disabled' => false,
    'loading' => false,
    'icon' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg border transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $sizeClasses = match($size) {
        'sm' => 'text-xs px-3 py-1.5',
        'lg' => 'text-base px-6 py-3',
        default => 'text-sm px-4 py-2'
    };
    
    $variantClasses = match($variant) {
        'secondary' => 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-gray-500',
        'danger' => 'border-transparent bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'success' => 'border-transparent bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'warning' => 'border-transparent bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-500',
        default => 'border-transparent bg-accent text-accent-foreground hover:bg-accent/90 focus:ring-accent' // primary
    };
    
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
    $loadingClasses = $loading ? 'opacity-75 cursor-not-allowed' : '';
@endphp

<button
    type="{{ $type }}"
    {{ $disabled || $loading ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => implode(' ', [$baseClasses, $sizeClasses, $variantClasses, $disabledClasses, $loadingClasses])]) }}
>
    @if($icon && !$loading)
        <x-dynamic-component :component="$icon" class="-ml-0.5 mr-2 h-5 w-5" />
    @endif

    @if($loading)
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    
    {{ $slot }}
</button>