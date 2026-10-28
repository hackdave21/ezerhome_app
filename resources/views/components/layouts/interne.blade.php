@props([
    'title' => 'Espace interne — Ezer Home',
    'espace' => 'admin',
    'actif' => '',
])

@php
    $config = \App\Support\InternalNav::space($espace);
    $user = auth()->user();
    $prenom = \Illuminate\Support\Str::of($user->name)->before(' ')->toString();
    $initiales = mb_strtoupper(mb_substr($prenom, 0, 2));
    $role = $user->getRoleNames()->first();
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
    <style>.chip-actif{background:#187d50;color:#fff;border:2px solid #187d50}.chip-inactif{background:#fff;color:#0e1c03;border:1px solid #6f8577}</style>
    @stack('styles')
</head>
<body class="bg-white text-ezer-dark font-sans text-base leading-normal min-h-screen flex flex-col antialiased">

    <header class="bg-white border-b border-ezer-green-borderLight">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-3 flex items-center justify-between gap-4">
            <a href="{{ $config['accueil'] }}" class="flex flex-col leading-none">
                <span class="font-cooper text-xl text-ezer-green">Ezer Home</span>
                <span class="text-xs uppercase tracking-wider text-ezer-muted mt-1">{{ $config['titre'] }}</span>
            </a>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-2.5">
                    <span aria-hidden="true" class="w-9 h-9 rounded-full bg-ezer-green text-white text-[13px] font-semibold inline-flex items-center justify-center">{{ $initiales }}</span>
                    <span class="hidden lg:inline font-semibold text-sm">{{ $prenom }}@if ($role)<span class="text-ezer-muted font-normal"> · {{ str_replace('_', ' ', $role) }}</span>@endif</span>
                    <span class="sr-only">Mon compte : {{ $prenom }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" aria-label="Déconnexion" class="inline-flex items-center gap-2 font-semibold text-sm text-ezer-dark hover:text-ezer-green bg-transparent border-0 cursor-pointer">
                        <svg aria-hidden="true" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><path d="m16 17 5-5-5-5"></path><path d="M21 12H9"></path></svg>
                        <span class="hidden lg:inline">Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-[1400px] mx-auto w-full px-6 lg:px-12 pb-12 lg:pb-16 flex flex-col lg:flex-row lg:items-start lg:gap-10 flex-1">
        <nav aria-label="{{ $config['titre'] }}" aria-orientation="horizontal"
             class="w-full lg:w-[220px] lg:shrink-0 -mx-4 px-4 lg:px-0 lg:py-6 flex lg:flex-col lg:gap-0.5 overflow-x-auto lg:overflow-visible border-b lg:border-0 border-ezer-green-border bg-white sticky top-0 lg:top-4 z-40">
            @foreach ($config['nav'] as $item)
                @if (isset($item['titre']))
                    <span aria-hidden="true" class="hidden lg:block px-4 pt-4 pb-1 text-xs font-semibold uppercase tracking-wider text-ezer-muted">{{ $item['titre'] }}</span>
                @else
                    @php $on = $item['id'] === $actif; @endphp
                    <a href="{{ $item['url'] }}" @if ($on) aria-current="page" @endif
                       class="flex-1 lg:flex-none flex items-center justify-center lg:justify-between gap-2 px-4 min-h-[48px] font-semibold text-[15px] lg:rounded-ezer {{ $on ? 'text-ezer-green bg-ezer-green-light shadow-ezer-nav lg:shadow-[inset_3px_0_0_#187d50]' : 'text-ezer-dark hover:bg-ezer-green-pale' }}">
                        <span class="inline-flex items-center">{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="flex-1 min-w-0 flex flex-col gap-5 pt-6 lg:pt-8">
            {{ $slot }}
        </div>
    </main>

    <footer class="border-t border-ezer-green-borderLight bg-white px-4 py-5 text-center text-[13px] text-ezer-muted">
        Espace confidentiel · Ezer Home · Les données des demandeurs ne doivent jamais sortir de la plateforme.
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
