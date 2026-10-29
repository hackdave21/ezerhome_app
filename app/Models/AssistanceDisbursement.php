<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Décaissement effectué : trace simple (montant, date, responsable), sans logique bancaire. */
class AssistanceDisbursement extends Model
{
    protected $fillable = ['assistance_request_id', 'amount', 'disbursed_on', 'responsible_id', 'label'];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'disbursed_on' => 'date',
        ];
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}
