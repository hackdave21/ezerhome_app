<?php

namespace App\Livewire;

use App\Models\MediaAsset;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** Administration des partenaires : logo (médiathèque), lien, accord écrit, activation, ordre. */
class PartnerManager extends Component
{
    public ?int $editingId = null;

    public bool $showForm = false;

    public string $name = '';

    public ?int $media_id = null;

    public string $url = '';

    /** Accord écrit de publication obtenu : conditionne l'activation (approved_at). */
    public bool $approved = false;

    public bool $is_active = false;

    public bool $saved = false;

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);
    }

    #[Computed]
    public function partners()
    {
        return Partner::with('logo')->orderBy('sort_order')->orderBy('id')->get();
    }

    /** Logos disponibles (catégorie « logo_partenaire », prêts). */
    #[Computed]
    public function logos()
    {
        return MediaAsset::where('category', MediaAsset::LOGO_PARTENAIRE)
            ->where('status', 'ready')->whereNull('archived_at')->latest('id')->get();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $partner = Partner::findOrFail($id);

        $this->editingId = $partner->id;
        $this->name = $partner->name;
        $this->media_id = $partner->media_id;
        $this->url = (string) $partner->url;
        $this->approved = $partner->approved_at !== null;
        $this->is_active = $partner->is_active;
        $this->showForm = true;
        $this->saved = false;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);

        $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'media_id' => ['nullable', Rule::exists('media_assets', 'id')],
            'url' => ['nullable', 'url', 'max:255'],
        ]);

        // Publication refusée sans accord écrit.
        if ($this->is_active && ! $this->approved) {
            $this->addError('is_active', "L'accord écrit du partenaire est obligatoire pour l'afficher sur le site.");

            return;
        }

        $partner = $this->editingId ? Partner::findOrFail($this->editingId) : new Partner;

        // approved_at : posé la première fois que l'accord est coché, retiré si l'accord est décoché.
        $approvedAt = $this->approved ? ($partner->approved_at ?? now()) : null;

        $partner->fill([
            'name' => $this->name,
            'media_id' => $this->media_id,
            'url' => $this->url ?: null,
            'approved_at' => $approvedAt,
            'is_active' => $this->is_active && $this->approved,
        ]);

        if (! $partner->exists) {
            $partner->sort_order = (int) Partner::max('sort_order') + 1;
        }

        $partner->save();

        $this->resetForm();
        $this->saved = true;
        unset($this->partners);
    }

    public function toggleActive(int $id): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);

        $partner = Partner::findOrFail($id);

        // On ne peut activer qu'un partenaire dont l'accord écrit est enregistré.
        if (! $partner->is_active && $partner->approved_at === null) {
            return;
        }

        $partner->update(['is_active' => ! $partner->is_active]);

        unset($this->partners);
    }

    public function delete(int $id): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);

        Partner::findOrFail($id)->delete();

        if ($this->editingId === $id) {
            $this->resetForm();
        }

        unset($this->partners);
    }

    public function reorder(array $ids): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);

        foreach (array_values($ids) as $position => $id) {
            Partner::where('id', (int) $id)->update(['sort_order' => $position]);
        }

        unset($this->partners);
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'showForm', 'name', 'media_id', 'url', 'approved', 'is_active']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.partner-manager');
    }
}
