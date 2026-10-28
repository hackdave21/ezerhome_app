@props([
    'variant' => 'primary', // 'primary' | 'secondary' | 'outline' | 'danger'
    'size' => 'md', // 'sm' | 'md' | 'lg'
    'href' => null,
    'type' => 'button'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-ezer transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';

    $sizeClasses = match($size) {
        'sm' => 'min-h-[36px] px-3 text-xs gap-1.5',
        'lg' => 'min-h-[52px] px-6 text-lg gap-2.5',
        default => 'min-h-[46px] px-4 text-[15px] gap-2',
    };

    $variantClasses = match($variant) {
        'secondary' => 'border-2 border-ezer-green text-ezer-green hover:bg-ezer-green-light focus:ring-ezer-green',
        'outline' => 'border border-ezer-borderMuted text-ezer-dark hover:bg-gray-50 focus:ring-ezer-green',
        'danger' => 'bg-ezer-red text-white hover:opacity-90 focus:ring-ezer-red',
        default => 'bg-ezer-green text-white hover:bg-ezer-green-dark focus:ring-ezer-green',
    };

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

