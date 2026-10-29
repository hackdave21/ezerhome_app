<?php

namespace App\Models;

use App\Enums\AssistanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssistanceEvent extends Model
{
    public $timestamps = false;

    protected $fillable = ['assistance_request_id', 'status', 'note', 'actor_id', 'created_at'];

    protected function casts(): array
    {
        return [
            'note' => 'encrypted',
            'status' => AssistanceStatus::class,
            'created_at' => 'datetime',
        ];
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
