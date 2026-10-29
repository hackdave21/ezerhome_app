<?php

namespace App\Livewire;

use App\Models\MediaAsset;
use App\Models\OrgMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** Administration de l'organigramme : ajout, modification, réordonnancement, masquage, suppression. */
class OrgMemberManager extends Component
{
    public ?int $editingId = null;

    public bool $showForm = false;

    public string $first_name = '';

    public string $last_name = '';

    public string $position = '';

    public string $committee = OrgMember::BUREAU_EXECUTIF;

    public ?int $media_id = null;

    public string $bio = '';

    public string $public_contact = '';

    public bool $is_visible = true;

    public bool $saved = false;

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);
    }

    #[Computed]
    public function members()
    {
        return OrgMember::with('photo')->orderBy('sort_order')->orderBy('id')->get()->groupBy('committee');
    }

    /** Photos disponibles pour illustrer un membre (catégorie « organigramme », prêtes). */
    #[Computed]
    public function photos()
    {
        return MediaAsset::where('category', MediaAsset::ORGANIGRAMME)
            ->where('status', 'ready')->whereNull('archived_at')->latest('id')->get();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $member = OrgMember::findOrFail($id);

        $this->editingId = $member->id;
        $this->first_name = $member->first_name;
        $this->last_name = $member->last_name;
        $this->position = $member->position;
        $this->committee = $member->committee;
        $this->media_id = $member->media_id;
        $this->bio = (string) $member->bio;
        $this->public_contact = (string) $member->public_contact;
        $this->is_visible = $member->is_visible;
        $this->showForm = true;
        $this->saved = false;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);

        $data = $this->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'position' => ['required', 'string', 'max:120'],
            'committee' => ['required', Rule::in(array_keys(OrgMember::COMMITTEES))],
            'media_id' => ['nullable', Rule::exists('media_assets', 'id')],
            'bio' => ['nullable', 'string', 'max:1000'],
            'public_contact' => ['nullable', 'string', 'max:120'],
            'is_visible' => ['boolean'],
        ]);

        if ($this->editingId) {
            OrgMember::findOrFail($this->editingId)->update($data);
        } else {
            $data['sort_order'] = (int) OrgMember::where('committee', $this->committee)->max('sort_order') + 1;
            OrgMember::create($data);
        }

        $this->resetForm();
        $this->saved = true;
        unset($this->members);
    }

    public function toggleVisible(int $id): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);

        $member = OrgMember::findOrFail($id);
        $member->update(['is_visible' => ! $member->is_visible]);

        unset($this->members);
    }

    public function delete(int $id): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);

        OrgMember::findOrFail($id)->delete();

        if ($this->editingId === $id) {
            $this->resetForm();
        }

        unset($this->members);
    }

    /** Réordonnancement (glisser-déposer) : la liste des identifiants, dans leur nouvel ordre, pour un comité. */
    public function reorder(array $ids): void
    {
        abort_unless(Auth::user()?->can('org.manage'), 403);

        foreach (array_values($ids) as $position => $id) {
            OrgMember::where('id', (int) $id)->update(['sort_order' => $position]);
        }

        unset($this->members);
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'showForm', 'first_name', 'last_name', 'position', 'media_id', 'bio', 'public_contact']);
        $this->committee = OrgMember::BUREAU_EXECUTIF;
        $this->is_visible = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.org-member-manager');
    }
}
