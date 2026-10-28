@props([
    'title' => 'Ezer Home — Accueil, Écoute & Accompagnement',
    'active' => '',
    'description' => 'EZER HOME accueille, écoute et accompagne toute personne en quête de sens, de repos ou de repères, sur place à Lomé ou en ligne.',
    'showDon' => true,
    'sobreFooter' => false,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }}</title>

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-white text-ezer-dark font-sans text-base leading-normal antialiased">
    
    <!-- En-tête partagé -->
    <x-header :active="$active" :show-don="$showDon" />

    <!-- Contenu Principal -->
    <main class="flex-1 flex flex-col">
        {{ $slot }}
    </main>

    <!-- Pied de page partagé -->
    <x-footer :sobre="$sobreFooter" />

    @livewireScripts
    @stack('scripts')
</body>
</html>

