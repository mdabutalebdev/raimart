<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_description' => 'Your trusted online shopping destination. Quality products across every category, delivered fast and priced fair.',
            'facebook_url' => 'https://facebook.com/raimart',
            'instagram_url' => 'https://instagram.com/raimart',
            'twitter_url' => 'https://x.com/raimart',
            'app_store_url' => '#',
            'google_play_url' => '#',
            'contact_address' => 'Dhaka, Bangladesh',
            'contact_phone' => '+880 1XXX-XXXXXX',
            'contact_email' => 'support@raimart.com',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }
    }
}
