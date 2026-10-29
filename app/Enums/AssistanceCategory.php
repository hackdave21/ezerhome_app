<?php

namespace App\Enums;

/** Types d'aide proposés dans le formulaire d'assistance (étape 1.8). */
enum AssistanceCategory: string
{
    case Logement = 'logement';
    case Alimentation = 'alimentation';
    case Habillement = 'habillement';
    case FraisScolarite = 'frais_scolarite';
    case Fournitures = 'fournitures';
    case Autre = 'autre';

    public function label(): string
    {
        return match ($this) {
            self::Logement => 'Logement',
            self::Alimentation => 'Alimentation',
            self::Habillement => 'Habillement',
            self::FraisScolarite => 'Frais de scolarité',
            self::Fournitures => 'Fournitures',
            self::Autre => 'Autre',
        };
    }

    /** @return array<string, string> valeur => libellé, pour les listes et filtres. */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $c) => [$c->value => $c->label()])->all();
    }
}
