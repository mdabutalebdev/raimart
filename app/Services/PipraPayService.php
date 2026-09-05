<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PipraPayService
{
    private string $baseUrl;

    private string $apiKey;

    private string $currency;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.piprapay.base_url'), '/');
        $this->apiKey = config('services.piprapay.api_key');
        $this->currency = config('services.piprapay.currency', 'BDT');
    }

    public function isConfigured(): bool
    {
        return filled($this->apiKey);
    }

    /**
     * Create a payment charge and return the response array from PipraPay.
     */
    public function createCharge(array $data): array
    {
        $response = Http::withoutVerifying()->withHeaders([
            'MHS-PIPRAPAY-API-KEY' => $this->apiKey,
        ])->post("{$this->baseUrl}/api/checkout/redirect", [
            'full_name' => $data['full_name'],
            'email_address' => $data['email_address'],
            'mobile_number' => $data['mobile_number'],
            'amount' => (string) $data['amount'],
            'currency' => $this->currency,
            'metadata' => json_encode($data['metadata'] ?? []),
            'return_url' => $data['return_url'],
            'webhook_url' => $data['webhook_url'],
        ]);

        return $response->json() ?? ['status' => false, 'message' => 'Invalid response from payment gateway.'];
    }

    /**
     * Verify a payment by its pp_id. Always re-check with the gateway rather than
     * trusting the webhook/redirect payload, since PipraPay does not sign requests.
     */
    public function verifyPayment(string $ppId): array
    {
        $response = Http::withoutVerifying()->withHeaders([
            'MHS-PIPRAPAY-API-KEY' => $this->apiKey,
        ])->post("{$this->baseUrl}/api/verify-payment", [
            'pp_id' => $ppId,
        ]);

        return $response->json() ?? ['status' => false, 'message' => 'Invalid response from payment gateway.'];
    }

    /**
     * Refund a payment.
     */
    public function refundPayment(string $ppId): array
    {
        $response = Http::withoutVerifying()->withHeaders([
            'MHS-PIPRAPAY-API-KEY' => $this->apiKey,
        ])->post("{$this->baseUrl}/api/refund-payment", [
            'pp_id' => $ppId,
        ]);

        return $response->json() ?? ['status' => false, 'message' => 'Invalid response from payment gateway.'];
    }
}
