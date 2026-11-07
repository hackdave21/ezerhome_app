@once
<div x-data="ezerCookieConsent()" x-cloak>
    <div x-show="visible"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed inset-x-0 bottom-0 z-50 border-t border-ezer-green-border bg-white p-4 shadow-[0_-8px_30px_rgba(14,28,3,0.12)] sm:p-5"
         role="dialog" aria-live="polite" aria-label="Consentement aux cookies">
        <div class="mx-auto flex max-w-[1400px] flex-col gap-3 lg:flex-row lg:items-center lg:justify-between lg:gap-6">
            <p class="text-sm text-ezer-dark">
                Nous utilisons uniquement des cookies nécessaires au fonctionnement du site (connexion et sécurité).
                Aucun cookie de mesure d'audience ou de publicité n'est posé. En continuant, vous acceptez ces cookies essentiels.
                <a href="{{ url('/confidentialite') }}" class="font-semibold text-ezer-green hover:underline">En savoir plus</a>
            </p>
            <div class="flex shrink-0 gap-2">
                <button type="button" @click="refuse()"
                        class="inline-flex min-h-[48px] flex-1 items-center justify-center rounded-ezer border border-ezer-borderMuted px-5 font-semibold text-ezer-dark hover:bg-ezer-green-pale lg:flex-none">
                    Refuser
                </button>
                <button type="button" @click="accept()"
                        class="inline-flex min-h-[48px] flex-1 items-center justify-center rounded-ezer bg-ezer-green px-5 font-semibold text-white hover:bg-ezer-green-dark lg:flex-none">
                    Tout accepter
                </button>
            </div>
        </div>
    </div>
</div>
@endonce
