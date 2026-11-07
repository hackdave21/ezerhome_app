@once
<div x-data="ezerInstallApp()" x-cloak>
    <!-- Invitation à installer l'application -->
    <div x-show="visible && !iosHelp"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed inset-x-3 bottom-3 z-[60] mx-auto max-w-md rounded-ezer border border-ezer-green-border bg-white p-4 shadow-lg sm:inset-x-auto sm:right-5 sm:bottom-5"
         role="dialog" aria-label="Installer l'application Ezer Home">
        <div class="flex items-start gap-3">
            <img src="{{ asset('icons/icon-192.png') }}" alt="" class="h-12 w-12 shrink-0 rounded-xl border border-ezer-green-borderLight object-contain">
            <div class="flex-1">
                <p class="font-semibold text-ezer-dark">Installer Ezer Home</p>
                <p class="mt-1 text-sm text-ezer-muted">Ajoutez l'application à votre écran d'accueil pour y accéder en un geste, comme une vraie app.</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <button type="button" @click="install()"
                            class="inline-flex min-h-[44px] items-center justify-center rounded-ezer bg-ezer-green px-4 font-semibold text-white hover:bg-ezer-green-dark">
                        Installer
                    </button>
                    <button type="button" @click="dismiss()"
                            class="inline-flex min-h-[44px] items-center justify-center rounded-ezer border border-ezer-borderMuted px-4 font-semibold text-ezer-dark hover:bg-ezer-green-pale">
                        Plus tard
                    </button>
                </div>
            </div>
            <button type="button" @click="dismiss()" aria-label="Fermer"
                    class="shrink-0 rounded-full p-1 text-ezer-muted hover:bg-ezer-green-pale">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"></path></svg>
            </button>
        </div>
    </div>

    <!-- Mode d'emploi iOS -->
    <div x-show="iosHelp" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center bg-black/50 p-4" role="dialog" aria-modal="true" aria-label="Installer sur iPhone ou iPad">
        <div class="max-w-sm rounded-ezer bg-white p-6">
            <h2 class="text-lg font-semibold text-ezer-dark">Installer sur iPhone / iPad</h2>
            <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-ezer-dark">
                <li>Appuyez sur le bouton <strong>Partager</strong> en bas de Safari.</li>
                <li>Faites défiler et choisissez <strong>« Sur l'écran d'accueil »</strong>.</li>
                <li>Appuyez sur <strong>Ajouter</strong>.</li>
            </ol>
            <p class="mt-3 text-xs text-ezer-muted">L'application Ezer Home apparaîtra parmi vos applications.</p>
            <div class="mt-5 flex gap-2">
                <button type="button" @click="iosHelp = false"
                        class="inline-flex min-h-[44px] flex-1 items-center justify-center rounded-ezer bg-ezer-green px-4 font-semibold text-white hover:bg-ezer-green-dark">
                    Compris
                </button>
                <button type="button" @click="dismiss()"
                        class="inline-flex min-h-[44px] items-center justify-center rounded-ezer border border-ezer-borderMuted px-4 font-semibold text-ezer-dark hover:bg-ezer-green-pale">
                    Plus tard
                </button>
            </div>
        </div>
    </div>
</div>
@endonce
