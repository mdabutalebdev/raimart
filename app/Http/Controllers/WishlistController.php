<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Wishlist;
use App\Support\Ga4;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Wishlist $wishlist)
    {
        $items = $wishlist->items();

        return view('wishlist', compact('items'));
    }

    public function toggle(Request $request, Wishlist $wishlist, Product $product)
    {
        $inWishlist = $wishlist->toggle($product);

        if ($request->wantsJson()) {
            return response()->json([
                'in_wishlist' => $inWishlist,
                'count' => $wishlist->count(),
                // Only report to GA4 when the item was just ADDED, not removed.
                'ga' => $inWishlist ? [
                    'event' => 'add_to_wishlist',
                    'ecommerce' => [
                        'currency' => Ga4::currency(),
                        'value' => round((float) $product->price, 2),
                        'items' => [Ga4::item($product)],
                    ],
                ] : null,
            ]);
        }

        return back()->with('status', $inWishlist ? "{$product->name} added to wishlist." : "{$product->name} removed from wishlist.");
    }

    public function remove(Wishlist $wishlist, Product $product)
    {
        $wishlist->remove($product);

        return back()->with('status', 'Item removed from wishlist.');
    }
}
