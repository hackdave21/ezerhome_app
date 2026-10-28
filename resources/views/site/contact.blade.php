<x-layouts.public title="Nous contacter — Ezer Home" active="contact">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        @if ($profil === 'organisation')
                        <li><a href="{{ url('/contact') }}" class="text-ezer-green hover:underline">Contact</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Donateur institutionnel</li>
                        @else
                        <li aria-current="page">Contact</li>
                        @endif
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Nous contacter</h1>
                <p class="text-[17px] max-w-[680px]">Une question, une suggestion, un projet de partenariat ? Écrivez-nous, nous vous répondons sous [X] jours ouvrés.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col lg:flex-row items-start gap-10 lg:gap-14">

            <!-- COLONNE FORMULAIRE -->
            <div class="flex-1 basis-[560px] min-w-0 w-full flex flex-col gap-6">

                <fieldset class="flex flex-col gap-3">
                    <legend class="text-lg font-semibold mb-2">Vous êtes</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-start gap-3 p-4 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                            <input type="radio" name="ct-profil" value="general" @checked($profil !== 'organisation') class="w-5 h-5 mt-0.5 shrink-0 accent-ezer-green">
                            <span class="flex flex-col gap-0.5">
                                <span class="font-semibold">Un particulier</span>
                                <span class="text-sm text-ezer-muted">Une question, une suggestion, une envie de vous engager.</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 p-4 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                            <input type="radio" name="ct-profil" value="organisation" @checked($profil === 'organisation') class="w-5 h-5 mt-0.5 shrink-0 accent-ezer-green">
                            <span class="flex flex-col gap-0.5">
                                <span class="font-semibold">Une organisation</span>
                                <span class="text-sm text-ezer-muted">Donateur institutionnel, entreprise, fondation ou partenaire.</span>
                            </span>
                        </label>
                    </div>
                </fieldset>

                <!-- FORMULAIRE PARTICULIER -->
                <form id="ct-form" method="POST" action="{{ route('contact.send') }}" novalidate {{ $profil === 'organisation' ? 'hidden' : '' }} aria-label="Formulaire de contact" class="flex flex-col gap-5">
                    @csrf
                    <input type="hidden" name="profil" value="particulier">
                    <div aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden"><label for="ct-website">Ne pas remplir</label><input id="ct-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
                    @include('site.partials.contact-feedback', ['form' => 'particulier'])

                    <p class="p-3.5 px-4 rounded-ezer bg-ezer-green-light text-[15px]">
                        Besoin d'être écouté(e) ou d'une aide matérielle ? Passez plutôt par <a href="{{ url('/ecoute') }}" class="font-semibold">l'écoute</a> ou <a href="{{ url('/assistance') }}" class="font-semibold">l'assistance</a> : votre demande sera traitée plus vite.
                    </p>
                    <p class="text-sm text-ezer-muted">Tous les champs sont obligatoires, sauf mention « facultatif ».</p>

                    <div class="flex flex-col gap-1.5">
                        <label for="ct-nom" class="text-[15px] font-semibold">Nom et prénoms</label>
                        <input id="ct-nom" name="nom" value="{{ old('nom') }}" type="text" autocomplete="name" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="ct-tel" class="text-[15px] font-semibold">Téléphone ou WhatsApp</label>
                            <div class="flex items-stretch border border-ezer-borderMuted rounded-ezer overflow-hidden bg-white">
                                <span aria-hidden="true" class="inline-flex items-center px-3.5 bg-ezer-green-light font-semibold border-r border-ezer-borderMuted">+228</span>
                                <input id="ct-tel" name="telephone" value="{{ old('telephone') }}" type="tel" inputmode="tel" autocomplete="tel" class="flex-1 min-w-0 h-[52px] px-4 border-0 bg-white focus:outline-none">
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="ct-mail" class="text-[15px] font-semibold">E-mail <span class="font-normal text-ezer-muted">(facultatif)</span></label>
                            <input id="ct-mail" name="email" value="{{ old('email') }}" type="email" autocomplete="email" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="ct-objet" class="text-[15px] font-semibold">Objet</label>
                        <select id="ct-objet" name="objet" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                                <option value="">Choisissez un objet</option>
                                @foreach ($objets as $o)<option value="{{ $o }}" @selected(old('objet') === $o)>{{ $o }}</option>@endforeach
                            </select>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="ct-msg" class="text-[15px] font-semibold">Votre message</label>
                        <textarea id="ct-msg" name="message" rows="6" class="w-full p-3 px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none resize-y">{{ old('message') }}</textarea>
                    </div>

                    <label class="flex items-start gap-3 text-[15px] cursor-pointer">
                        <input type="checkbox" name="consentement" value="1" @checked(old('consentement')) class="w-6 h-6 shrink-0 accent-ezer-green">
                        J'accepte qu'Ezer Home utilise ces informations pour me répondre. <a href="{{ url('/confidentialite') }}" class="font-semibold text-ezer-green hover:underline">Confidentialité</a>
                    </label>

                    <button type="submit" class="self-start w-full sm:w-auto inline-flex items-center justify-center min-h-[52px] px-7 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Envoyer le message</button>
                </form>

                <!-- FORMULAIRE ORGANISATION -->
                <form id="ci-form" method="POST" action="{{ route('contact.send') }}" novalidate {{ $profil !== 'organisation' ? 'hidden' : '' }} aria-label="Formulaire pour les organisations" class="flex flex-col gap-5">
                    @csrf
                    <input type="hidden" name="profil" value="organisation">
                    <div aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden"><label for="ci-website">Ne pas remplir</label><input id="ci-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
                    @include('site.partials.contact-feedback', ['form' => 'organisation'])

                    <p class="text-sm text-ezer-muted">Tous les champs sont obligatoires, sauf mention « facultatif ».</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="ci-org" class="text-[15px] font-semibold">Nom de l'organisation</label>
                            <input id="ci-org" name="organisation" value="{{ old('organisation') }}" type="text" autocomplete="organization" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="ci-type" class="text-[15px] font-semibold">Type d'organisation</label>
                            <select id="ci-type" name="type_organisation" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                                <option value="">Choisissez</option>
                                @foreach ($typesOrganisation as $o)<option value="{{ $o }}" @selected(old('type_organisation') === $o)>{{ $o }}</option>@endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="ci-nom" class="text-[15px] font-semibold">Nom et prénoms du contact</label>
                            <input id="ci-nom" name="nom" value="{{ old('nom') }}" type="text" autocomplete="name" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="ci-fonction" class="text-[15px] font-semibold">Fonction</label>
                            <input id="ci-fonction" name="fonction" value="{{ old('fonction') }}" type="text" autocomplete="organization-title" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="ci-mail" class="text-[15px] font-semibold">E-mail professionnel</label>
                            <input id="ci-mail" name="email" value="{{ old('email') }}" type="email" autocomplete="email" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="ci-tel" class="text-[15px] font-semibold">Téléphone <span class="font-normal text-ezer-muted">(facultatif)</span></label>
                            <input id="ci-tel" name="telephone" value="{{ old('telephone') }}" type="tel" autocomplete="tel" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="ci-msg" class="text-[15px] font-semibold">Votre projet ou votre question</label>
                        <textarea id="ci-msg" name="message" rows="5" class="w-full p-3 px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none resize-y">{{ old('message') }}</textarea>
                    </div>

                    <fieldset class="flex flex-col gap-2">
                        <legend class="text-[15px] font-semibold mb-2">Soutien envisagé <span class="font-normal text-ezer-muted">(plusieurs choix possibles)</span></legend>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <label class="flex items-center gap-2.5 min-h-[52px] px-3.5 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                                <input type="checkbox" name="soutien[]" value="Don financier" @checked(in_array('Don financier', old('soutien', []), true)) class="w-5 h-5 shrink-0 accent-ezer-green">Don financier
                            </label>
                            <label class="flex items-center gap-2.5 min-h-[52px] px-3.5 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                                <input type="checkbox" name="soutien[]" value="Don en nature" @checked(in_array('Don en nature', old('soutien', []), true)) class="w-5 h-5 shrink-0 accent-ezer-green">Don en nature
                            </label>
                            <label class="flex items-center gap-2.5 min-h-[52px] px-3.5 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                                <input type="checkbox" name="soutien[]" value="Mécénat de compétences" @checked(in_array('Mécénat de compétences', old('soutien', []), true)) class="w-5 h-5 shrink-0 accent-ezer-green">Mécénat de compétences
                            </label>
                            <label class="flex items-center gap-2.5 min-h-[52px] px-3.5 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                                <input type="checkbox" name="soutien[]" value="Projet mené ensemble" @checked(in_array('Projet mené ensemble', old('soutien', []), true)) class="w-5 h-5 shrink-0 accent-ezer-green">Projet mené ensemble
                            </label>
                        </div>
                    </fieldset>

                    <label class="flex items-start gap-3 text-[15px] cursor-pointer">
                        <input type="checkbox" name="dossier" value="1" @checked(old('dossier')) class="w-6 h-6 shrink-0 accent-ezer-green">
                        Je souhaite recevoir le dossier de présentation de l'association.
                    </label>
                    <label class="flex items-start gap-3 text-[15px] cursor-pointer">
                        <input type="checkbox" name="consentement" value="1" @checked(old('consentement')) class="w-6 h-6 shrink-0 accent-ezer-green">
                        J'accepte qu'Ezer Home utilise ces informations pour me répondre. <a href="{{ url('/confidentialite') }}" class="font-semibold text-ezer-green hover:underline">Confidentialité</a>
                    </label>

                    <button type="submit" class="self-start w-full sm:w-auto inline-flex items-center justify-center min-h-[52px] px-7 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Envoyer la demande</button>
                </form>
            </div>

            <!-- ASIDE -->
            <aside class="flex-1 basis-[320px] min-w-0 w-full flex flex-col gap-5">
                <div class="bg-ezer-green-light rounded-ezer p-6 flex flex-col gap-4">
                    <h2 class="text-xl font-semibold">Nos coordonnées</h2>
                    <ul class="flex flex-col gap-4 list-none">
                        <li class="flex items-start gap-3">
                            <svg aria-hidden="true" class="shrink-0 mt-0.5 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"></path><circle cx="12" cy="9.5" r="2.5"></circle></svg>
                            <span class="flex flex-col">
                                <span>{{ setting('contact.adresse') }}</span>
                                <a href="#" class="text-sm text-ezer-green hover:underline">Voir sur une carte</a>
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg aria-hidden="true" class="shrink-0 mt-0.5 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"></path></svg>
                            <a href="tel:{{ preg_replace('/[^+0-9]/', '', setting('contact.telephone')) }}" class="font-semibold">{{ setting('contact.telephone') }}</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg aria-hidden="true" class="shrink-0 mt-0.5 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                            <a href="mailto:{{ setting('contact.email') }}" class="font-semibold">{{ setting('contact.email') }}</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg aria-hidden="true" class="shrink-0 mt-0.5 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg>
                            <span>[Du lundi au vendredi, de 8 h à 17 h]</span>
                        </li>
                    </ul>
                    <a href="https://wa.me/{{ setting('contact.whatsapp') }}" class="w-full inline-flex items-center justify-center gap-2 min-h-[52px] rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">
                        <svg aria-hidden="true" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 20.5l1.3-4A8.5 8.5 0 1 1 8 19.6z"></path><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2z"></path></svg>
                        Écrire sur WhatsApp
                    </a>
                </div>

                <div id="ci-aside" {{ $profil !== 'organisation' ? 'hidden' : '' }} class="border border-ezer-green-border rounded-ezer p-6 flex flex-col gap-2.5">
                    <h2 class="text-lg font-semibold">Pour les organisations</h2>
                    <p>Votre interlocuteur : <strong class="font-semibold">[NOM Prénoms]</strong>, [fonction].<br>
                        <a href="mailto:partenariats@ezerhome.tg" class="font-semibold">[partenariats@ezerhome.tg]</a></p>
                    <a href="#" class="inline-flex items-center gap-2 min-h-[44px] font-semibold text-ezer-green hover:underline">
                        <svg aria-hidden="true" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"></path><path d="M14 3v5h5"></path></svg>
                        Statuts (PDF)
                    </a>
                    <a href="#" class="inline-flex items-center gap-2 min-h-[44px] font-semibold text-ezer-green hover:underline">
                        <svg aria-hidden="true" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"></path><path d="M14 3v5h5"></path></svg>
                        Rapport d'activité [année] (PDF)
                    </a>
                    <a href="#" class="inline-flex items-center gap-2 min-h-[44px] font-semibold text-ezer-green hover:underline">
                        <svg aria-hidden="true" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"></path><path d="M14 3v5h5"></path></svg>
                        Dossier de présentation (PDF)
                    </a>
                </div>
            </aside>
        </div>


@push('scripts')
<script>
    (function () {
        var forms = document.querySelectorAll('[name="ct-profil"]');
        var particulier = document.getElementById('ct-form');
        var organisation = document.getElementById('ci-form');
        var aside = document.getElementById('ci-aside');

        forms.forEach(function (r) {
            r.addEventListener('change', function () {
                var org = r.value === 'organisation' && r.checked;
                particulier.hidden = org;
                organisation.hidden = !org;
                aside.hidden = !org;
            });
        });
    })();
</script>
@endpush
</x-layouts.public>
