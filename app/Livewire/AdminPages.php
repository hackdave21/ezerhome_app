<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** CMS (Admin) : édition des pages de contenu (mentions, confidentialité, CGU, dons, FAQ). */
class AdminPages extends Component
{
    /** Pages éditables attendues : slug => titre par défaut. */
    public const KNOWN = [
        'a-quoi-servent-les-dons' => 'À quoi servent les dons',
        'faq' => 'Foire aux questions',
        'mentions-legales' => 'Mentions légales',
        'confidentialite' => 'Politique de confidentialité',
        'cgu' => "Conditions générales d'utilisation",
    ];

    public ?string $editingSlug = null;

    public string $title = '';

    public string $body = '';

    public bool $isPublished = true;

    public bool $saved = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);
    }

    #[Computed]
    public function pages()
    {
        $existantes = Page::orderBy('slug')->get()->keyBy('slug');

        return collect(self::KNOWN)->map(fn ($titre, $slug) => [
            'slug' => $slug,
            'title' => $existantes[$slug]->title ?? $titre,
            'published' => $existantes[$slug]->is_published ?? false,
            'exists' => isset($existantes[$slug]),
        ])->values();
    }

    public function edit(string $slug): void
    {
        abort_unless(isset(self::KNOWN[$slug]), 404);

        $page = Page::where('slug', $slug)->first();

        $this->editingSlug = $slug;
        $this->title = $page->title ?? self::KNOWN[$slug];
        $this->body = (string) ($page->body ?? '');
        $this->isPublished = $page->is_published ?? true;
        $this->saved = false;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        abort_unless(auth()->user()->can('settings.manage'), 403);
        abort_unless($this->editingSlug !== null && isset(self::KNOWN[$this->editingSlug]), 422);

        $this->validate([
            'title' => ['required', 'string', 'max:150'],
            'body' => ['nullable', 'string'],
            'isPublished' => ['boolean'],
        ]);

        Page::updateOrCreate(
            ['slug' => $this->editingSlug],
            ['title' => $this->title, 'body' => $this->body ?: null, 'is_published' => $this->isPublished],
        );

        $this->saved = true;
        $this->editingSlug = null;
        unset($this->pages);
    }

    public function cancel(): void
    {
        $this->editingSlug = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin-pages');
    }
}
