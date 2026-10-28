<x-layouts.guest title="Nouveau mot de passe — Ezer Home">

                <div class="flex flex-col gap-2">
                    <p class="inline-flex items-center gap-1.5 text-sm font-semibold text-ezer-green">
                        <svg aria-hidden="true" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                        Code vérifié
                    </p>
                    <h1 class="font-cooper font-normal text-3xl">Choisissez un nouveau mot de passe</h1>
                </div>

                <form method="POST" action="{{ route('password.update') }}" novalidate aria-label="Nouveau mot de passe" class="flex flex-col gap-5">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label for="re-mdp" class="text-[15px] font-semibold">Nouveau mot de passe</label>
                        <div class="flex items-center border border-ezer-borderMuted rounded-ezer bg-white pr-1">
                            <input id="re-mdp" name="password" type="password" autocomplete="new-password" aria-describedby="re-regles" class="flex-1 min-w-0 h-[52px] px-4 border-0 rounded-ezer bg-white focus:outline-none">
                            <button type="button" id="re-toggle" aria-pressed="false" class="shrink-0 min-h-[44px] px-3 border-0 bg-transparent text-ezer-green font-semibold text-sm">Afficher</button>
                        </div>
                        <ul id="re-regles" class="flex flex-col gap-1 text-sm list-none">
                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" class="shrink-0 w-4 h-4 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                                8 caractères minimum <span class="sr-only">(respecté)</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg aria-hidden="true" class="shrink-0 w-4 h-4 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                                Au moins un chiffre <span class="sr-only">(respecté)</span>
                            </li>
                        </ul>
                        @error('password')<p role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="re-mdp2" class="text-[15px] font-semibold">Confirmez le mot de passe</label>
                        <input id="re-mdp2" name="password_confirmation" type="password" autocomplete="new-password" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                    </div>

                    <button type="submit" class="w-full min-h-[56px] rounded-ezer border-0 bg-ezer-green text-white text-[17px] font-semibold hover:bg-ezer-green-dark transition-colors">Enregistrer et me connecter</button>
                </form>

                <p class="text-sm text-ezer-muted">Par sécurité, vous serez déconnecté(e) de vos autres appareils.</p>

@push('scripts')
<script>
(function () {
    var mdp = document.getElementById('re-mdp');
    var btn = document.getElementById('re-toggle');
    btn.addEventListener('click', function () {
        var visible = mdp.type === 'text';
        mdp.type = visible ? 'password' : 'text';
        btn.textContent = visible ? 'Afficher' : 'Masquer';
        btn.setAttribute('aria-pressed', visible ? 'false' : 'true');
    });
})();
</script>
@endpush
</x-layouts.guest>
