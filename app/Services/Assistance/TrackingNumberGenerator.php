<?php

namespace App\Services\Assistance;

use App\Models\AssistanceRequest;
use Illuminate\Support\Facades\DB;

/**
 * Génère un numéro de suivi « ASS-AAAA-NNNNN » avec un compteur annuel. L'incrément est fait
 * dans une transaction avec verrou sur la dernière ligne de l'année, et la colonne tracking_no
 * est unique : aucun doublon possible, même en cas de dépôts simultanés.
 */
class TrackingNumberGenerator
{
    public function next(?int $year = null): string
    {
        $year ??= (int) now()->year;
        $prefix = sprintf('ASS-%d-', $year);

        return DB::transaction(function () use ($prefix) {
            $dernier = AssistanceRequest::where('tracking_no', 'like', $prefix.'%')
                ->orderByDesc('tracking_no')
                ->lockForUpdate()
                ->value('tracking_no');

            $numero = $dernier ? ((int) substr($dernier, -5)) + 1 : 1;

            return sprintf('%s%05d', $prefix, $numero);
        });
    }
}
