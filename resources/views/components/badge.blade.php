@props([
    'variant' => 'green', // 'green' | 'red' | 'gray' | 'brown'
    'type' => null // 'gratuit' | 'tarife'
])

@php
    if ($type === 'gratuit') {
        $variant = 'green';
        $label = 'Gratuit';
    } elseif ($type === 'tarife') {
        $variant = 'brown';
        $label = 'Tarifé';
    } else {
        $label = $slot->isNotEmpty() ? $slot : '';
    }

    $classes = match($variant) {
        'green' => 'border-ezer-green bg-ezer-green-light text-ezer-green',
        'red' => 'border-ezer-red bg-ezer-red-light text-ezer-red',
        'brown' => 'border-ezer-brown bg-ezer-brown-light text-ezer-brown',
        default => 'border-ezer-borderMuted bg-gray-100 text-ezer-muted',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full border {$classes}"]) }}>
    {{ $label }}
</span>

