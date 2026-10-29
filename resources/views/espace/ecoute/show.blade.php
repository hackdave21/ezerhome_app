<x-layouts.account title="Suivi de ma demande — Ezer Home" active-tab="ecoute">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pb-12 lg:pb-18 py-6 lg:py-10">
        <div class="max-w-[820px] flex flex-col gap-6">

            <nav aria-label="Fil d'Ariane" class="text-sm">
                <a href="{{ route('espace.ecoute') }}" class="text-ezer-green font-semibold hover:underline">← Toutes mes demandes</a>
            </nav>

            @if (session('status'))
                <p role="status" class="p-4 rounded-ezer bg-ezer-green-light text-ezer-green font-semibold">{{ session('status') }}</p>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="font-cooper font-normal text-2xl lg:text-3xl">Demande {{ $demande->shortRef() }}</h1>
                <span class="inline-flex items-center px-3 min-h-[36px] rounded-ezer bg-ezer-green-light text-ezer-green font-semibold">{{ $demande->status->label() }}</span>
            </div>

            <p class="p-4 rounded-ezer bg-ezer-green-pale">{{ $demande->status->consultantHint() }}</p>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1 p-4 border border-ezer-green-border rounded-ezer">
                    <dt class="text-[13px] text-ezer-muted">Envoyée le</dt>
                    <dd class="font-semibold">{{ $demande->created_at->locale('fr')->translatedFormat('j F Y à H\hi') }}</dd>
                </div>
                <div class="flex flex-col gap-1 p-4 border border-ezer-green-border rounded-ezer">
                    <dt class="text-[13px] text-ezer-muted">Canal de contact souhaité</dt>
                    <dd class="font-semibold">{{ $demande->channelLabel() ?? 'Peu importe' }}</dd>
                </div>
            </dl>

            <section class="flex flex-col gap-2">
                <h2 class="text-sm font-semibold text-ezer-muted">Ce que vous avez partagé</h2>
                <p class="p-4 border border-ezer-green-border rounded-ezer whitespace-pre-line">{{ $demande->description }}</p>
            </section>
        </div>
    </div>
</x-layouts.account>
