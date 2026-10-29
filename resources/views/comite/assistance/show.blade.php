<x-layouts.interne title="Fiche d'assistance — Ezer Home" espace="comite" actif="demandes">
    <nav aria-label="Fil d'Ariane" class="text-sm">
        <a href="{{ route('comite.demandes') }}" class="text-ezer-green font-semibold hover:underline">← Toutes les demandes</a>
    </nav>

    <livewire:comite-assistance-fiche :demande="$demande" />
</x-layouts.interne>
