<x-layouts.interne title="Tableau de bord — Ezer Home" espace="admin" actif="dashboard">
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Tableau de bord</h1>
            <p class="text-[15px] text-ezer-muted">Vue d'ensemble de l'activité à traiter.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <a href="{{ route('admin.ecoute') }}" class="flex flex-col gap-1 p-5 border border-ezer-green-border rounded-ezer hover:bg-ezer-green-pale transition-colors">
                <span class="text-[13px] text-ezer-muted">Demandes d'écoute en attente</span>
                <span class="text-3xl font-semibold">{{ $ecouteEnAttente }}</span>
            </a>
            <a href="{{ route('comite.demandes') }}" class="flex flex-col gap-1 p-5 border border-ezer-green-border rounded-ezer hover:bg-ezer-green-pale transition-colors">
                <span class="text-[13px] text-ezer-muted">Assistance en cours</span>
                <span class="text-3xl font-semibold">{{ $assistanceEnCours }}</span>
            </a>
            <a href="{{ route('admin.dons') }}" class="flex flex-col gap-1 p-5 border border-ezer-green-border rounded-ezer hover:bg-ezer-green-pale transition-colors">
                <span class="text-[13px] text-ezer-muted">Paiements à confirmer</span>
                <span class="text-3xl font-semibold">{{ $paiementsAConfirmer }}</span>
            </a>
            <div class="flex flex-col gap-1 p-5 border border-ezer-green-border rounded-ezer">
                <span class="text-[13px] text-ezer-muted">Dons du mois</span>
                <span class="text-3xl font-semibold">{{ $donsDuMois }}</span>
                <span class="text-[13px] text-ezer-muted">{{ number_format($montantConfirmeMois, 0, ',', ' ') }} FCFA confirmés</span>
            </div>
        </div>
    </div>
</x-layouts.interne>
