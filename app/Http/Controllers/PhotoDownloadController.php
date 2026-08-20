<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class PhotoDownloadController extends Controller
{
    public function download(Request $request, $orderId)
    {
        // Validasi Signed URL (mencegah manipulasi URL)
        if (! $request->hasValidSignature()) {
            abort(401, 'Link download sudah tidak berlaku atau tidak valid.');
        }

        $order = Order::with('items.photo')->findOrFail($orderId);

        if ($order->status !== 'paid') {
            abort(403, 'Pembayaran belum diselesaikan.');
        }

        // Ambil foto pertama dari order
        $photo = $order->items->first()->photo;
        $originalFilePath = $photo->original_path;

        if (! Storage::disk('local')->exists($originalFilePath)) {
            abort(404, 'File foto asli tidak ditemukan di server.');
        }

        return Storage::disk('local')->download($originalFilePath, $photo->filename);
    }
}
