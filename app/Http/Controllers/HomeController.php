<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /** Everything a product card renders, eager loaded so cards never query per row. */
    private const CARD_RELATIONS = ['category:id,name', 'brand:id,name', 'images'];

    public function index()
    {
        // Banners and categories change rarely — cache them and let the admin
        // panel bust the cache on save (see CacheBuster observer).
        [$heroBanners, $promoBanners] = Cache::remember('home:banners', now()->addHours(6), function () {
            $banners = Banner::active()->whereIn('type', ['hero', 'promo'])->orderBy('sort_order')->get();

            return [
                $banners->where('type', 'hero')->values(),
                $banners->where('type', 'promo')->values(),
            ];
        });

        $categories = Cache::remember('home:categories', now()->addHours(6), fn () => $this->homeCategories());

        $featuredProducts = Product::with(self::CARD_RELATIONS)->active()->featured()->latest()->take(10)->get();
        $bestSelling = Product::with(self::CARD_RELATIONS)->active()->bestSeller()->latest()->take(15)->get();
        $newArrivals = Product::with(self::CARD_RELATIONS)->active()->newArrival()->latest()->take(15)->get();
        // 10 shown per page, paged client-side.
        $justForYou = Product::with(self::CARD_RELATIONS)->active()->inRandomOrder()->take(40)->get();

        $testimonials = Cache::remember(
            'home:testimonials',
            now()->addHours(6),
            fn () => Testimonial::active()->orderBy('sort_order')->get()
        );

        return view('home', compact(
            'heroBanners',
            'promoBanners',
            'categories',
            'featuredProducts',
            'bestSelling',
            'newArrivals',
            'justForYou',
            'testimonials',
        ));
    }

    /**
     * Home categories with a product count that includes their sub-categories.
     * One grouped count query for all of them instead of one per category.
     */
    private function homeCategories()
    {
        $categories = Category::active()
            ->topLevel()
            ->where('show_on_home', true)
            ->with(['children' => fn ($q) => $q->active()])
            ->orderBy('sort_order')
            ->get();

        $ids = $categories->flatMap(fn ($c) => [$c->id, ...$c->children->pluck('id')])->unique();

        $counts = Product::active()
            ->whereIn('category_id', $ids)
            ->groupBy('category_id')
            ->pluck(DB::raw('count(*)'), 'category_id');

        return $categories->map(function ($category) use ($counts) {
            $category->products_total = (int) $counts->get($category->id, 0)
                + (int) $category->children->sum(fn ($child) => $counts->get($child->id, 0));

            return $category;
        })->values();
    }
}
