<?php

use App\Livewire\Admin\AlbumManager;
use App\Livewire\Admin\PhotoUploadComponent;
use App\Livewire\Admin\WatermarkSettingComponent;
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
/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Menjadikan AlbumManager sebagai Dashboard Admin Utama
    Route::get('/dashboard', AlbumManager::class)->name('dashboard');
    // CRUD Album
    Route::get('/albums', AlbumManager::class)->name('albums');

    // Bulk Upload Photo & Watermarking
    Route::get('/photos/upload', PhotoUploadComponent::class)->name('photos.upload');

    // Watermark Setting
    Route::get('/watermark', WatermarkSettingComponent::class)->name('watermark');
});

require __DIR__ . '/auth.php';
