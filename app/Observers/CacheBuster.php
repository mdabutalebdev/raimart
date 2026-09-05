<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Clears the cached home-page fragments whenever the admin saves a banner,
 * category or testimonial, so edits show up immediately.
 */
class CacheBuster
{
    private const KEYS = [
        'home:banners',
        'home:categories',
        'home:testimonials',
        'nav:categories',
    ];

    public function saved(Model $model): void
    {
        $this->flush();
    }

    public function deleted(Model $model): void
    {
        $this->flush();
    }

    private function flush(): void
    {
        foreach (self::KEYS as $key) {
            Cache::forget($key);
        }
    }
}
