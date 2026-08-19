# Setup Project — Photo Selling Platform

## 1. Buat project Laravel baru

```bash
composer create-project laravel/laravel photo-platform
cd photo-platform
```

## 2. Install package yang dibutuhkan

```bash
# Livewire
composer require livewire/livewire

# Bootstrap 5 (via npm)
npm install bootstrap @popperjs/core sass --save-dev

# Watermark & image processing
composer require intervention/image

# Untuk generate signed URL & QR code (opsional, kalau butuh QRIS custom)
composer require simplesoftwareio/simple-qrcode

# HTTP client untuk Tripay (sudah built-in di Laravel via Illuminate\Support\Facades\Http)
```

## 3. Setup Bootstrap di resources/js/app.js dan resources/sass/app.scss

resources/sass/app.scss:
```scss
@import "bootstrap/scss/bootstrap";
```

resources/js/app.js:
```js
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
```

Lalu jalankan:
```bash
npm install
npm run dev
```

## 4. Config Filesystem (PENTING untuk proteksi foto asli)

Edit `config/filesystems.php`, tambahkan disk baru:

```php
'disks' => [
    // ... disk lain

    'originals' => [
        'driver' => 'local',
        'root' => storage_path('app/originals'), // TIDAK bisa diakses publik
        'visibility' => 'private',
    ],

    'watermarked' => [
        'driver' => 'local',
        'root' => storage_path('app/public/watermarked'),
        'url' => env('APP_URL').'/storage/watermarked',
        'visibility' => 'public',
    ],
],
```

```bash
mkdir -p storage/app/originals
php artisan storage:link
```

## 5. Environment Variables (.env)

Tambahkan konfigurasi berikut di `.env`:

```env
# Tripay
TRIPAY_MODE=sandbox
TRIPAY_API_KEY=
TRIPAY_PRIVATE_KEY=
TRIPAY_MERCHANT_CODE=
TRIPAY_CALLBACK_URL="${APP_URL}/callback/tripay"

# WhatsApp Gateway (pilih salah satu, sesuaikan nanti)
WA_GATEWAY_URL=
WA_GATEWAY_TOKEN=

# Signed URL expiry untuk link download (menit)
DOWNLOAD_LINK_EXPIRY=1440
```

## 6. Jalankan migration

Copy semua file dari folder `database/migrations/` ke project Anda, lalu:

```bash
php artisan migrate
```

## 7. Queue (wajib untuk proses pengiriman file)

```bash
# Di .env
QUEUE_CONNECTION=database

php artisan queue:table
php artisan migrate
php artisan queue:work
```

Proses generate watermark saat upload dan pengiriman email/WA setelah payment akan dijalankan lewat Job & Queue supaya tidak memblokir request utama (terutama penting untuk response webhook Tripay yang harus cepat).

---

Setelah struktur dasar ini jalan, langkah berikutnya: sistem watermark otomatis saat upload, lalu alur checkout + integrasi Tripay.
