<?php

namespace App\Support;

use App\Models\Banner;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Builds GA4 Enhanced Ecommerce "items" arrays from the app's own models, so
 * every event — a client-side dataLayer push or a server-side Measurement
 * Protocol call — speaks exactly the same shape.
 *
 * @see https://developers.google.com/analytics/devguides/collection/ga4/ecommerce
 */
class Ga4
{
    public static function currency(): string
    {
        return config('services.ga4.currency', 'BDT');
    }

    /** GA4 item_id: prefer the product SKU, fall back to a stable RM-{id}. */
    public static function itemId(Product $product): string
    {
        return $product->sku ?: 'RM-'.$product->id;
    }

    /**
     * Map a live Product to a GA4 item.
     * $extra overrides/adds keys (quantity, index, item_list_id, item_variant, ...).
     */
    public static function item(Product $product, array $extra = []): array
    {
        $price = round((float) $product->price, 2);
        $discount = ($product->old_price && (float) $product->old_price > $price)
            ? round((float) $product->old_price - $price, 2)
            : null;

        return self::clean([
            'item_id' => self::itemId($product),
            'item_name' => $product->name,
            'item_brand' => $product->brand?->name,
            'item_category' => $product->category?->name,
            'price' => $price,
            'discount' => $discount,
            'quantity' => 1,
        ], $extra);
    }

    /** One cart line (object with ->product, ->quantity, ->options) -> GA4 item. */
    public static function cartLine(object $line, array $extra = []): array
    {
        return self::item($line->product, self::clean([
            'quantity' => (int) $line->quantity,
            'item_variant' => self::variant($line->options ?? []),
        ], $extra));
    }

    /** A whole cart collection -> GA4 items (each tagged with its index). */
    public static function cartItems(Collection $lines): array
    {
        return $lines->values()
            ->map(fn ($line, $i) => self::cartLine($line, ['index' => $i]))
            ->all();
    }

    /**
     * One saved OrderItem -> GA4 item. Uses the order snapshot (name/price) and
     * enriches brand/category/sku from the product if it still exists.
     */
    public static function orderLine(OrderItem $oi, array $extra = []): array
    {
        $product = $oi->product;

        return self::clean([
            'item_id' => $product ? self::itemId($product) : 'RM-'.$oi->product_id,
            'item_name' => $oi->product_name,
            'item_brand' => $product?->brand?->name,
            'item_category' => $product?->category?->name,
            'price' => round((float) $oi->price, 2),
            'quantity' => (int) $oi->quantity,
            'item_variant' => self::variant($oi->options ?? []),
        ], $extra);
    }

    /** A list/collection of Products -> GA4 items (each tagged with its index). */
    public static function itemsFromProducts(iterable $products, array $extra = []): array
    {
        $items = [];
        $i = 0;

        foreach ($products as $product) {
            $items[] = self::item($product, ['index' => $i++] + $extra);
        }

        return $items;
    }

    /** A whole order -> GA4 items (each tagged with its index). */
    public static function orderItems(Order $order, array $extra = []): array
    {
        return $order->items->values()
            ->map(fn ($oi, $i) => self::orderLine($oi, ['index' => $i] + $extra))
            ->all();
    }

    /** Map a Banner to GA4 promotion params (for view_promotion / select_promotion). */
    public static function promotion(Banner $banner): array
    {
        return array_filter([
            'promotion_id' => (string) $banner->id,
            'promotion_name' => $banner->title ?: (ucfirst($banner->type).' banner'),
            'creative_name' => $banner->badge_text ?: null,
            'creative_slot' => $banner->type,
        ], fn ($v) => $v !== null && $v !== '');
    }

    /** Join the selected options into a single GA4 item_variant string. */
    public static function variant(array $options): ?string
    {
        $options = array_filter(array_map(
            fn ($v) => is_array($v) ? implode('/', $v) : trim((string) $v),
            $options
        ));

        return empty($options) ? null : implode(' / ', $options);
    }

    /** Drop null/'' values from $base, then let $extra override what remains. */
    private static function clean(array $base, array $extra = []): array
    {
        $filtered = array_filter($base, fn ($v) => $v !== null && $v !== '');

        return array_merge($filtered, $extra);
    }
}
