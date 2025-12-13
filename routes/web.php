<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreatmentController;

// 1. Route Home (WAJIB ADA ->name('home'))
Route::get("/", function () {
    return view("landing.index"); // Pastikan ini sesuai nama file view utama Anda
})->name("home");

// 2. Route Halaman Produk
Route::get("/produk", function () {
    return view("landing.pages.semua-produk");
})->name("produk");

Route::get("/artikel", function () {
    return view("landing.pages.semua-artikel");
})->name("artikel-index");

Route::get("/perawatan", function () {
    return view("landing.pages.perawatan");
})->name("perawatan.index");

Route::get("/perawatan/{slug}", [TreatmentController::class, "show"])->name(
    "perawatan.show",
);
