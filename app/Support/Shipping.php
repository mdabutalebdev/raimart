<?php

namespace App\Support;

use App\Models\SiteSetting;

/**
 * Delivery charges, all admin-managed (Admin → Delivery Charge):
 * a rate for inside Dhaka, another for outside, and an optional order value
 * above which delivery becomes free.
 */
class Shipping
{
    public const DEFAULT_AREA = 'inside';

    /** ['inside' => ['label' => …, 'fee' => …], 'outside' => …] */
    public static function areas(): array
    {
        return [
            'inside' => [
                'label' => SiteSetting::get('shipping_inside_label') ?: 'Inside Dhaka',
                'fee' => (float) (SiteSetting::get('shipping_inside_fee') ?? 60),
            ],
            'outside' => [
                'label' => SiteSetting::get('shipping_outside_label') ?: 'Outside Dhaka',
                'fee' => (float) (SiteSetting::get('shipping_outside_fee') ?? 120),
            ],
        ];
    }

    public static function label(string $area): string
    {
        return self::areas()[self::normalise($area)]['label'];
    }

    /** Order value that unlocks free delivery, or null when the offer is off. */
    public static function threshold(): ?float
    {
        $value = (float) (SiteSetting::get('free_shipping_threshold') ?? 0);

        return $value > 0 ? $value : null;
    }

    public static function qualifiesForFree(float $subtotal): bool
    {
        $threshold = self::threshold();

        return $threshold !== null && $subtotal >= $threshold;
    }

    /** How much more the customer must add to get free delivery (0 when done/off). */
    public static function remainingForFree(float $subtotal): float
    {
        $threshold = self::threshold();

        if ($threshold === null || $subtotal >= $threshold) {
            return 0.0;
        }

        return round($threshold - $subtotal, 2);
    }

    /** 0–100 progress towards the free delivery threshold. */
    public static function freeProgress(float $subtotal): int
    {
        $threshold = self::threshold();

        if ($threshold === null || $threshold <= 0) {
            return 0;
        }

        return (int) min(100, round($subtotal / $threshold * 100));
    }

    /** The delivery charge for this area and cart value. */
    public static function fee(?string $area, float $subtotal, bool $freeShippingCoupon = false): float
    {
        if ($freeShippingCoupon || self::qualifiesForFree($subtotal)) {
            return 0.0;
        }

        return self::areas()[self::normalise($area)]['fee'];
    }

    public static function normalise(?string $area): string
    {
        return in_array($area, ['inside', 'outside'], true) ? $area : self::DEFAULT_AREA;
    }
}
