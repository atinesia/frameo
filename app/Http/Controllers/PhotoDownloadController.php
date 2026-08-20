<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PhotoDownloadController extends Controller
{
    public function download(Request $request, $orderId)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link download sudah tidak berlaku.');
        }

        $order = Order::with('items.photo')->findOrFail($orderId);

        if ($order->status !== 'paid') {
            abort(403, 'Pembayaran belum lunas.');
        }

        $items = $order->items;

        // Jika Pembelian Tunggal (1 Foto)
        if ($items->count() === 1) {
            $photo = $items->first()->photo;
            return Storage::disk('local')->download($photo->original_path, $photo->filename);
        }

        // Jika Pembelian Bundle (Banyak Foto -> Zip File)
        $zipFileName = 'album-photos-' . $order->order_number . '.zip';
        $zipPath = storage_path('app/private/temp/' . $zipFileName);

        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($items as $item) {
                $filePath = storage_path('app/' . $item->photo->original_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $item->photo->filename);
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
