<x-layouts.account title="Profil et confidentialité — Ezer Home" active-tab="profil">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pb-12 lg:pb-18 py-6 lg:py-10">
        <div class="max-w-[820px] flex flex-col gap-7">

            <h1 class="font-cooper font-normal text-2xl lg:text-3xl">Profil et confidentialité</h1>

            @if (session('status'))
                <p role="status" class="p-3.5 px-4 rounded-ezer bg-ezer-green-light border border-ezer-green text-[15px] font-semibold">{{ session('status') }}</p>
            @endif

            <!-- MES INFORMATIONS -->
            <section aria-labelledby="pr-infos" class="flex flex-col gap-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 id="pr-infos" class="text-xl font-semibold">Mes informations</h2>
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Nom ou pseudonyme</dt><dd class="font-semibold">{{ $user->name }}</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Téléphone</dt><dd class="font-semibold">{{ $telephone ?? 'Non renseigné' }}</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">E-mail</dt><dd class="font-semibold">{{ $user->email ?? 'Non renseigné' }}</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Contact préféré</dt><dd class="font-semibold">{{ $user->preferred_contact ?? 'Non renseigné' }}</dd></div>
                </dl>
            </section>

            <!-- CHARTE -->
            <section aria-labelledby="pr-charte" class="flex flex-col gap-1.5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 id="pr-charte" class="text-xl font-semibold">Charte d'écoute</h2>
                    <a href="{{ url('/charte') }}" class="inline-flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">Relire la charte</a>
                </div>
                <p class="text-[15px] text-ezer-muted">Pas encore signée.</p>
            </section>

            <!-- SÉCURITÉ -->
            <section aria-labelledby="pr-securite" class="flex flex-col gap-4">
                <h2 id="pr-securite" class="text-xl font-semibold">Sécurité</h2>
                <ul class="flex flex-col list-none">
                    <li class="flex flex-wrap items-center justify-between gap-3 py-4 border-b border-ezer-green-borderLight">
                        <div class="flex flex-col">
                            <span class="font-semibold">Mot de passe</span>
                            <span class="text-sm text-ezer-muted">
                                @if ($user->password === null)
                                    Aucun : vous vous connectez par code ou par lien.
                                @elseif ($user->password_changed_at)
                                    Modifié le {{ $user->password_changed_at->locale('fr')->translatedFormat('j F Y') }}
                                @else
                                    Défini à l'inscription
                                @endif
                            </span>
                        </div>
                        <a href="{{ route('password.request') }}" class="font-semibold text-ezer-green hover:underline">{{ $user->password === null ? 'Définir' : 'Modifier' }}</a>
                    </li>
                    <li class="flex flex-wrap items-center justify-between gap-3 py-4 border-b border-ezer-green-borderLight">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="font-semibold">Double authentification</span>
                            @if ($user->hasConfirmedTwoFactor())
                                <span class="text-[13px] font-semibold px-2 py-0.5 rounded-full bg-ezer-green-light border border-ezer-green text-ezer-green">Activée</span>
                            @else
                                <span class="text-[13px] font-semibold px-2 py-0.5 rounded-full bg-white border border-ezer-borderMuted text-ezer-muted">Désactivée</span>
                            @endif
                        </div>
                        @if ($user->hasConfirmedTwoFactor())
                            <form method="POST" action="{{ route('two-factor.regenerate') }}" onsubmit="return confirm('Générer de nouveaux codes de secours ? Les anciens ne fonctionneront plus.')">
                                @csrf
                                <button type="submit" class="font-semibold text-ezer-green hover:underline bg-transparent border-0 p-0 cursor-pointer">Gérer</button>
                            </form>
                        @else
                            <a href="{{ route('two-factor.setup') }}" class="font-semibold text-ezer-green hover:underline">Activer</a>
                        @endif
                    </li>
                    <li class="flex flex-col gap-2.5 py-4 border-b border-ezer-green-borderLight">
                        <div class="flex flex-col">
                            <span class="font-semibold">Appareils connectés</span>
                            <span class="text-sm text-ezer-muted">
                                {{ $appareils }} {{ $appareils > 1 ? 'appareils connectés' : 'appareil connecté' }}@if ($user->last_activity_at) (dernière connexion le {{ $user->last_activity_at->locale('fr')->translatedFormat('j F Y') }})@endif.
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <form method="POST" action="{{ route('espace.logout-others') }}">
                                @csrf
                                <button id="deconnecter" type="submit" class="inline-flex items-center justify-center min-h-[48px] px-5 rounded-ezer border-2 border-ezer-green bg-white text-ezer-green font-semibold hover:bg-ezer-green-light transition-colors cursor-pointer">Déconnecter tous les autres appareils</button>
                            </form>
                            <span class="text-sm text-ezer-muted">Utile si vous avez perdu un téléphone ou utilisé un appareil partagé.</span>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- MES DONNÉES -->
            <section aria-labelledby="pr-donnees" class="flex flex-col gap-3">
                <h2 id="pr-donnees" class="text-xl font-semibold">Mes données</h2>
                <p>Recevez une copie de toutes les informations que nous détenons sur vous : profil, demandes, messages, inscriptions. Le fichier est prêt rapidement et reste téléchargeable 7 jours.</p>
                @if ($export?->isDownloadable())
                    <p><a href="{{ route('espace.export.download', $export) }}" class="font-semibold text-ezer-green hover:underline">Télécharger mon export (prêt le {{ $export->ready_at->locale('fr')->translatedFormat('j F Y') }})</a></p>
                @elseif ($export && $export->ready_at === null)
                    <p class="text-ezer-muted">Votre export est en cours de préparation.</p>
                @endif
                <form method="POST" action="{{ route('espace.export') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center min-h-[48px] px-5 rounded-ezer border-2 border-ezer-green bg-white text-ezer-green font-semibold hover:bg-ezer-green-light transition-colors cursor-pointer">Demander l'export de mes données</button>
                </form>
            </section>

            <!-- SUPPRIMER -->
            <section aria-labelledby="pr-supprimer" class="flex flex-col gap-3">
                <h2 id="pr-supprimer" class="text-xl font-semibold">Supprimer mon compte</h2>
                <p>Votre compte, vos demandes et vos messages seront supprimés définitivement après un délai de [30 jours], pendant lequel vous pourrez changer d'avis. Les données de paiement sont conservées [durée légale].</p>
                <div class="flex flex-col gap-1.5 items-start">
                    @if ($suppressionDemandee)
                        <p class="font-semibold">Votre demande de suppression est en cours de traitement.</p>
                    @else
                        <form method="POST" action="{{ route('espace.suppression') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center min-h-[48px] px-5 rounded-ezer border-2 border-ezer-dark bg-white text-ezer-dark font-semibold hover:bg-gray-100 transition-colors cursor-pointer">Demander la suppression</button>
                        </form>
                        <span class="text-sm text-ezer-muted">Une confirmation par code vous sera demandée.</span>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-layouts.account>
