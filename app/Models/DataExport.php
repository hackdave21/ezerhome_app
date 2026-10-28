<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataExport extends Model
{
    protected $fillable = ['user_id', 'path', 'ready_at', 'expires_at'];

    protected function casts(): array
    {
        return ['ready_at' => 'datetime', 'expires_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isDownloadable(): bool
    {
        return $this->path !== null && $this->ready_at !== null && $this->expires_at?->isFuture() === true;
    }
}
