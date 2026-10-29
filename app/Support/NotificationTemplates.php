<?php

namespace App\Support;

use App\Mail\AssistanceNotification;
use App\Mail\DonationReminder;
use App\Mail\ListeningNotification;

/**
 * Catalogue des modèles de notifications modifiables par l'Admin. Chaque entrée fournit la clé de
 * réglage de base (sujet/corps surchargeables), un libellé et les valeurs par défaut.
 */
class NotificationTemplates
{
    /** @return list<array{base: string, label: string, subject: string, body: string}> */
    public static function all(): array
    {
        $groupes = [
            ['Écoute', 'notif.listening.', ListeningNotification::TEMPLATES],
            ['Assistance', 'notif.assistance.', AssistanceNotification::TEMPLATES],
        ];

        $entries = [];

        foreach ($groupes as [$prefixeLabel, $prefixeCle, $modeles]) {
            foreach ($modeles as $type => $modele) {
                $entries[] = [
                    'base' => $prefixeCle.$type,
                    'label' => $prefixeLabel.' · '.$type,
                    'subject' => $modele['subject'],
                    'body' => $modele['body'],
                ];
            }
        }

        $entries[] = [
            'base' => 'notif.donation.reminder',
            'label' => 'Dons · rappel mensuel',
            'subject' => DonationReminder::DEFAULTS['subject'],
            'body' => DonationReminder::DEFAULTS['body'],
        ];

        return $entries;
    }
}
