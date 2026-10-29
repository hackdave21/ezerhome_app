<?php

namespace App\Enums;

/**
 * Cycle de vie d'une demande d'assistance (étape 1.8) :
 * recue → en_examen → acceptee → close, avec la sortie refusee.
 */
enum AssistanceStatus: string
{
    case Recue = 'recue';
    case EnExamen = 'en_examen';
    case Acceptee = 'acceptee';
    case Close = 'close';
    case Refusee = 'refusee';

    public function label(): string
    {
        return match ($this) {
            self::Recue => 'Reçue',
            self::EnExamen => "En cours d'examen",
            self::Acceptee => 'Acceptée',
            self::Close => 'Clôturée',
            self::Refusee => 'Refusée',
        };
    }

    /** Message de suivi public, sans aucun détail sensible. */
    public function publicHint(): string
    {
        return match ($this) {
            self::Recue => 'Votre demande a bien été reçue.',
            self::EnExamen => 'Votre demande est en cours d\'examen.',
            self::Acceptee => 'Votre demande a été acceptée. Nous allons vous recontacter.',
            self::Close => 'Votre demande est clôturée.',
            self::Refusee => 'Votre demande n\'a pas pu aboutir. Vous pouvez nous contacter pour en savoir plus.',
        };
    }
}
