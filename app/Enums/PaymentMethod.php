<?php

namespace App\Enums;

/** Moyens de paiement proposés au donateur (carte plus tard). */
enum PaymentMethod: string
{
    case Mixx = 'mixx';
    case Moov = 'moov';

    public function label(): string
    {
        return match ($this) {
            self::Mixx => 'Mixx By Yas',
            self::Moov => 'Moov Money',
        };
    }

    /** Clé de réglage du numéro marchand associé. */
    public function merchantSettingKey(): string
    {
        return 'donations.merchant.'.$this->value;
    }
}
