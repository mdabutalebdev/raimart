<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Dove', 'Vaseline', 'Sakura', 'Lux', 'Nivea', 'Sunsilk'];

        $brands = collect($names)->map(function ($name, $index) {
            return Brand::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => $index, 'is_active' => true, 'show_on_home' => true],
            );
        });

        // Give every product without a brand a random one, so the filter has data to show.
        Product::whereNull('brand_id')->get()->each(function ($product) use ($brands) {
            $product->update(['brand_id' => $brands->random()->id]);
        });
    }
}
