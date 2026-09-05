<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = ['011939', '083369', 'F58502'];

        $data = [
            'Electronics' => ['Mobiles & Tablets', 'Laptops & Computers', 'Headphones & Audio', 'Cameras', 'Smart Watches'],
            'Fashion' => ["Men's Wear", "Women's Wear", 'Footwear', 'Bags & Wallets', 'Accessories'],
            'Home & Living' => ['Furniture', 'Kitchenware', 'Home Decor', 'Bedding & Bath'],
            'Beauty' => ['Skincare', 'Makeup', 'Fragrance', 'Hair Care'],
            'Groceries' => ['Snacks & Beverages', 'Staples', 'Organic Foods', 'Household Essentials'],
            'Sports & Outdoor' => ['Fitness Gear', 'Cycling', 'Camping & Hiking', 'Team Sports'],
            'Toys & Baby' => ['Toys & Games', 'Baby Gear', 'Learning & Education', 'Feeding & Nursing'],
        ];

        $index = 0;

        foreach ($data as $name => $subcategories) {
            $color = $colors[$index % count($colors)];

            $parent = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'image' => "https://placehold.co/300x225/{$color}/F5F2F2?font=playfair-display&text=".urlencode($name),
                'sort_order' => $index,
                'is_active' => true,
            ]);

            foreach ($subcategories as $subIndex => $subName) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name' => $subName,
                    'slug' => Str::slug($name.'-'.$subName),
                    'sort_order' => $subIndex,
                    'is_active' => true,
                ]);
            }

            $index++;
        }
    }
}
