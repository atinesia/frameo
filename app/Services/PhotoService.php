<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\WatermarkSetting;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function processAndSavePhoto($file, $albumId, $price)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // 1. Simpan Foto Asli ke Private Storage
        Storage::disk('local')->putFileAs('private/photos', $file, $filename);
        $originalPath = 'private/photos/' . $filename;

        // 2. Ambil Setting Watermark dari DB
        $wmSetting = WatermarkSetting::first();
        $image = $this->manager->read($file->getRealPath());

        if ($wmSetting && $wmSetting->image_path && Storage::disk('public')->exists($wmSetting->image_path)) {
            $watermark = $this->manager->read(Storage::disk('public')->path($wmSetting->image_path));

            // Hitung lebar watermark berdasarkan skala (%)
            $wmWidth = intval($image->width() * ($wmSetting->scale / 100));
            $watermark->scale(width: $wmWidth);

            // PERBAIKAN V3: Terapkan opacity langsung pada objek watermark (nilai 0.0 - 1.0)
            $opacityValue = floatval(($wmSetting->opacity ?? 50) / 100);
            $watermark->opacity($opacityValue);

            // Pemetaan Posisi
            $position = match ($wmSetting->position) {
                'top-left' => 'top-left',
                'top-right' => 'top-right',
                'bottom-left' => 'bottom-left',
                'bottom-right' => 'bottom-right',
                default => 'center',
            };

            // Tempel Watermark (tanpa parameter opacity di method place)
            $image->place(
                element: $watermark,
                position: $position,
                offset_x: 15,
                offset_y: 15
            );
        }

        // 3. Simpan Foto Ber-Watermark ke Public Storage
        $watermarkedPath = 'photos/watermarked/' . $filename;
        Storage::disk('public')->put($watermarkedPath, (string) $image->toJpeg(80));

        // 4. Return Data
        return [
            'album_id' => $albumId,
            'filename' => $filename,
            'original_path' => $originalPath,
            'watermark_path' => $watermarkedPath,
            'price' => $price,
            'code' => uniqid('photo_'),
        ];
    }
}
