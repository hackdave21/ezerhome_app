@props([
    'title' => 'Connexion — Ezer Home',
])

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
<body class="bg-white lg:bg-ezer-green-light text-ezer-dark font-sans text-base leading-normal min-h-screen flex flex-col antialiased">

    <x-header />

    <main class="flex-1">
        <div class="px-4 py-7 lg:px-8 lg:py-16 lg:pb-22">
            <div class="max-w-[520px] mx-auto lg:p-10 lg:border lg:border-ezer-green-border rounded-ezer bg-white flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </main>

    <x-mini-footer />

    @livewireScripts
    @stack('scripts')
</body>
</html>
