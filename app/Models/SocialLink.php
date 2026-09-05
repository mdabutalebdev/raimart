<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    /**
     * The social platforms the admin can switch on. Key => [label, Font Awesome icon].
     * Add a row here and it shows up in the admin screen automatically.
     */
    public const PLATFORMS = [
        'facebook' => ['label' => 'Facebook', 'icon' => 'facebook-f'],
        'messenger' => ['label' => 'Messenger', 'icon' => 'facebook-messenger'],
        'instagram' => ['label' => 'Instagram', 'icon' => 'instagram'],
        'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'whatsapp'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'youtube'],
        'twitter' => ['label' => 'X (Twitter)', 'icon' => 'x-twitter'],
        'tiktok' => ['label' => 'TikTok', 'icon' => 'tiktok'],
        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'linkedin-in'],
        'telegram' => ['label' => 'Telegram', 'icon' => 'telegram'],
        'pinterest' => ['label' => 'Pinterest', 'icon' => 'pinterest-p'],
        'threads' => ['label' => 'Threads', 'icon' => 'threads'],
        'snapchat' => ['label' => 'Snapchat', 'icon' => 'snapchat'],
    ];

    protected $fillable = [
        'platform',
        'url',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getLabelAttribute(): string
    {
        return self::PLATFORMS[$this->platform]['label'] ?? ucfirst($this->platform);
    }

    public function getIconAttribute(): string
    {
        return self::PLATFORMS[$this->platform]['icon'] ?? 'link';
    }

    /** Active links that actually have a URL, in admin order — what the footer renders. */
    public function scopeVisible($query)
    {
        return $query->where('is_active', true)->whereNotNull('url')->where('url', '!=', '')->orderBy('sort_order');
    }
}
