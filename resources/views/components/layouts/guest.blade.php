@props([
    'title' => 'Connexion — Ezer Home',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-ezer-green-pale">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 text-ezer-dark font-sans antialiased">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <a href="{{ url('/') }}" class="inline-block">
            <img class="h-20 w-auto mx-auto object-contain" src="{{ asset('assets/images/Logo_Ezer-01.png') }}" alt="Logo Ezer Home">
        </a>
        <h2 class="mt-4 font-cooper text-2xl font-bold tracking-tight text-ezer-dark">
            {{ $heading ?? 'Bienvenue chez Ezer Home' }}
        </h2>
        @if(isset($subheading))
            <p class="mt-2 text-sm text-ezer-muted">
                {{ $subheading }}
            </p>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 shadow-sm border border-ezer-green-border rounded-ezer sm:px-10">
            {{ $slot }}
        </div>
        <div class="mt-6 text-center text-xs text-ezer-muted">
            <a href="{{ url('/') }}" class="hover:text-ezer-green hover:underline inline-flex items-center gap-1">
                &larr; Retour au site public
            </a>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>

