<?php

namespace App\Livewire;

use App\Models\MediaAsset;
use App\Services\Media\MediaIngest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/** Photos des actions d'assistance : réservé à la catégorie « assistance », accord de publication obligatoire. */
class ComitePhotos extends Component
{
    use WithFileUploads;

    public const ALLOWED = ['image/jpeg', 'image/png'];

    /** @var array<int, TemporaryUploadedFile> */
    public array $photos = [];

    public string $caption = '';

    public ?string $actionDate = null;

    public string $linkedRequest = '';

    public bool $consent = false;

    public array $errorsUpload = [];

    public bool $saved = false;

    public function mount(): void
    {
        abort_unless(Gate::allows('create', [MediaAsset::class, MediaAsset::ASSISTANCE]), 403);
    }

    #[Computed]
    public function gallery()
    {
        return MediaAsset::where('category', MediaAsset::ASSISTANCE)->whereNull('archived_at')->latest('id')->get();
    }

    public function updatedPhotos(MediaIngest $ingest): void
    {
        $this->errorsUpload = [];

        foreach ($this->photos as $i => $file) {
            if ($erreur = $ingest->rejection($file, self::ALLOWED)) {
                $this->errorsUpload[] = $erreur;
                unset($this->photos[$i]);
            }
        }

        $this->photos = array_values($this->photos);
        $this->saved = false;
    }

    public function removePhoto(int $index): void
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    public function save(MediaIngest $ingest): void
    {
        Gate::authorize('create', [MediaAsset::class, MediaAsset::ASSISTANCE]);

        $this->validate([
            'photos' => ['required', 'array', 'min:1'],
            'caption' => ['required', 'string', 'max:255'],
            'actionDate' => ['nullable', 'date'],
            'linkedRequest' => ['nullable', 'string', 'max:40'],
            'consent' => ['accepted'],
        ], [
            'photos.required' => 'Choisissez au moins une photo.',
            'caption.required' => 'Ajoutez une légende : elle décrit la photo.',
            'consent.accepted' => 'Sans l\'accord de publication, les photos ne peuvent pas être enregistrées.',
        ]);

        foreach ($this->photos as $file) {
            // Légende = texte alternatif ; jamais de nom ni de contact de bénéficiaire.
            $ingest->store($file, Auth::user(), [
                'category' => MediaAsset::ASSISTANCE,
                'caption' => $this->caption,
                'alt' => $this->caption,
                'action_date' => $this->actionDate ?: null,
                'linked_request' => $this->linkedRequest ?: null,
                'consent_confirmed' => true,
                'is_public' => true,
            ]);
        }

        $this->reset('photos', 'caption', 'actionDate', 'linkedRequest', 'consent');
        $this->saved = true;
        unset($this->gallery);
    }

    public function render()
    {
        return view('livewire.comite-photos');
    }
}
