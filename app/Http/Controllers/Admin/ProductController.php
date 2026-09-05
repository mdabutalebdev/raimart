<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\Csv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /** Stock level below which a product counts as "low". */
    public const LOW_STOCK = 10;

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])
            ->when($request->search, fn ($q, $search) => $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
            }))
            ->when($request->category, fn ($q, $id) => $q->where('category_id', $id))
            ->when($request->brand, fn ($q, $id) => $q->where('brand_id', $id))
            ->when($request->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->stock === 'low', fn ($q) => $q->whereBetween('stock', [1, self::LOW_STOCK]))
            ->when($request->stock === 'out', fn ($q) => $q->where('stock', '<=', 0))
            ->when($request->stock === 'in', fn ($q) => $q->where('stock', '>', self::LOW_STOCK))
            ->when($request->flag === 'featured', fn ($q) => $q->where('is_featured', true))
            ->when($request->flag === 'best_seller', fn ($q) => $q->where('is_best_seller', true))
            ->when($request->flag === 'new_arrival', fn ($q) => $q->where('is_new_arrival', true))
            ->when($request->filled('min_price'), fn ($q) => $q->where('price', '>=', (float) $request->min_price))
            ->when($request->filled('max_price'), fn ($q) => $q->where('price', '<=', (float) $request->max_price));

        $query = match ($request->sort) {
            'name' => $query->orderBy('name'),
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'stock_low' => $query->orderBy('stock'),
            'stock_high' => $query->orderByDesc('stock'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $products = $query->paginate((int) $request->input('per_page', 20))->withQueryString();

        // Counters shown as quick filter chips.
        $counts = [
            'all' => Product::count(),
            'low' => Product::whereBetween('stock', [1, self::LOW_STOCK])->count(),
            'out' => Product::where('stock', '<=', 0)->count(),
            'inactive' => Product::where('is_active', false)->count(),
        ];

        return view('admin.products.index', [
            'products' => $products,
            'counts' => $counts,
            'lowStock' => self::LOW_STOCK,
            'categoryGroups' => $this->categoryGroups(),
            'brands' => $this->brands(),
        ]);
    }

    /** One-click CSV export of the current filtered result set. */
    public function export(Request $request)
    {
        $products = Product::with(['category', 'brand'])
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when($request->category, fn ($q, $id) => $q->where('category_id', $id))
            ->when($request->brand, fn ($q, $id) => $q->where('brand_id', $id))
            ->when($request->stock === 'low', fn ($q) => $q->whereBetween('stock', [1, self::LOW_STOCK]))
            ->when($request->stock === 'out', fn ($q) => $q->where('stock', '<=', 0))
            ->latest()
            ->get();

        $rows = $products->map(fn (Product $p) => [
            $p->id,
            $p->sku,
            $p->name,
            $p->category?->name,
            $p->brand?->name,
            $p->price,
            $p->old_price,
            $p->stock,
            $p->is_active ? 'Active' : 'Inactive',
            $p->is_featured ? 'Yes' : 'No',
            $p->is_best_seller ? 'Yes' : 'No',
            $p->is_new_arrival ? 'Yes' : 'No',
            $p->created_at?->format('Y-m-d H:i'),
        ]);

        return Csv::download(
            'products-'.now()->format('Y-m-d'),
            ['ID', 'SKU', 'Name', 'Category', 'Brand', 'Price', 'Old Price', 'Stock', 'Status', 'Featured', 'Best Seller', 'New Arrival', 'Created'],
            $rows,
        );
    }

    public function create()
    {
        $categoryGroups = $this->categoryGroups();
        $brands = $this->brands();

        return view('admin.products.create', compact('categoryGroups', 'brands'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(4));

        if ($request->hasFile('main_image')) {
            $data['main_image'] = app(\App\Services\ImageOptimizer::class)->store($request->file('main_image'), 'products');
        }
        
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = json_decode($data['attributes'], true);
        }

        $product = Product::create($data);

        $this->storeImages($request, $product);

        return redirect()->route('admin.products.index')->with('status', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product)
    {
        $categoryGroups = $this->categoryGroups();
        $brands = $this->brands();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categoryGroups', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        if ($request->hasFile('main_image')) {
            if ($product->main_image && !str_starts_with($product->main_image, 'http')) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = app(\App\Services\ImageOptimizer::class)->store($request->file('main_image'), 'products');
        }
        
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = json_decode($data['attributes'], true);
        }

        $product->update($data);

        $this->storeImages($request, $product);

        return redirect()->route('admin.products.index')->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image && !str_starts_with($product->main_image, 'http')) {
            Storage::disk('public')->delete($product->main_image);
        }

        foreach ($product->images as $image) {
            if (! str_starts_with($image->image, 'http')) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted successfully.');
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        abort_if($image->product_id !== $product->id, 404);

        if (! str_starts_with($image->image, 'http')) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return back()->with('status', 'Image removed.');
    }

    private function categoryGroups()
    {
        return Category::topLevel()->with('children')->orderBy('sort_order')->get();
    }

    private function brands()
    {
        return Brand::orderBy('name')->get();
    }

    private function storeImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $startOrder = $product->images()->max('sort_order') + 1;

        foreach ($request->file('images') as $index => $file) {
            $product->images()->create([
                'image' => app(\App\Services\ImageOptimizer::class)->store($file, 'products'),
                'sort_order' => $startOrder + $index,
            ]);
        }
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'old_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['nullable', 'string', 'max:100'],
            'weight' => ['nullable', 'string', 'max:100'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'reviews_count' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_best_seller' => ['sometimes', 'boolean'],
            'is_new_arrival' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'main_image' => ['nullable', 'image', 'max:2048'],
            'attributes' => ['nullable'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'mimes:jpeg,jpg,png,webp,gif,mp4,mov,webm,ogv,ogg,m4v,avi,mkv,3gp', 'max:20480'],
        ]);

        foreach (['is_featured', 'is_best_seller', 'is_new_arrival', 'is_active'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        unset($data['images']);

        return $data;
    }
}
