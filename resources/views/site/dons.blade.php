<x-layouts.public title="Faire un don — Ezer Home" active="">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Faire un don</li>
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Faire un don</h1>
                <p class="text-[17px] max-w-[680px]">Votre don soutient l'écoute et l'aide matérielle. <a href="{{ route('usage-dons') }}" class="font-semibold text-ezer-green hover:underline">À quoi servent les dons ?</a></p>
            </div>
        </section>

        <div class="max-w-[680px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-6" x-data="{ anonyme: {{ old('is_anonymous') ? 'true' : 'false' }} }">

            @if ($errors->any())
                <ul role="alert" class="p-3.5 px-4 rounded-ezer bg-ezer-red-light border border-ezer-red-border text-ezer-red text-[15px] list-disc pl-8">
                    @foreach ($errors->all() as $erreur)<li>{{ $erreur }}</li>@endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('dons.store') }}" novalidate class="flex flex-col gap-6">
                @csrf
                <div aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden">
                    <label for="do-website">Ne pas remplir</label><input id="do-website" name="website" type="text" tabindex="-1" autocomplete="off">
                </div>

                {{-- 1. Montant --}}
                <fieldset class="flex flex-col gap-2">
                    <legend class="text-[15px] font-semibold mb-1">Montant de votre don (FCFA)</legend>
                    <div class="flex flex-wrap gap-2" x-data="{ montant: '{{ old('amount') }}' }" x-init="$watch('montant', v => $refs.amount.value = v)">
                        @foreach ($suggested as $m)
                            <button type="button" @click="montant = '{{ $m }}'" :class="montant === '{{ $m }}' ? 'chip-actif' : 'chip-inactif'" class="min-h-[48px] px-4 rounded-ezer font-semibold">{{ number_format($m, 0, ',', ' ') }}</button>
                        @endforeach
                        <input type="number" name="amount" x-ref="amount" value="{{ old('amount') }}" @input="montant = $event.target.value" min="100" placeholder="Autre montant" class="h-[48px] px-4 w-[160px] border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                    </div>
                </fieldset>

                {{-- 2. Moyen de paiement --}}
                <fieldset class="flex flex-col gap-2">
                    <legend class="text-[15px] font-semibold mb-1">Moyen de paiement</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($methods as $methode)
                            <label class="flex items-center gap-3 p-4 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                                <input type="radio" name="method" value="{{ $methode->value }}" @checked(old('method') === $methode->value) class="w-5 h-5 shrink-0 accent-ezer-green">
                                <span class="font-semibold">{{ $methode->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                {{-- 3. Fréquence : ponctuel / régulier, présentés à égalité --}}
                <fieldset class="flex flex-col gap-2">
                    <legend class="text-[15px] font-semibold mb-1">Fréquence</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex flex-col gap-0.5 p-4 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                            <span class="flex items-center gap-3"><input type="radio" name="frequence" value="ponctuel" @checked(old('frequence', 'ponctuel') === 'ponctuel') class="w-5 h-5 shrink-0 accent-ezer-green"><span class="font-semibold">Don ponctuel</span></span>
                            <span class="text-sm text-ezer-muted pl-8">Un don unique.</span>
                        </label>
                        <label class="flex flex-col gap-0.5 p-4 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                            <span class="flex items-center gap-3"><input type="radio" name="frequence" value="regulier" @checked(old('frequence') === 'regulier') class="w-5 h-5 shrink-0 accent-ezer-green"><span class="font-semibold">Don régulier</span></span>
                            <span class="text-sm text-ezer-muted pl-8">Un rappel mensuel par notification (sans prélèvement automatique).</span>
                        </label>
                    </div>
                </fieldset>

                {{-- 4. Don anonyme --}}
                <label class="flex items-start gap-3 text-[15px] cursor-pointer">
                    <input type="checkbox" name="is_anonymous" value="1" x-model="anonyme" @checked(old('is_anonymous')) class="w-6 h-6 shrink-0 accent-ezer-green">
                    <span><strong class="font-semibold">Je souhaite faire un don anonyme.</strong> Votre nom ne sera ni publié ni conservé.</span>
                </label>

                {{-- 5 & 6. Nom et contact (facultatifs, masqués si anonyme) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="!anonyme" x-cloak>
                    <div class="flex flex-col gap-1.5">
                        <label for="do-nom" class="text-[15px] font-semibold">Nom <span class="font-normal text-ezer-muted">(facultatif)</span></label>
                        <input id="do-nom" name="donor_name" value="{{ old('donor_name') }}" type="text" autocomplete="name" class="h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="do-contact" class="text-[15px] font-semibold">Contact <span class="font-normal text-ezer-muted">(facultatif)</span></label>
                        <input id="do-contact" name="donor_contact" value="{{ old('donor_contact') }}" type="text" class="h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <p class="p-3.5 px-4 rounded-ezer bg-ezer-green-light text-[15px]">🔒 Vos informations sont confidentielles. Aucune donnée bancaire n'est demandée ni stockée : vous effectuerez le versement vous-même vers le numéro marchand indiqué à l'étape suivante.</p>
                    <button type="submit" class="self-start w-full sm:w-auto inline-flex items-center justify-center min-h-[52px] px-7 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Continuer</button>
                </div>
            </form>
        </div>
</x-layouts.public>
