@if (session('contact_envoye'))
    <p role="status" class="p-3.5 px-4 rounded-ezer bg-ezer-green-light border border-ezer-green text-[15px] font-semibold">Merci, votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.</p>
@endif

@if ($errors->any() && old('profil') === $form)
    <div role="alert" class="p-3.5 px-4 rounded-ezer bg-ezer-red-light border border-ezer-red-border text-ezer-red text-[15px]">
        <p class="font-semibold">Merci de corriger les points suivants :</p>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $erreur)
                <li>{{ $erreur }}</li>
            @endforeach
        </ul>
    </div>
@endif
