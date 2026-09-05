<?php

namespace App\Services;

use App\Models\Coupon;

/**
 * Keeps the customer's applied coupon in the session and works out the money off.
 * The coupon is re-validated on every read, so an expired or disabled code stops
 * applying even if it was added to the session earlier.
 */
class CouponService
{
    private const SESSION_KEY = 'coupon_code';

    public function code(): ?string
    {
        return session(self::SESSION_KEY);
    }

    /** The applied coupon, or null when none is applied / it is no longer usable. */
    public function coupon(): ?Coupon
    {
        $code = $this->code();

        if (! $code) {
            return null;
        }

        $coupon = Coupon::whereRaw('LOWER(code) = ?', [mb_strtolower($code)])->first();

        if (! $coupon || ! $coupon->isUsable()) {
            $this->clear();

            return null;
        }

        return $coupon;
    }

    /**
     * Try to apply a code. Returns [ok, message].
     */
    public function apply(string $code, float $subtotal): array
    {
        $coupon = Coupon::whereRaw('LOWER(code) = ?', [mb_strtolower(trim($code))])->first();

        if (! $coupon) {
            return [false, 'This coupon code is not valid.'];
        }

        if ($reason = $coupon->reasonUnusableFor($subtotal)) {
            return [false, $reason];
        }

        session([self::SESSION_KEY => $coupon->code]);

        return [true, "Coupon {$coupon->code} applied — {$coupon->label}."];
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /** Discount for the current cart, 0 when nothing applies. */
    public function discount(float $subtotal): float
    {
        $coupon = $this->coupon();

        if (! $coupon || $coupon->reasonUnusableFor($subtotal)) {
            return 0.0;
        }

        return $coupon->discountFor($subtotal);
    }

    /** Does the applied coupon also make delivery free? */
    public function givesFreeShipping(float $subtotal): bool
    {
        $coupon = $this->coupon();

        return $coupon && ! $coupon->reasonUnusableFor($subtotal) && $coupon->free_shipping;
    }
}
