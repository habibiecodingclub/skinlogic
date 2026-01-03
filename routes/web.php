<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CartController;


// 1. Route Home
Route::get('/', [ArticleController::class, 'home'])->name('home');

// 2. Route Halaman Produk
Route::get("/produk", [ProductController::class, "index"])->name("produk.index");
Route::get("/produk/{slug}", [ProductController::class, "show"])->name("produk.show");

// 3. Route Perawatan
Route::get("/perawatan", function () {
    return view("landing.pages.perawatan");
})->name("perawatan.index");

Route::get("/perawatan/{slug}", [TreatmentController::class, "show"])->name("perawatan.show");

// 4. Route Tentang Kami
Route::get("/tentang-kami", function () {
    return view("landing.pages.tentang-kami");
})->name("tentang-kami");

// 5. Artikel Routes - PERBAIKI DI SINI
Route::get('/artikel', [ArticleController::class, 'index'])->name('artikel.index');
Route::get('/artikel/kategori/{slug}', [ArticleController::class, 'category'])->name('artikel.category');
Route::get('/artikel/tag/{slug}', [ArticleController::class, 'tag'])->name('artikel.tag');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('artikel.show');


// Cart routes
Route::get('/cart/get', [CartController::class, 'getCart'])->name('cart.get');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/midtrans/callback', [CartController::class, 'callback'])->name('midtrans.callback');