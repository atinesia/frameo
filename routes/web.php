<?php

use App\Http\Controllers\PhotoDownloadController;
use App\Livewire\Admin\AlbumManager;
use App\Livewire\Admin\BundleManager;
use App\Livewire\Admin\DashboardComponent;
use App\Livewire\Admin\PhotoUploadComponent;
use App\Livewire\Admin\WatermarkSettingComponent;
use App\Livewire\Public\CheckoutBundleComponent;
use App\Livewire\Public\CheckoutComponent;
use App\Livewire\Public\CheckoutSuccessComponent;
use App\Livewire\Public\GalleryComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect Logic
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::get('/', GalleryComponent::class)->name('home');

Route::get('/checkout/{photoId}', CheckoutComponent::class)->name('checkout');

// Secure Signed Route untuk Unduh Foto Asli
Route::get('/download/photo/{order}', [PhotoDownloadController::class, 'download'])
    ->name('photo.download');

Route::get('/checkout/success/{order_number}', CheckoutSuccessComponent::class)
    ->name('checkout.success');

Route::get('/checkout/bundle/{bundleId}', CheckoutBundleComponent::class)
    ->name('checkout.bundle');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin Utama
    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');
    // CRUD Album
    Route::get('/albums', AlbumManager::class)->name('albums');

    // Bulk Upload Photo & Watermarking
    Route::get('/photos/upload', PhotoUploadComponent::class)->name('photos.upload');

    // Watermark Setting
    Route::get('/watermark', WatermarkSettingComponent::class)->name('watermark');

    Route::get('/bundles', BundleManager::class)->name('bundles');
});

require __DIR__ . '/auth.php';
