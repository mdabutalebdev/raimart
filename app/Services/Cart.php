<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    private const SESSION_KEY = 'cart_items';

    public function add(Product $product, int $quantity = 1, array $options = []): void
    {
        $items = $this->raw();
        
        ksort($options);
        $cartItemId = $product->id . '_' . md5(json_encode($options));
        
        if (isset($items[$cartItemId])) {
            $items[$cartItemId]['quantity'] += $quantity;
        } else {
            $items[$cartItemId] = [
                'id' => $cartItemId,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'options' => $options
            ];
        }
        
        session([self::SESSION_KEY => $items]);
    }

    public function update(string $cartItemId, int $quantity): void
    {
        $items = $this->raw();

        if (isset($items[$cartItemId])) {
            if ($quantity <= 0) {
                unset($items[$cartItemId]);
            } else {
                $items[$cartItemId]['quantity'] = $quantity;
            }
            session([self::SESSION_KEY => $items]);
        }
    }

    public function remove(string $cartItemId): void
    {
        $items = $this->raw();
        if (isset($items[$cartItemId])) {
            unset($items[$cartItemId]);
            session([self::SESSION_KEY => $items]);
        }
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function items(): Collection
    {
        $raw = $this->raw();

        if (empty($raw)) {
            return collect();
        }

        $productIds = collect($raw)->pluck('product_id')->unique()->toArray();
        $products = Product::with('images')->whereIn('id', $productIds)->get()->keyBy('id');

        return collect($raw)->map(function ($item) use ($products) {
            $product = $products->get($item['product_id']);
            if (!$product) {
                return null;
            }
            
            return (object) [
                'id' => $item['id'],
                'product' => $product,
                'quantity' => $item['quantity'],
                'options' => $item['options'],
                'subtotal' => $product->price * $item['quantity'],
            ];
        })->filter();
    }

    public function count(): int
    {
        return collect($this->raw())->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }
}
