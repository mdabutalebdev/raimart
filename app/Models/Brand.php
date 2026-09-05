<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'sort_order',
        'is_active',
        'show_on_home',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'show_on_home' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnHome($query)
    {
        return $query->where('show_on_home', true);
    }
}
