<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TripayService
{
    protected $baseUrl;
    protected $apiKey;
    protected $privateKey;
    protected $merchantCode;

    public function __construct()
    {
        $this->apiKey = config('services.tripay.api_key');
        $this->privateKey = config('services.tripay.private_key');
        $this->merchantCode = config('services.tripay.merchant_code');

        $isSandbox = config('services.tripay.is_sandbox');
        $this->baseUrl = $isSandbox
            ? 'https://tripay.co.id/api-sandbox/'
            : 'https://tripay.co.id/api/';
    }

    public function getPaymentChannels()
    {
        $response = Http::withToken($this->apiKey)
            ->get($this->baseUrl . 'merchant/payment-channel');

        return $response->json();
    }

    public function createTransaction($order, $method)
    {
        // 1. Pastikan amount berupa Integer bulat (tanpa desimal/koma)
        $amount = intval($order->total_amount);

        // 2. Format Signature Tripay: HMAC_SHA256(merchant_code + merchant_ref + amount, private_key)
        $signature = hash_hmac('sha256', $this->merchantCode . $order->order_number . $amount, $this->privateKey);

        $payload = [
            'method' => $method,
            'merchant_ref' => $order->order_number,
            'amount' => $amount,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'order_items' => [
                [
                    'name' => 'Foto Original (Tanpa Watermark)',
                    'price' => $amount,
                    'quantity' => 1,
                ]
            ],
            'callback_url' => route('tripay.callback'),
            'return_url' => route('checkout.success', $order->order_number),
            'expired_time' => (time() + (24 * 60 * 60)), // Expired 24 Jam
            'signature' => $signature,
        ];

        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl . 'transaction/create', $payload);

        return $response->json();
    }
}
