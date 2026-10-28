<x-layouts.public title="La charte d'écoute — Ezer Home" active="ecoute">
    <section class="bg-ezer-green-light">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
            <nav aria-label="Fil d'Ariane" class="text-sm">
                <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                    <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ url('/ecoute') }}" class="text-ezer-green hover:underline">Écoute</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Charte d'écoute</li>
                </ol>
            </nav>
            <h1 class="font-cooper font-normal text-3xl lg:text-5xl">La charte d'écoute</h1>
            <p class="text-sm text-ezer-muted">Version {{ $charter->version }} · en vigueur depuis le {{ $charter->published_at->locale('fr')->translatedFormat('j F Y') }}</p>
        </div>
    </section>

    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-5 max-w-[820px]">
        {!! $charter->body !!}
        <p><a href="{{ route('espace.charte') }}" class="font-semibold text-ezer-green hover:underline">Signer la charte depuis mon espace</a></p>
    </div>
</x-layouts.public>
