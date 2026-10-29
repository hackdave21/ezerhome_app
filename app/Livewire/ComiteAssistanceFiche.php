<?php

namespace App\Livewire;

use App\Enums\AssistanceStatus;
use App\Models\AssistanceRequest;
use App\Models\MediaAsset;
use App\Models\MediaUsage;
use App\Services\Assistance\AssistanceWorkflow;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** Fiche d'une demande d'assistance : statut + historique, notes internes, décaissements, pièces. */
class ComiteAssistanceFiche extends Component
{
    public AssistanceRequest $demande;

    // Changement de statut
    public string $newStatus = '';

    public string $statusNote = '';

    // Note interne
    public string $note = '';

    // Décaissement
    public ?int $amount = null;

    public string $disbursedOn = '';

    public string $disbursementLabel = '';

    // Pièce justificative
    public ?int $attachId = null;

    public function mount(): void
    {
        abort_unless(Gate::allows('view', $this->demande), 403);
        $this->newStatus = $this->demande->status->value;
    }

    /** Autres demandes partageant le même numéro (détection de doublons). */
    #[Computed]
    public function duplicates()
    {
        return AssistanceRequest::where('phone_hash', $this->demande->phone_hash)
            ->whereKeyNot($this->demande->id)
            ->latest('id')
            ->get();
    }

    /** Pièces justificatives (catégorie assistance) disponibles à rattacher. */
    #[Computed]
    public function availableAttachments()
    {
        $dejaLiees = $this->demande->attachments()->pluck('media_assets.id');

        return MediaAsset::where('category', MediaAsset::ASSISTANCE)
            ->where('status', 'ready')->whereNull('archived_at')
            ->whereNotIn('id', $dejaLiees)
            ->latest('id')->get();
    }

    public function changeStatus(AssistanceWorkflow $workflow): void
    {
        abort_unless(Gate::allows('updateStatus', $this->demande), 403);

        $this->validate([
            'newStatus' => ['required', Rule::enum(AssistanceStatus::class)],
            'statusNote' => ['nullable', 'string', 'max:1000'],
        ]);

        $workflow->changeStatus($this->demande, AssistanceStatus::from($this->newStatus), $this->statusNote ?: null, auth()->user());

        $this->statusNote = '';
        $this->demande->refresh();
        unset($this->duplicates);
    }

    public function addNote(AssistanceWorkflow $workflow): void
    {
        abort_unless(Gate::allows('updateStatus', $this->demande), 403);

        $this->validate(['note' => ['required', 'string', 'max:1000']]);

        $workflow->addNote($this->demande, $this->note, auth()->user());

        $this->note = '';
        $this->demande->refresh();
    }

    public function addDisbursement(AssistanceWorkflow $workflow): void
    {
        abort_unless(Gate::allows('updateStatus', $this->demande), 403);

        $this->validate([
            'amount' => ['required', 'integer', 'min:1'],
            'disbursedOn' => ['required', 'date'],
            'disbursementLabel' => ['nullable', 'string', 'max:120'],
        ]);

        $workflow->addDisbursement($this->demande, (int) $this->amount, $this->disbursedOn, auth()->user(), $this->disbursementLabel ?: null);

        $this->reset(['amount', 'disbursedOn', 'disbursementLabel']);
        $this->demande->refresh();
    }

    public function attach(): void
    {
        abort_unless(Gate::allows('updateStatus', $this->demande) && auth()->user()->can('media.manage-assistance'), 403);

        $this->validate(['attachId' => ['required', Rule::exists('media_assets', 'id')]]);

        MediaUsage::firstOrCreate([
            'media_asset_id' => $this->attachId,
            'model_type' => AssistanceRequest::class,
            'model_id' => $this->demande->id,
        ], [
            'label' => 'Assistance '.$this->demande->tracking_no,
            'url' => route('comite.demandes.show', $this->demande),
        ]);

        $this->attachId = null;
        unset($this->availableAttachments);
    }

    public function detach(int $mediaId): void
    {
        abort_unless(Gate::allows('updateStatus', $this->demande) && auth()->user()->can('media.manage-assistance'), 403);

        MediaUsage::where('media_asset_id', $mediaId)
            ->where('model_type', AssistanceRequest::class)
            ->where('model_id', $this->demande->id)
            ->delete();

        unset($this->availableAttachments);
    }

    public function render()
    {
        return view('livewire.comite-assistance-fiche');
    }
}
