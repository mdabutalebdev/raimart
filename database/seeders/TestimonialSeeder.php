<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Rakib Hossain', 'location' => 'Dhaka', 'rating' => 5, 'text' => 'Genuine products, fast delivery every single time. Raimart is now my go-to for everything.'],
            ['name' => 'Sadia Islam', 'location' => 'Chattogram', 'rating' => 5, 'text' => 'Customer support helped me with a return within minutes. Really smooth experience.'],
            ['name' => 'Tanvir Ahmed', 'location' => 'Sylhet', 'rating' => 4, 'text' => 'Great prices and the packaging quality is excellent. Highly recommend.'],
            ['name' => 'Nusrat Jahan', 'location' => 'Khulna', 'rating' => 5, 'text' => 'Wide range of categories in one place, I barely need to shop anywhere else now.'],
        ];

        foreach ($testimonials as $index => $data) {
            Testimonial::create([
                'name' => $data['name'],
                'location' => $data['location'],
                'rating' => $data['rating'],
                'text' => $data['text'],
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }
}
