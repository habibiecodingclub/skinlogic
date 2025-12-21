<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// === API RESERVASI ===
Route::prefix('reservations')->group(function () {
    // 1. Ambil List Perawatan
    Route::get('/perawatans', [ReservationApiController::class, 'getPerawatans']);

    // 2. Cek Slot Waktu (Jam)
    Route::get('/available-slots', [ReservationApiController::class, 'getAvailableSlots']);

    // 3. Cek Terapis Tersedia (INI YANG PENTING & MUNGKIN HILANG)
    Route::get('/available-therapists', [ReservationApiController::class, 'getAvailableTherapists']);

    // 4. Simpan Reservasi Baru
    Route::post('/', [ReservationApiController::class, 'store']);

    // 5. Cek Detail Reservasi (Cek Status)
    Route::get('/{identifier}', [ReservationApiController::class, 'show']);
    
    // 6. History & Cancel (Opsional)
    Route::post('/history', [ReservationApiController::class, 'history']);
    Route::post('/cancel/{id}', [ReservationApiController::class, 'cancel']);
});