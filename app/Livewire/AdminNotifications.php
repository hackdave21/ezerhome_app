<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Support\NotificationTemplates;
use Livewire\Component;

/** Notifications (Admin) : édition des modèles de messages (sujet + corps), sans données sensibles. */
class AdminNotifications extends Component
{
    /** @var array<int, array{subject: string, body: string}> indexé comme le catalogue */
    public array $values = [];

    public bool $saved = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        foreach (NotificationTemplates::all() as $i => $modele) {
            $this->values[$i] = [
                'subject' => (string) Setting::get($modele['base'].'.subject', $modele['subject']),
                'body' => (string) Setting::get($modele['base'].'.body', $modele['body']),
            ];
        }
    }

    public function templates(): array
    {
        return NotificationTemplates::all();
    }

    public function save(): void
    {
        abort_unless(auth()->user()->can('settings.manage'), 403);

        foreach (NotificationTemplates::all() as $i => $modele) {
            Setting::put($modele['base'].'.subject', $this->values[$i]['subject'] ?? null);
            Setting::put($modele['base'].'.body', $this->values[$i]['body'] ?? null);
        }

        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.admin-notifications');
    }
}
