<?php

use App\Livewire\Admin\AlbumManager;
use App\Livewire\Admin\PhotoUploadComponent;
use App\Livewire\Admin\WatermarkSettingComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected with Auth & Role Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard Main View
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD Album
    Route::get('/albums', AlbumManager::class)->name('albums');

    // Bulk Upload Photo & Watermarking
    Route::get('/photos/upload', PhotoUploadComponent::class)->name('photos.upload');

    // Watermark Setting
    Route::get('/watermark', WatermarkSettingComponent::class)->name('watermark');

    // Route Transaksi/Pesanan (Persiapan Modul Selanjutnya)
    Route::get('/orders', function () {
        return view('admin.orders.index');
    })->name('orders');
});
