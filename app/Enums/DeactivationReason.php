<?php

namespace App\Enums;

enum DeactivationReason: string
{
    case Auto6Mois = 'auto_6_mois';
    case Admin = 'admin';
    case DemandeUtilisateur = 'demande_utilisateur';

    public function label(): string
    {
        return match ($this) {
            self::Auto6Mois => 'Aucune connexion depuis plus de 6 mois',
            self::Admin => 'Désactivé par un administrateur',
            self::DemandeUtilisateur => 'Désactivé à votre demande',
        };
    }
}
