<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Wireless Over-Ear Headphones', 'category' => 'Electronics', 'price' => 2499, 'old_price' => 3200, 'rating' => 4.6, 'reviews' => 128, 'featured' => true, 'best_seller' => true, 'new_arrival' => false],
            ['name' => 'Classic Chronograph Watch', 'category' => 'Fashion', 'price' => 3999, 'old_price' => null, 'rating' => 4.8, 'reviews' => 84, 'featured' => true, 'best_seller' => true, 'new_arrival' => false],
            ['name' => 'Leather Tote Handbag', 'category' => 'Fashion', 'price' => 1799, 'old_price' => 2100, 'rating' => 4.5, 'reviews' => 61, 'featured' => true, 'best_seller' => true, 'new_arrival' => false],
            ['name' => '3-Seater Fabric Sofa', 'category' => 'Home & Living', 'price' => 18500, 'old_price' => 21000, 'rating' => 4.7, 'reviews' => 39, 'featured' => true, 'best_seller' => true, 'new_arrival' => true],
            ['name' => 'Matte Finish Perfume', 'category' => 'Beauty', 'price' => 1250, 'old_price' => null, 'rating' => 4.4, 'reviews' => 97, 'featured' => true, 'best_seller' => true, 'new_arrival' => true],
            ['name' => 'Running Sneakers', 'category' => 'Sports & Outdoor', 'price' => 2850, 'old_price' => 3500, 'rating' => 4.6, 'reviews' => 152, 'featured' => true, 'best_seller' => false, 'new_arrival' => true],
            ['name' => 'Non-Stick Cookware Set', 'category' => 'Home & Living', 'price' => 3400, 'old_price' => null, 'rating' => 4.3, 'reviews' => 45, 'featured' => true, 'best_seller' => false, 'new_arrival' => true],
            ['name' => 'Kids Building Blocks', 'category' => 'Toys & Baby', 'price' => 990, 'old_price' => 1200, 'rating' => 4.9, 'reviews' => 73, 'featured' => true, 'best_seller' => false, 'new_arrival' => true],
            ['name' => 'Organic Green Tea Pack', 'category' => 'Groceries', 'price' => 450, 'old_price' => null, 'rating' => 4.2, 'reviews' => 28, 'featured' => true, 'best_seller' => false, 'new_arrival' => false],
            ['name' => 'Bluetooth Smart Speaker', 'category' => 'Electronics', 'price' => 2200, 'old_price' => 2600, 'rating' => 4.5, 'reviews' => 66, 'featured' => true, 'best_seller' => false, 'new_arrival' => false],
            ['name' => 'Slim Fit Denim Jacket', 'category' => 'Fashion', 'price' => 2100, 'old_price' => 2600, 'rating' => 4.4, 'reviews' => 52, 'featured' => true, 'best_seller' => false, 'new_arrival' => false],
            ['name' => 'Ceramic Table Lamp', 'category' => 'Home & Living', 'price' => 1650, 'old_price' => null, 'rating' => 4.3, 'reviews' => 33, 'featured' => true, 'best_seller' => false, 'new_arrival' => false],
            ['name' => 'Herbal Face Wash', 'category' => 'Beauty', 'price' => 380, 'old_price' => 450, 'rating' => 4.1, 'reviews' => 41, 'featured' => true, 'best_seller' => false, 'new_arrival' => false],
            ['name' => 'Yoga Mat with Strap', 'category' => 'Sports & Outdoor', 'price' => 950, 'old_price' => null, 'rating' => 4.5, 'reviews' => 59, 'featured' => true, 'best_seller' => false, 'new_arrival' => false],
        ];

        foreach ($products as $data) {
            $category = Category::query()->whereNull('parent_id')->where('name', $data['category'])->first();

            $product = Product::create([
                'category_id' => $category->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => "The {$data['name']} combines everyday reliability with a design that fits right into your lifestyle. Carefully sourced and quality-checked before it ships.",
                'price' => $data['price'],
                'old_price' => $data['old_price'],
                'stock' => rand(15, 120),
                'sku' => strtoupper(Str::random(8)),
                'rating' => $data['rating'],
                'reviews_count' => $data['reviews'],
                'is_featured' => $data['featured'],
                'is_best_seller' => $data['best_seller'],
                'is_new_arrival' => $data['new_arrival'],
                'is_active' => true,
            ]);

            foreach (range(1, 2) as $i) {
                $product->images()->create([
                    'image' => "https://placehold.co/600x600/F5F2F2/083369?font=playfair-display&text=".urlencode($data['name']).($i > 1 ? '+'.$i : ''),
                    'sort_order' => $i - 1,
                ]);
            }
        }
    }
}
