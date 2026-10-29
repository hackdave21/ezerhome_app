<x-layouts.interne title="Organigramme et partenaires — Ezer Home" espace="admin" actif="equipe">
    <div x-data="{ onglet: 'organigramme' }" class="flex flex-col gap-6">
        <div>
            <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Organigramme et partenaires</h1>
            <p class="text-[15px] text-ezer-muted">Gérez les membres de l'équipe et les logos des partenaires affichés sur le site public.</p>
        </div>

        <div role="tablist" aria-label="Sections" class="flex flex-wrap gap-1.5">
            <button type="button" role="tab" :aria-selected="onglet === 'organigramme'" @click="onglet = 'organigramme'"
                    :class="onglet === 'organigramme' ? 'chip-actif' : 'chip-inactif'" class="min-h-[44px] px-4 rounded-ezer font-semibold text-sm">Organigramme</button>
            <button type="button" role="tab" :aria-selected="onglet === 'partenaires'" @click="onglet = 'partenaires'"
                    :class="onglet === 'partenaires' ? 'chip-actif' : 'chip-inactif'" class="min-h-[44px] px-4 rounded-ezer font-semibold text-sm">Partenaires</button>
        </div>

        <div x-show="onglet === 'organigramme'">
            <livewire:org-member-manager />
        </div>
        <div x-show="onglet === 'partenaires'" x-cloak>
            <livewire:partner-manager />
        </div>
    </div>
</x-layouts.interne>
