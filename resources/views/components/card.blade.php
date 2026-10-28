@props([
    'title' => null,
    'badge' => null,
    'badgeType' => null,
    'href' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-ezer border border-ezer-green-border p-6 lg:p-8 flex flex-col justify-between shadow-sm transition-all hover:border-ezer-green']) }}>
    <div>
        @if($badge || $title)
            <div class="flex items-center justify-between gap-4 mb-4">
                @if($title)
                    <h3 class="font-semibold text-lg lg:text-xl text-ezer-dark">{{ $title }}</h3>
                @endif
                @if($badge)
                    <x-badge :type="$badgeType">{{ $badge }}</x-badge>
                @endif
            </div>
        @endif

        <div class="text-ezer-muted text-sm lg:text-base leading-relaxed">
            {{ $slot }}
        </div>
    </div>

    @if(isset($footer))
        <div class="mt-6 pt-4 border-t border-ezer-green-borderLight flex items-center justify-between">
            {{ $footer }}
        </div>
    @endif
</div>

