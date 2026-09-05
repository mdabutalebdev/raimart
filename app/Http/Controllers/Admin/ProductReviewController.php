<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = ProductReview::with('product')
            ->when($request->status === 'pending', fn ($q) => $q->where('is_approved', false))
            ->when($request->status === 'approved', fn ($q) => $q->where('is_approved', true))
            ->when($request->search, fn ($q, $s) => $q->where(function ($sub) use ($s) {
                $sub->where('name', 'like', "%{$s}%")->orWhere('text', 'like', "%{$s}%");
            }))
            ->when($request->rating, fn ($q, $r) => $q->where('rating', $r))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'all' => ProductReview::count(),
            'pending' => ProductReview::where('is_approved', false)->count(),
            'approved' => ProductReview::where('is_approved', true)->count(),
        ];

        return view('admin.product-reviews.index', compact('reviews', 'counts'));
    }

    public function approve(ProductReview $review)
    {
        $review->update(['is_approved' => true]);
        $review->product?->refreshReviewStats();

        return back()->with('status', 'Review approved and now visible on the product page.');
    }

    public function reject(ProductReview $review)
    {
        $review->update(['is_approved' => false]);
        $review->product?->refreshReviewStats();

        return back()->with('status', 'Review hidden from the product page.');
    }

    public function destroy(ProductReview $review)
    {
        $product = $review->product;
        $review->delete();
        $product?->refreshReviewStats();

        return back()->with('status', 'Review deleted.');
    }
}
