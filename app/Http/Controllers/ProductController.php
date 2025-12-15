<?php

namespace App\Http\Controllers;

use App\Services\ProductService; // Pastikan baris ini ada
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    // Constructor untuk inject service
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // INI METHOD YANG HILANG (Penyebab Error)
    public function index()
    {
        $products = $this->productService->getAllProducts();
        
        // Pastikan view ini ada di: resources/views/landing/pages/semua-produk.blade.php
        return view('landing.pages.semua-produk', compact('products'));
    }

    // Method untuk detail produk
    public function show($slug)
    {
        $product = $this->productService->getProductBySlug($slug);

        if (!$product) {
            abort(404);
        }

        $relatedProducts = $this->productService->getRelatedProducts($slug);

        // Pastikan view ini ada di: resources/views/landing/pages/detail-produk.blade.php
        return view('landing.pages.detail-produk', compact('product', 'relatedProducts'));
    }
}