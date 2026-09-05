<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_description',
        'is_active',
        'show_in_footer',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'show_in_footer' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Active pages the admin chose to list in the footer, in admin order. */
    public function scopeVisible($query)
    {
        return $query->active()->where('show_in_footer', true)->orderBy('sort_order')->orderBy('title');
    }
}
