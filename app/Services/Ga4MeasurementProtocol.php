<?php

namespace App\Services;

use App\Models\Order;
use App\Support\Ga4;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Sends GA4 events straight from the server via the Measurement Protocol.
 * Used for the `purchase` event so a confirmed order is always counted —
 * even if the browser is closed, navigates away, or an ad-blocker strips
 * the client-side GTM tag.
 *
 * @see https://developers.google.com/analytics/devguides/collection/protocol/ga4
 */
class Ga4MeasurementProtocol
{
    public function isConfigured(): bool
    {
        return filled(config('services.ga4.measurement_id'))
            && filled(config('services.ga4.api_secret'));
    }

    /**
     * Fire the GA4 `purchase` event for an order and stamp it so it can never
     * be sent twice (PipraPay callback + webhook, or a refresh, are safe).
     * Returns true if the event was sent.
     */
    public function purchase(Order $order): bool
    {
        if (! $this->isConfigured() || $order->purchase_tracked_at) {
            return false;
        }

        $order->loadMissing('items.product.brand', 'items.product.category');

        $params = array_filter([
            'transaction_id' => $order->order_number,
            'value' => round((float) $order->total, 2),
            'shipping' => round((float) $order->shipping_fee, 2),
            'tax' => 0,
            'currency' => Ga4::currency(),
            'items' => Ga4::orderItems($order),
        ], fn ($v) => $v !== null);

        if ($order->ga_session_id) {
            // Stitch the hit to the same web session the customer browsed in.
            $params['session_id'] = $order->ga_session_id;
            $params['engagement_time_msec'] = 1;
        }

        $sent = $this->send([
            'client_id' => $order->ga_client_id ?: $this->fallbackClientId($order),
            'events' => [[
                'name' => 'purchase',
                'params' => $params,
            ]],
        ]);

        if ($sent) {
            $order->forceFill(['purchase_tracked_at' => now()])->save();
        }

        return $sent;
    }

    private function send(array $payload): bool
    {
        $base = config('services.ga4.debug')
            ? 'https://www.google-analytics.com/debug/mp/collect'
            : 'https://www.google-analytics.com/mp/collect';

        try {
            $response = Http::asJson()->timeout(5)->post($base.'?'.http_build_query([
                'measurement_id' => config('services.ga4.measurement_id'),
                'api_secret' => config('services.ga4.api_secret'),
            ]), $payload);

            if (config('services.ga4.debug')) {
                // The /debug endpoint returns validationMessages instead of sending.
                Log::info('GA4 MP debug response', ['status' => $response->status(), 'body' => $response->json()]);
            }

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('GA4 Measurement Protocol failed', ['error' => $e->getMessage()]);

            return false;
        }
    }

    /** Stable fallback client_id when the browser _ga cookie wasn't captured. */
    private function fallbackClientId(Order $order): string
    {
        return 'srv.'.$order->id.'.'.substr(md5($order->order_number), 0, 12);
    }
}
