<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heroSlides = [
            [
                'title' => 'Your Trusted Online Shopping Destination',
                'subtitle' => 'Discover quality products across every category, at prices that make sense.',
                'image' => 'https://placehold.co/1200x600/011939/011939?text=+',
            ],
            [
                'title' => 'Big Savings On Electronics',
                'subtitle' => 'Headphones, speakers, and smart gadgets at unbeatable prices.',
                'image' => 'https://placehold.co/1200x600/083369/083369?text=+',
            ],
            [
                'title' => 'New Season, New Style',
                'subtitle' => 'Fresh fashion arrivals curated just for you.',
                'image' => 'https://placehold.co/1200x600/F58502/F58502?text=+',
            ],
        ];

        foreach ($heroSlides as $index => $slide) {
            Banner::create([
                'type' => 'hero',
                'title' => $slide['title'],
                'subtitle' => $slide['subtitle'],
                'image' => $slide['image'],
                'link' => '#',
                'button_text' => 'Shop Now',
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        Banner::create([
            'type' => 'promo',
            'title' => 'Electronics Sale',
            'badge_text' => '10% OFF',
            'image' => 'https://placehold.co/600x300/083369/F5F2F2?font=playfair-display&text=Electronics+Sale',
            'link' => '#',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        Banner::create([
            'type' => 'promo',
            'title' => 'Fashion Sale',
            'badge_text' => '10% OFF',
            'image' => 'https://placehold.co/600x300/F58502/ffffff?font=playfair-display&text=Fashion+Sale',
            'link' => '#',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}
