<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\DeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TripayCallbackController extends Controller
{
    public function handle(Request $request, DeliveryService $deliveryService)
    {
        $privateKey = config('services.tripay.private_key');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $privateKey);

        if ($signature !== $request->header('X-Callback-Signature')) {
            return response()->json(['success' => false, 'message' => 'Invalid Signature'], 400);
        }

        $data = json_decode($json);
        if ($data->status === 'PAID') {
            $order = Order::where('order_number', $data->merchant_ref)->first();
            if (! $order) {
                Log::warning('Tripay Callback Error: Order Not Found for Ref: ' . ($data->merchant_ref ?? 'EMPTY'));
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Jalankan update jika status belum paid
            if ($order->status !== 'paid') {
                $order->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
                //dd($order->toArray()); // Debugging: Log order details after update
                // Kirim foto otomatis via WA/Email
                $deliveryService->sendPhotoAccess($order);
                Log::info('Tripay Callback Success: Order ' . $order->order_number . ' marked as PAID.');
            }
        }

        return response()->json(['success' => true, 'message' => 'Callback processed successfully']);
    }
}
