<x-layouts.account :title="$titre.' — Ezer Home'" :active-tab="$onglet">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pb-12 lg:pb-18 py-6 lg:py-10">
        <div class="max-w-[820px] flex flex-col gap-4">
            <h1 class="font-cooper font-normal text-2xl lg:text-3xl">{{ $titre }}</h1>
            <p class="p-5 rounded-ezer bg-ezer-green-light">Cette rubrique sera bientôt disponible. En attendant, vous pouvez <a href="{{ route('espace.profil') }}" class="font-semibold text-ezer-green hover:underline">gérer votre profil</a> ou nous <a href="{{ url('/contact') }}" class="font-semibold text-ezer-green hover:underline">contacter</a>.</p>
        </div>
    </div>
</x-layouts.account>
