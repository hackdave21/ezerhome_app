@props([
    'title' => 'Ezer Home — Accueil, Écoute & Accompagnement',
    'active' => '',
    'description' => 'EZER HOME accueille, écoute et accompagne toute personne en quête de sens, de repos ou de repères, sur place à Lomé ou en ligne.',
    'sobreFooter' => false,
    'image' => null,
    'type' => 'website',
    'noindex' => false,
])

@php
    $siteName = 'Ezer Home';
    $locale = str_replace('_', '-', app()->getLocale());
    $canonical = url()->current();
    $ogImage = $image ?: asset('og-image.png');

    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'NGO',
        'name' => $siteName,
        'url' => url('/'),
        'logo' => asset('assets/images/Logo_Ezer-01.png'),
        'image' => $ogImage,
        'description' => 'EZER HOME accueille, écoute et accompagne toute personne en quête de sens, de repos ou de repères, sur place à Lomé ou en ligne.',
        'telephone' => setting('contact.telephone'),
        'email' => setting('contact.email'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => setting('contact.adresse'),
            'addressLocality' => 'Lomé',
            'addressCountry' => 'TG',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description }}">
    @if ($noindex)
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow, max-image-preview:large">
    @endif
    <link rel="canonical" href="{{ $canonical }}">
    <title>{{ $title }}</title>

    <!-- Open Graph / réseaux sociaux (WhatsApp, Facebook, LinkedIn…) -->
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Ezer Home — Accueil, Écoute & Accompagnement">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Application installable (PWA) -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#187d50">
    <meta name="application-name" content="{{ $siteName }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $siteName }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icons/icon-512.png') }}">

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')

    <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
</head>
<body class="min-h-full flex flex-col bg-white text-ezer-dark font-sans text-base leading-normal antialiased">
    
    <!-- En-tête partagé -->
    <x-header :active="$active" />

    <!-- Contenu Principal -->
    <main class="flex-1 flex flex-col">
        {{ $slot }}
    </main>

    <!-- Pied de page partagé -->
    <x-footer :sobre="$sobreFooter" />

    <x-cookie-consent />
    <x-install-app />

    @livewireScripts
    @stack('scripts')
</body>
</html>
