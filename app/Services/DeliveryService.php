<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class DeliveryService
{
    public function sendPhotoAccess(Order $order)
    {
        // 1. Generate Temporary Signed URL (Expired dalam 24 Jam)
        $downloadUrl = URL::temporarySignedRoute(
            'photo.download',
            now()->addHours(24),
            ['order' => $order->id]
        );

        // 2. Format Ulang Nomor WhatsApp agar Kompatibel dengan Fonnte (Hapus spasi, strip, plus)
        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);

        // Ubah format 08xx menjadi 628xx jika diperlukan oleh provider
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $waToken = config('services.fonnte.token');

        if ($waToken && $phone) {
            $message = "Halo *{$order->customer_name}*,\n\nTerima kasih! Pembayaran foto Anda telah kami terima.\n\nSilakan unduh foto resolusi tinggi tanpa watermark melalui link berikut (berlaku 24 jam):\n{$downloadUrl}\n\nSalam,\nStudio Photography";

            Log::info("token: {$waToken}, phone: {$phone}, message: {$message}");
            // Kirim HTTP Request ke Fonnte
            $response = Http::withHeaders([
                'Authorization' => $waToken,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Default Indonesia
            ]);

            // Cek Response dari Fonnte & Simpan di Log
            if ($response->successful()) {
                Log::info('WhatsApp Fonnte Success Sent to: ' . $phone, $response->json());
            } else {
                Log::error('WhatsApp Fonnte Failed to: ' . $phone, [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }
        } else {
            Log::warning('WhatsApp Fonnte Skipped: Token or Phone number missing.', [
                'has_token' => !empty($waToken),
                'phone'     => $phone,
            ]);
        }
    }
}
