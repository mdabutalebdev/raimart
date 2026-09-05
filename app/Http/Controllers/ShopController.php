<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /** Product flag filters: request value => model column. */
    private const FLAGS = [
        'best_seller' => 'is_best_seller',
        'featured' => 'is_featured',
        'new_arrival' => 'is_new_arrival',
    ];

    public function index(Request $request)
    {
        $topCategories = Category::active()->topLevel()->with(['children' => fn ($q) => $q->active()])->orderBy('sort_order')->get();
        $allBrands = Brand::active()->orderBy('sort_order')->orderBy('name')->get();

        // --- Selected filters (multi-select; legacy singular params still honoured) ---
        $selectedCategories = collect((array) $request->input('categories', []))
            ->merge($request->filled('category') ? [(string) $request->input('category')] : [])
            ->filter()->unique()->values();

        $selectedBrands = collect((array) $request->input('brands', []))
            ->merge($request->filled('brand') ? [(string) $request->input('brand')] : [])
            ->filter()->unique()->values();

        $selectedFlags = collect((array) $request->input('flags', []))
            ->intersect(array_keys(self::FLAGS))->values();

        // Expand category slugs to ids (a top-level category also covers its children)
        $categoryIds = $this->resolveCategoryIds($selectedCategories, $topCategories);
        $brandIds = $allBrands->whereIn('slug', $selectedBrands)->pluck('id');

        // Single active category drives the page heading / breadcrumb
        $activeCategory = $selectedCategories->count() === 1
            ? Category::active()->where('slug', $selectedCategories->first())->first()
            : null;

        // --- Price bounds: derived from the category/brand/search context only ---
        $context = fn () => Product::active()
            ->when($categoryIds->isNotEmpty(), fn ($q) => $q->whereIn('category_id', $categoryIds))
            ->when($brandIds->isNotEmpty(), fn ($q) => $q->whereIn('brand_id', $brandIds))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'));

        $priceFloor = (int) floor($context()->min('price') ?? 0);
        $priceCeil = (int) ceil($context()->max('price') ?? 0);

        if ($priceCeil <= $priceFloor) {
            $priceCeil = $priceFloor + 100;
        }

        $minPrice = $request->filled('min_price') ? max($priceFloor, min($priceCeil, (int) $request->min_price)) : $priceFloor;
        $maxPrice = $request->filled('max_price') ? min($priceCeil, max($priceFloor, (int) $request->max_price)) : $priceCeil;

        if ($minPrice > $maxPrice) {
            [$minPrice, $maxPrice] = [$maxPrice, $minPrice];
        }

        // --- The product grid: every active filter applied ---
        $products = Product::with(['category', 'images', 'brand'])
            ->active()
            ->when($categoryIds->isNotEmpty(), fn ($q) => $q->whereIn('category_id', $categoryIds))
            ->when($brandIds->isNotEmpty(), fn ($q) => $q->whereIn('brand_id', $brandIds))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('min_price'), fn ($q) => $q->where('price', '>=', $minPrice))
            ->when($request->filled('max_price'), fn ($q) => $q->where('price', '<=', $maxPrice))
            ->when($request->boolean('in_stock'), fn ($q) => $q->where('stock', '>', 0))
            ->when($request->boolean('on_sale'), fn ($q) => $q->whereColumn('old_price', '>', 'price'))
            ->when($selectedFlags->isNotEmpty(), fn ($q) => $q->where(function ($sub) use ($selectedFlags) {
                foreach ($selectedFlags as $flag) {
                    $sub->orWhere(self::FLAGS[$flag], true);
                }
            }))
            ->when($request->sort === 'price_low', fn ($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_high', fn ($q) => $q->orderByDesc('price'))
            ->when($request->sort === 'popular', fn ($q) => $q->orderByDesc('reviews_count'))
            ->when(! $request->filled('sort'), fn ($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        // --- Per-option counts (global active products) for the filter labels ---
        $countsByCategory = Product::active()->selectRaw('category_id, count(*) as aggregate')->groupBy('category_id')->pluck('aggregate', 'category_id');
        $categoryCounts = $topCategories->mapWithKeys(function ($category) use ($countsByCategory) {
            $ids = collect([$category->id])->merge($category->children->pluck('id'));

            return [$category->slug => (int) $ids->sum(fn ($id) => $countsByCategory[$id] ?? 0)];
        });

        $brands = $allBrands->map(function ($brand) {
            $brand->products_count = $brand->products()->where('is_active', true)->count();

            return $brand;
        });

        $flagCounts = [
            'best_seller' => (int) Product::active()->bestSeller()->count(),
            'featured' => (int) Product::active()->featured()->count(),
            'new_arrival' => (int) Product::active()->newArrival()->count(),
        ];

        $availabilityCounts = [
            'in_stock' => (int) Product::active()->where('stock', '>', 0)->count(),
            'on_sale' => (int) Product::active()->whereColumn('old_price', '>', 'price')->count(),
        ];

        return view('shop', [
            'categories' => $topCategories,
            'brands' => $brands,
            'products' => $products,
            'activeCategory' => $activeCategory,
            'priceFloor' => $priceFloor,
            'priceCeil' => $priceCeil,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'selectedCategories' => $selectedCategories,
            'selectedBrands' => $selectedBrands,
            'selectedFlags' => $selectedFlags,
            'categoryCounts' => $categoryCounts,
            'flagCounts' => $flagCounts,
            'availabilityCounts' => $availabilityCounts,
        ]);
    }

    /** Turn selected category slugs into the full set of matching category ids. */
    private function resolveCategoryIds($slugs, $topCategories)
    {
        if ($slugs->isEmpty()) {
            return collect();
        }

        return Category::active()
            ->whereIn('slug', $slugs)
            ->get()
            ->flatMap(function ($category) {
                if ($category->parent_id) {
                    return [$category->id];
                }

                // Top-level: include the category itself and all of its children.
                return collect([$category->id])
                    ->merge(Category::where('parent_id', $category->id)->pluck('id'))
                    ->all();
            })
            ->unique()
            ->values();
    }
}
