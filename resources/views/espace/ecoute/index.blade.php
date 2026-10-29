<x-layouts.account title="Écoute — Ezer Home" active-tab="ecoute">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pb-12 lg:pb-18 py-6 lg:py-10">
        <div class="max-w-[820px] flex flex-col gap-8">

            <div class="flex flex-col gap-2">
                <h1 class="font-cooper font-normal text-2xl lg:text-3xl">Être écouté</h1>
                <p class="text-ezer-muted">Expliquez librement ce qui vous amène. Un membre du bureau lira votre demande et vous mettra en relation avec un intervenant. Vos échanges restent confidentiels.</p>
            </div>

            @if (session('status'))
                <p role="status" class="p-4 rounded-ezer bg-ezer-green-light text-ezer-green font-semibold">{{ session('status') }}</p>
            @endif

            {{-- Formulaire de demande --}}
            <form method="POST" action="{{ route('espace.ecoute.store') }}" class="flex flex-col gap-4 p-5 lg:p-6 border border-ezer-green-border rounded-ezer">
                @csrf
                <h2 class="text-lg font-semibold">Nouvelle demande d'écoute</h2>

                <div class="flex flex-col gap-1">
                    <label for="ec-desc" class="text-sm font-semibold">Ce que vous souhaitez partager</label>
                    <textarea id="ec-desc" name="description" rows="6" required
                              class="px-3 py-2 border border-ezer-borderMuted rounded-ezer text-[15px] focus:border-ezer-green focus:outline-none"
                              placeholder="Écrivez avec vos mots, à votre rythme.">{{ old('description') }}</textarea>
                    @error('description')<span role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1">
                        <label for="ec-urg" class="text-sm font-semibold">Degré d'urgence (facultatif)</label>
                        <select id="ec-urg" name="urgency" class="h-[48px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-[15px] focus:border-ezer-green focus:outline-none">
                            <option value="">Je préfère ne pas préciser</option>
                            @foreach (\App\Models\ListeningRequest::URGENCIES as $cle => $label)
                                <option value="{{ $cle }}" @selected(old('urgency') === $cle)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="ec-canal" class="text-sm font-semibold">Comment vous contacter (facultatif)</label>
                        <select id="ec-canal" name="preferred_channel" class="h-[48px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-[15px] focus:border-ezer-green focus:outline-none">
                            <option value="">Peu importe</option>
                            @foreach (\App\Models\ListeningRequest::CHANNELS as $cle => $label)
                                <option value="{{ $cle }}" @selected(old('preferred_channel') === $cle)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <p class="text-[13px] text-ezer-muted">Vos informations sont chiffrées et ne sont visibles que par les personnes habilitées à traiter votre demande.</p>

                <button type="submit" class="self-start inline-flex items-center justify-center min-h-[52px] px-6 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Envoyer ma demande</button>
            </form>

            {{-- Suivi des demandes --}}
            <section class="flex flex-col gap-3">
                <h2 class="text-lg font-semibold">Mes demandes</h2>
                @forelse ($demandes as $demande)
                    <a href="{{ route('espace.ecoute.show', $demande) }}" class="flex flex-wrap items-center justify-between gap-2 p-4 border border-ezer-green-border rounded-ezer bg-white hover:bg-ezer-green-pale transition-colors">
                        <span class="flex flex-col gap-0.5">
                            <span class="font-semibold text-sm">Demande {{ $demande->shortRef() }}</span>
                            <span class="text-[13px] text-ezer-muted">{{ $demande->created_at->locale('fr')->translatedFormat('j F Y') }}</span>
                        </span>
                        <span class="inline-flex items-center px-3 min-h-[32px] rounded-ezer bg-ezer-green-light text-ezer-green text-[13px] font-semibold">{{ $demande->status->label() }}</span>
                    </a>
                @empty
                    <p class="p-4 rounded-ezer bg-ezer-green-pale text-ezer-muted">Vous n'avez pas encore de demande. Utilisez le formulaire ci-dessus pour en créer une.</p>
                @endforelse
            </section>
        </div>
    </div>
</x-layouts.account>
