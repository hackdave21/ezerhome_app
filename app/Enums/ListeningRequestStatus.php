<?php

namespace App\Enums;

/**
 * Cycle de vie d'une demande d'écoute (cahier des charges, étape 1.7) :
 * envoyee → en_cours_affectation → affectee → en_cours → cloturee.
 * Une déclinaison par le thérapeute renvoie la demande en « en_cours_affectation ».
 */
enum ListeningRequestStatus: string
{
    case Envoyee = 'envoyee';
    case EnCoursAffectation = 'en_cours_affectation';
    case Affectee = 'affectee';
    case EnCours = 'en_cours';
    case Cloturee = 'cloturee';

    public function label(): string
    {
        return match ($this) {
            self::Envoyee => 'Envoyée',
            self::EnCoursAffectation => "En cours d'affectation",
            self::Affectee => 'Affectée',
            self::EnCours => 'En cours',
            self::Cloturee => 'Clôturée',
        };
    }

    /** Message affiché au consultant pour le suivi (sans jargon). */
    public function consultantHint(): string
    {
        return match ($this) {
            self::Envoyee => 'Votre demande a bien été reçue. Le bureau va la traiter.',
            self::EnCoursAffectation => 'Le bureau recherche un intervenant disponible.',
            self::Affectee => 'Un intervenant vous a été attribué ; il va bientôt vous contacter.',
            self::EnCours => 'Votre accompagnement est en cours.',
            self::Cloturee => 'Cet accompagnement est clôturé.',
        };
    }

    public function isOpen(): bool
    {
        return $this !== self::Cloturee;
    }
}
