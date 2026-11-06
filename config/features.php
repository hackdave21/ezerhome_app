<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature flags
    |--------------------------------------------------------------------------
    |
    | phase2 : Débloque les modules et routes de la phase 2 (dons, paiements,
    |          formations, coaching, candidatures, réunions, messagerie, etc.).
    |          Désactivé par défaut en phase 1 (false).
    |
    */
    'phase2' => (bool) env('FEATURE_PHASE2', false),
];
