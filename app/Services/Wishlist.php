<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class Wishlist
{
    private const SESSION_KEY = 'wishlist';

    public function toggle(Product $product): bool
    {
        $ids = $this->raw();

        if (in_array($product->id, $ids, true)) {
            $ids = array_values(array_diff($ids, [$product->id]));
            session([self::SESSION_KEY => $ids]);

            return false;
        }

        $ids[] = $product->id;
        session([self::SESSION_KEY => $ids]);

        return true;
    }

    public function remove(Product $product): void
    {
        $ids = array_values(array_diff($this->raw(), [$product->id]));
        session([self::SESSION_KEY => $ids]);
    }

    public function has(int $productId): bool
    {
        return in_array($productId, $this->raw(), true);
    }

    public function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function items(): Collection
    {
        $ids = $this->raw();

        if (empty($ids)) {
            return collect();
        }

        return Product::with(['category', 'images'])->whereIn('id', $ids)->get();
    }

    public function count(): int
    {
        return count($this->raw());
    }
}
