@props([
    'title' => 'Mon Espace — Ezer Home',
    'activeTab' => 'profil'
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
<body class="min-h-full flex flex-col font-sans text-ezer-dark antialiased">
    
    <!-- Barre navigation haute -->
    <header class="bg-white border-b border-ezer-green-borderLight sticky top-0 z-40">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-3 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="block shrink-0">
                    <img src="{{ asset('assets/images/Logo_Ezer-01.png') }}" alt="Logo Ezer Home" class="h-12 w-auto object-contain">
                </a>
                <span class="hidden sm:inline-block font-semibold text-sm px-2.5 py-1 rounded bg-ezer-green-light text-ezer-green">
                    Espace Membre
                </span>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="text-sm font-medium text-ezer-muted hover:text-ezer-green">
                    Site public
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-ezer-red hover:underline">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Corps de la page avec barre latérale / contenu -->
    <div class="flex-1 max-w-[1400px] w-full mx-auto px-6 lg:px-12 py-8 flex flex-col md:flex-row gap-8">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 shrink-0 flex flex-col gap-2">
            <div class="bg-white p-4 rounded-ezer border border-ezer-green-borderLight flex flex-col gap-1">
                <a href="{{ url('/espace/profil') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ $activeTab === 'profil' ? 'bg-ezer-green-light text-ezer-green' : 'text-ezer-dark hover:bg-gray-50' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                    Mon Profil
                </a>
                <a href="{{ url('/espace/ecoute') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ $activeTab === 'ecoute' ? 'bg-ezer-green-light text-ezer-green' : 'text-ezer-dark hover:bg-gray-50' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20.5l1.4-4.8A8 8 0 1 1 21 12z"></path></svg>
                    Mes demandes d'écoute
                </a>
                <a href="{{ url('/espace/formations') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ $activeTab === 'formations' ? 'bg-ezer-green-light text-ezer-green' : 'text-ezer-dark hover:bg-gray-50' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path></svg>
                    Mes formations
                </a>
                <a href="{{ url('/espace/coaching') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ $activeTab === 'coaching' ? 'bg-ezer-green-light text-ezer-green' : 'text-ezer-dark hover:bg-gray-50' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                    Mon suivi coaching
                </a>
            </div>
        </aside>

        <!-- Contenu -->
        <main class="flex-1 bg-white p-6 lg:p-8 rounded-ezer border border-ezer-green-borderLight shadow-sm">
            {{ $slot }}
        </main>
    </div>

    <!-- Footer sobre -->
    <x-footer :sobre="true" />

    @livewireScripts
    @stack('scripts')
</body>
</html>

