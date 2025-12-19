<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationApiController;

Route::prefix('reservations')->group(function () {
    // Public API routes for frontend
    Route::get('/available-slots', [ReservationApiController::class, 'getAvailableSlots']);
    Route::get('/available-therapists', [ReservationApiController::class, 'getAvailableTherapists']);
    Route::get('/perawatans', [ReservationApiController::class, 'getPerawatans']);
    Route::post('/', [ReservationApiController::class, 'store']);
    Route::get('/history', [ReservationApiController::class, 'history']);
    Route::get('/{identifier}', [ReservationApiController::class, 'show']);
    Route::post('/{id}/cancel', [ReservationApiController::class, 'cancel']);

    // Optional: Add authentication for some routes
    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::get('/my-reservations', [ReservationApiController::class, 'myReservations']);
    // });
});
