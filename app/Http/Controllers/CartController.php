<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use App\Support\Ga4;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Cart $cart)
    {
        $items = $cart->items();
        $subtotal = $cart->subtotal();

        return view('cart', compact('items', 'subtotal'));
    }

    /** Rendered cart-items fragment for the slide-out drawer (AJAX). */
    public function partial(Cart $cart)
    {
        return response()->json($this->cartPayload($cart));
    }

    private function cartPayload(Cart $cart): array
    {
        return [
            'count' => $cart->count(),
            'subtotal' => $cart->subtotal(),
            'html' => view('partials.cart-items', [
                'items' => $cart->items(),
                'subtotal' => $cart->subtotal(),
            ])->render(),
        ];
    }

    public function add(Request $request, Cart $cart, Product $product)
    {
        $quantity = max(1, (int) $request->input('quantity', 1));
        
        $options = $request->input('options', []);
        
        // Validate options if product has attributes
        if ($product->attributes) {
            foreach ($product->attributes as $attribute) {
                $values = array_filter(array_map('trim', explode(',', $attribute['values'] ?? '')));
                if (count($values) > 0) {
                    if (empty($options[$attribute['name']])) {
                        return back()->withErrors(['options' => "Please select a {$attribute['name']}."]);
                    }
                }
            }
        }
        
        if (is_array($options)) {
            $options = array_filter($options);
        }

        $cart->add($product, $quantity, $options);

        if ($request->input('redirect') === 'checkout') {
            return redirect()->route('checkout.index');
        }

        if ($request->wantsJson()) {
            return response()->json($this->cartPayload($cart) + [
                'message' => "{$product->name} added to cart.",
                'ga' => [
                    'event' => 'add_to_cart',
                    'ecommerce' => [
                        'currency' => Ga4::currency(),
                        'value' => round((float) $product->price * $quantity, 2),
                        'items' => [Ga4::item($product, array_filter([
                            'quantity' => $quantity,
                            'item_variant' => Ga4::variant($options ?: []),
                        ], fn ($v) => $v !== null))],
                    ],
                ],
            ]);
        }

        return back()->with('status', "{$product->name} added to cart.");
    }

    public function update(Request $request, Cart $cart, string $id)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0']]);

        $cart->update($id, $data['quantity']);

        if ($request->wantsJson()) {
            return response()->json($this->cartPayload($cart));
        }

        return back()->with('status', 'Cart updated.');
    }

    public function remove(Request $request, Cart $cart, string $id)
    {
        // Grab the line before it's gone so we can report it to GA4.
        $removed = $cart->items()->firstWhere('id', $id);

        $cart->remove($id);

        if ($request->wantsJson()) {
            $payload = $this->cartPayload($cart);

            if ($removed) {
                $payload['ga'] = [
                    'event' => 'remove_from_cart',
                    'ecommerce' => [
                        'currency' => Ga4::currency(),
                        'value' => round((float) $removed->product->price * $removed->quantity, 2),
                        'items' => [Ga4::cartLine($removed)],
                    ],
                ];
            }

            return response()->json($payload);
        }

        return back()->with('status', 'Item removed from cart.');
    }
}
