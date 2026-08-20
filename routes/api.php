<?php

use App\Http\Controllers\Api\TripayCallbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('tripay/callback', [TripayCallbackController::class, 'handle'])->name('tripay.callback');
