<x-layouts.guest title="Compte désactivé — Ezer Home">

                <span aria-hidden="true" class="w-16 h-16 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M10 9v6M14 9v6"></path></svg>
                </span>

                <div class="flex flex-col gap-2">
                    <h1 class="font-cooper font-normal text-3xl">Votre compte est désactivé</h1>
                    <p>Rassurez-vous : vos informations ne sont pas perdues. Voici pourquoi, et comment le réactiver.</p>
                </div>

                <dl class="p-4 lg:px-5 rounded-ezer bg-ezer-green-light flex flex-col gap-3">
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Pourquoi ?</dt><dd class="font-semibold">{{ $user?->deactivation_reason?->label() ?? 'Compte désactivé' }}</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Désactivé le</dt><dd class="font-semibold">{{ $user?->deactivated_at?->locale('fr')->translatedFormat('j F Y') ?? '[date]' }}</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Vos données sont conservées jusqu'au</dt><dd class="font-semibold">[date]</dd></div>
                </dl>

                <div class="flex flex-col gap-3.5">
                    <h2 class="text-xl font-semibold">Pour le réactiver</h2>
                    <ol class="flex flex-col gap-3.5 list-none">
                        <li class="flex items-start gap-3">
                            <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">1</span>
                            <span class="pt-0.5">Contactez-nous en indiquant le téléphone ou l'e-mail de votre compte.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">2</span>
                            <span class="pt-0.5">Un membre du bureau vérifie qu'il s'agit bien de vous.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">3</span>
                            <span class="pt-0.5">Vous recevez un code par SMS pour vous reconnecter. Votre historique est intact.</span>
                        </li>
                    </ol>
                </div>

                <div class="flex flex-col gap-2.5">
                    <a href="https://wa.me/{{ setting('contact.whatsapp') }}" class="flex items-center justify-center gap-2 min-h-[56px] rounded-ezer bg-ezer-green text-white text-[17px] font-semibold hover:bg-ezer-green-dark transition-colors">
                        <svg aria-hidden="true" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 20.5l1.3-4A8.5 8.5 0 1 1 8 19.6z"></path><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2z"></path></svg>
                        Demander la réactivation sur WhatsApp
                    </a>
                    <a href="tel:{{ preg_replace('/[^+0-9]/', '', setting('contact.telephone')) }}" class="flex items-center justify-center min-h-[56px] rounded-ezer border-2 border-ezer-green bg-white text-ezer-green text-[17px] font-semibold hover:bg-ezer-green-light transition-colors">Appeler le {{ setting('contact.telephone') }}</a>
                    <a href="{{ url('/contact') }}" class="self-center inline-flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">Utiliser le formulaire de contact</a>
                </div>

                <p class="pt-4 border-t border-ezer-green-borderLight text-[15px] text-ezer-muted">Besoin d'une aide matérielle en attendant ? <a href="{{ url('/assistance') }}" class="font-semibold text-ezer-green hover:underline">L'assistance reste accessible sans compte.</a></p>
</x-layouts.guest>
