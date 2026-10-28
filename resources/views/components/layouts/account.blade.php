@props([
    'title' => 'Mon espace — Ezer Home',
    'activeTab' => 'profil',
])

@php
    $onglets = [
        ['id' => 'ecoute', 'label' => 'Écoute', 'url' => route('espace.onglet', 'ecoute')],
        ['id' => 'formations', 'label' => 'Formations', 'url' => route('espace.onglet', 'formations')],
        ['id' => 'coaching', 'label' => 'Coaching', 'url' => route('espace.onglet', 'coaching')],
        ['id' => 'profil', 'label' => 'Profil et confidentialité', 'url' => route('espace.profil')],
    ];
    $prenom = \Illuminate\Support\Str::of(auth()->user()->name)->before(' ');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-white text-ezer-dark font-sans text-base leading-relaxed min-h-screen flex flex-col antialiased">

    <x-header :show-don="false" />

    <section class="bg-ezer-green-light">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-5 lg:py-6 flex flex-wrap items-end justify-between gap-3">
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Mon espace</p>
                <h1 class="font-cooper font-normal text-2xl lg:text-[34px]">Bonjour, {{ $prenom }}</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline bg-transparent border-0 cursor-pointer">Se déconnecter</button>
            </form>
        </div>
    </section>

    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
        <nav role="tablist" aria-label="Rubriques de mon espace" aria-orientation="horizontal"
             class="lg:flex lg:gap-12 -mx-6 px-6 lg:-mx-12 lg:px-0 lg:pt-8 lg:pb-0 lg:flex-col overflow-x-auto border-b lg:border-0 border-ezer-green-border bg-white sticky top-0 lg:top-24 lg:bg-transparent z-40">
            @foreach ($onglets as $t)
                @php $on = $t['id'] === $activeTab; @endphp
                <a href="{{ $t['url'] }}" @if ($on) aria-current="page" @endif
                   class="flex-1 lg:flex-none flex items-center justify-center lg:justify-between gap-2 px-4 py-3.5 min-h-[52px] font-semibold text-[15px] lg:rounded-ezer {{ $on ? 'text-ezer-green bg-ezer-green-light shadow-ezer-nav lg:shadow-[inset_3px_0_0_#187d50]' : 'text-ezer-dark hover:bg-ezer-green-pale' }}">
                    <span class="inline-flex items-center">{{ $t['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-mini-footer />

    @livewireScripts
    @stack('scripts')
</body>
</html>
