<?php

namespace App\Enums;

/**
 * Cycle de vie d'un paiement (étape 1.9) :
 * initie → en_attente → paye | echoue | expire | rembourse.
 * Le passage à « paye » n'est possible que par une confirmation Admin tracée (ou, plus tard,
 * un webhook vérifié) — garanti au niveau du modèle Payment.
 */
enum PaymentStatus: string
{
    case Initie = 'initie';
    case EnAttente = 'en_attente';
    case Paye = 'paye';
    case Echoue = 'echoue';
    case Expire = 'expire';
    case Rembourse = 'rembourse';

    public function label(): string
    {
        return match ($this) {
            self::Initie => 'Initié',
            self::EnAttente => 'En attente',
            self::Paye => 'Payé',
            self::Echoue => 'Échoué',
            self::Expire => 'Expiré',
            self::Rembourse => 'Remboursé',
        };
    }
}
