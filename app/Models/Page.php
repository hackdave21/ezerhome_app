<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Page extends Model
{
    protected $fillable = ['slug', 'title', 'body', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    protected static function booted(): void
    {
        $forget = fn (Page $page) => Cache::forget("page.{$page->slug}");

        static::saved($forget);
        static::deleted($forget);
    }

    public static function published(string $slug): ?self
    {
        return Cache::rememberForever("page.{$slug}", fn () => static::where('slug', $slug)->where('is_published', true)->first());
    }
}
