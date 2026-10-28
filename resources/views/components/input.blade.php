@props([
    'disabled' => false,
    'label' => null,
    'error' => null,
    'name' => '',
])

<div class="flex flex-col gap-1.5 w-full">
    @if($label)
        <label for="{{ $attributes->get('id', $name) }}" class="text-sm font-semibold text-ezer-dark">
            {{ $label }}
            @if($attributes->has('required'))
                <span class="text-ezer-red">*</span>
            @endif
        </label>
    @endif

    <input 
        name="{{ $name }}"
        id="{{ $attributes->get('id', $name) }}"
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge([
            'class' => 'w-full rounded-ezer border border-ezer-borderMuted px-4 py-2.5 text-ezer-dark focus:border-ezer-green focus:outline-none focus:ring-1 focus:ring-ezer-green text-sm disabled:bg-gray-100 disabled:cursor-not-allowed ' . ($error ? 'border-ezer-red focus:border-ezer-red focus:ring-ezer-red' : '')
        ]) !!}
    >

    @if($error)
        <p class="text-xs text-ezer-red font-medium">{{ $error }}</p>
    @endif
</div>

