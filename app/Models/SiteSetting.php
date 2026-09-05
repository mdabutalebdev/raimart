<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    private const CACHE_KEY = 'site_settings:all';

    /** Loaded once per request, so repeated get()/getAll() calls are free. */
    private static ?array $memo = null;

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::all_()[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);

        static::flushCache();
    }

    public static function getAll(): array
    {
        return static::all_();
    }

    /**
     * The whole settings table as key => value, memoised for the request and
     * cached across requests (settings change only when an admin saves).
     */
    private static function all_(): array
    {
        if (static::$memo !== null) {
            return static::$memo;
        }

        return static::$memo = Cache::remember(
            self::CACHE_KEY,
            now()->addHours(12),
            fn () => static::query()->pluck('value', 'key')->all()
        );
    }

    public static function flushCache(): void
    {
        static::$memo = null;
        Cache::forget(self::CACHE_KEY);
    }
}
