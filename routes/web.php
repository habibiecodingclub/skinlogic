<?php

use App\Http\Controllers\LaporanStokController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/laporan/stok/cetak', [LaporanStokController::class, 'cetak'])
    ->name('laporan.stok.cetak');
