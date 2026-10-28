<?php

namespace App\Livewire;

use App\Models\MediaAsset;
use App\Policies\MediaAssetPolicy;
use App\Services\Media\MediaIngest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/** Médiathèque de l'Admin : envoi multiple, recherche, filtres, édition, remplacement, suppression protégée. */
class MediaLibrary extends Component
{
    use WithFileUploads;

    public const ALLOWED = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'application/pdf'];

    /** Filtre de catégorie : « toutes » ou une clé de MediaAsset::CATEGORIES. */
    public string $category = 'toutes';

    public string $search = '';

    public bool $showArchived = false;

    public ?int $selectedId = null;

    /** @var array<int, TemporaryUploadedFile> */
    public array $uploads = [];

    public $replacement = null;

    // Formulaire d'édition
    public string $title = '';

    public string $alt = '';

    public string $caption = '';

    public string $credit = '';

    public string $editCategory = MediaAsset::GENERAL;

    public bool $isPublic = false;

    public bool $consent = false;

    public bool $deleteBlocked = false;

    public array $errorsUpload = [];

    public bool $saved = false;

    public function mount(): void
    {
        abort_unless(Gate::allows('viewAny', MediaAsset::class), 403);
    }

    #[Computed]
    public function categories(): array
    {
        return MediaAssetPolicy::categoriesFor(Auth::user());
    }

    #[Computed]
    public function assets()
    {
        return MediaAsset::query()
            ->whereIn('category', $this->categories)
            ->when($this->category !== 'toutes', fn ($q) => $q->where('category', $this->category))
            ->when(! $this->showArchived, fn ($q) => $q->whereNull('archived_at'))
            ->when(trim($this->search) !== '', fn ($q) => $q->where(function ($q) {
                $term = '%'.trim($this->search).'%';
                $q->where('title', 'like', $term)->orWhere('original_name', 'like', $term)->orWhere('caption', 'like', $term);
            }))
            ->withCount('usages')
            ->latest('id')
            ->get();
    }

    #[Computed]
    public function selected(): ?MediaAsset
    {
        return $this->selectedId
            ? MediaAsset::with(['usages', 'uploader'])->whereIn('category', $this->categories)->find($this->selectedId)
            : null;
    }

    #[Computed]
    public function totals(): array
    {
        $query = MediaAsset::query()->whereIn('category', $this->categories);

        return ['count' => (clone $query)->count(), 'bytes' => (int) (clone $query)->sum('size')];
    }

    public function setCategory(string $category): void
    {
        $this->category = $category === 'toutes' || in_array($category, $this->categories, true) ? $category : 'toutes';
    }

    public function select(int $id): void
    {
        if ($this->selectedId === $id) {
            $this->selectedId = null;

            return;
        }

        $asset = MediaAsset::findOrFail($id);
        $this->authorize('update', $asset);

        $this->selectedId = $id;
        $this->deleteBlocked = false;
        $this->saved = false;
        $this->resetErrorBag();
        $this->title = $asset->title;
        $this->alt = (string) $asset->alt;
        $this->caption = (string) $asset->caption;
        $this->credit = (string) $asset->credit;
        $this->editCategory = $asset->category;
        $this->isPublic = $asset->is_public;
        $this->consent = $asset->consent_confirmed;
    }

    /** Envoi multiple : chaque fichier est contrôlé (type réel, taille) puis traité en arrière-plan. */
    public function updatedUploads(MediaIngest $ingest): void
    {
        $this->errorsUpload = [];
        $category = $this->category !== 'toutes' ? $this->category : MediaAsset::GENERAL;

        foreach ($this->uploads as $file) {
            Gate::authorize('create', [MediaAsset::class, $category]);

            if ($erreur = $ingest->rejection($file, self::ALLOWED)) {
                $this->errorsUpload[] = $erreur;

                continue;
            }

            $ingest->store($file, Auth::user(), ['category' => $category]);
        }

        $this->uploads = [];
        unset($this->assets, $this->totals);
    }

    public function save(): void
    {
        $asset = MediaAsset::findOrFail($this->selectedId);
        $this->authorize('update', $asset);
        Gate::authorize('create', [MediaAsset::class, $this->editCategory]);

        $this->validate([
            'title' => ['required', 'string', 'max:120'],
            'alt' => [$asset->isImage() ? 'required' : 'nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'credit' => ['nullable', 'string', 'max:120'],
            'editCategory' => ['required', 'in:'.implode(',', array_keys(MediaAsset::CATEGORIES))],
        ], [
            'alt.required' => 'Le texte alternatif est obligatoire : il décrit l\'image pour les personnes qui ne la voient pas.',
            'title.required' => 'Donnez un titre au fichier.',
        ]);

        // Une photo d'assistance ne devient publiable qu'avec l'accord de publication des personnes.
        if ($this->isPublic && $this->editCategory === MediaAsset::ASSISTANCE && ! $this->consent) {
            $this->addError('isPublic', "L'accord de publication est obligatoire pour rendre une photo d'assistance publiable.");

            return;
        }

        $asset->update([
            'title' => $this->title,
            'alt' => $this->alt ?: null,
            'caption' => $this->caption ?: null,
            'credit' => $this->credit ?: null,
            'category' => $this->editCategory,
            'is_public' => $this->isPublic,
            'consent_confirmed' => $this->consent,
        ]);

        $asset->syncDisk();

        $this->saved = true;
        unset($this->assets, $this->selected);
    }

    /** Remplace le fichier en conservant l'identifiant : les liens existants ne cassent pas. */
    public function replaceFile(MediaIngest $ingest): void
    {
        $asset = MediaAsset::findOrFail($this->selectedId);
        $this->authorize('update', $asset);

        $this->errorsUpload = [];

        if ($erreur = $ingest->rejection($this->replacement, self::ALLOWED)) {
            $this->errorsUpload[] = $erreur;
        } else {
            $ingest->replace($asset, $this->replacement);
        }

        $this->replacement = null;
        unset($this->assets, $this->selected, $this->totals);
    }

    public function toggleArchive(): void
    {
        $asset = MediaAsset::findOrFail($this->selectedId);
        $this->authorize('update', $asset);

        $asset->update(['archived_at' => $asset->isArchived() ? null : now()]);

        unset($this->assets, $this->selected);
    }

    /** Suppression refusée tant que le fichier est utilisé : la liste des pages concernées s'affiche. */
    public function delete(): void
    {
        $asset = MediaAsset::withCount('usages')->findOrFail($this->selectedId);
        $this->authorize('delete', $asset);

        if ($asset->usages_count > 0) {
            $this->deleteBlocked = true;

            return;
        }

        $asset->deleteFiles();
        $asset->delete();

        $this->selectedId = null;
        $this->deleteBlocked = false;
        unset($this->assets, $this->selected, $this->totals);
    }

    public function closeBlock(): void
    {
        $this->deleteBlocked = false;
    }

    public function render()
    {
        return view('livewire.media-library');
    }
}
