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

        if ($wmSetting && $wmSetting->logo_path && Storage::disk('public')->exists($wmSetting->logo_path)) {
            $watermarkPath = Storage::disk('public')->path($wmSetting->logo_path);

            // Baca watermark & ubah ukurannya
            $watermark = $this->manager->read($watermarkPath);
            $wmWidth = intval($image->width() * ($wmSetting->scale / 100));
            $watermark->scale(width: $wmWidth);

            // MANIPULASI OPACITY VIA GD RESOURCE
            $opacity = intval($wmSetting->opacity ?? 50); // Nilai 1 - 100
            if ($opacity < 100) {
                $watermark = $this->applyOpacityGd($watermark, $opacity);
            }

            // Pemetaan Posisi
            $position = match ($wmSetting->position) {
                'top-left' => 'top-left',
                'top-right' => 'top-right',
                'bottom-left' => 'bottom-left',
                'bottom-right' => 'bottom-right',
                default => 'center',
            };

            // Tempel Watermark yang sudah transparan
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

    /**
     * Helper untuk menerapkan opacity pada objek Intervention Image v3 (GD Driver)
     */
    private function applyOpacityGd($imageInstance, $opacity)
    {
        // Encode sementara ke PNG untuk ambil GD resource
        $pngData = (string) $imageInstance->toPng();
        $src = imagecreatefromstring($pngData);

        $width = imagesx($src);
        $height = imagesy($src);

        // Buat canvas transparan baru
        $dst = imagecreatetruecolor($width, $height);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);

        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $width, $height, $transparent);

        // Hitung rasio opacity (0 - 1)
        $pct = $opacity / 100;

        // Loop setiap piksel untuk atur alpha channel
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($src, $x, $y);
                $alpha = ($color >> 24) & 0x7F;

                // Terapkan opacity baru ke piksel
                $newAlpha = 127 - intval((127 - $alpha) * $pct);

                $r = ($color >> 16) & 0xFF;
                $g = ($color >> 8) & 0xFF;
                $b = $color & 0xFF;

                $newColor = imagecolorallocatealpha($dst, $r, $g, $b, $newAlpha);
                imagesetpixel($dst, $x, $y, $newColor);
            }
        }

        // Simpan hasil ke output buffer & re-read ke Intervention Image
        ob_start();
        imagepng($dst);
        $resultData = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $this->manager->read($resultData);
    }
}
