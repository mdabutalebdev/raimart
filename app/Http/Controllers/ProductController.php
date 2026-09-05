<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'images', 'brand']);

        $reviews = $product->reviews()->approved()->latest()->get();

        $ratingBreakdown = [5, 4, 3, 2, 1];
        $ratingCounts = collect($ratingBreakdown)->mapWithKeys(fn ($star) => [
            $star => $reviews->where('rating', $star)->count(),
        ]);

        $relatedProducts = Product::with(['category', 'images'])
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(5)
            ->get();

        return view('product-details', compact('product', 'relatedProducts', 'reviews', 'ratingCounts'));
    }
}
