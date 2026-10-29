<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;

/** Paramètres (Admin) : coordonnées, montants suggérés, taille max d'upload, durées de conservation. */
class AdminSettings extends Component
{
    /** @var array<string, string> */
    public array $values = [];

    public bool $saved = false;

    /** Champs éditables : clé de réglage => [libellé, type]. */
    public const FIELDS = [
        'contact.adresse' => ['Adresse', 'text'],
        'contact.telephone' => ['Téléphone', 'text'],
        'contact.whatsapp' => ['Numéro WhatsApp (format wa.me, ex. 22890000000)', 'text'],
        'contact.email' => ['E-mail de contact', 'text'],
        'donations.suggested' => ['Montants de don suggérés (FCFA, séparés par des virgules)', 'text'],
        'donations.merchant.mixx' => ['Numéro marchand Mixx By Yas', 'text'],
        'donations.merchant.moov' => ['Numéro marchand Moov Money', 'text'],
        'media.max_mb' => ['Taille maximale d\'un fichier (Mo)', 'number'],
        'retention.assistance_days' => ['Conservation des demandes d\'assistance (jours)', 'number'],
        'retention.listening_days' => ['Conservation des demandes d\'écoute clôturées (jours)', 'number'],
    ];

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        // Indexation numérique : les clés de réglage contiennent des points (chemins Livewire).
        foreach (array_keys(self::FIELDS) as $i => $key) {
            $this->values[$i] = (string) Setting::get($key, '');
        }
    }

    public function save(): void
    {
        abort_unless(auth()->user()->can('settings.manage'), 403);

        foreach (array_keys(self::FIELDS) as $i => $key) {
            Setting::put($key, $this->values[$i] ?? null);
        }

        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.admin-settings');
    }
}
