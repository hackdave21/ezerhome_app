<x-layouts.interne title="Fiche demande d'écoute — Ezer Home" espace="admin" actif="ecoute">
    <nav aria-label="Fil d'Ariane" class="text-sm">
        <a href="{{ route('admin.ecoute') }}" class="text-ezer-green font-semibold hover:underline">← Toutes les demandes</a>
    </nav>

    <livewire:admin-listening-fiche :demande="$demande" />
</x-layouts.interne>
