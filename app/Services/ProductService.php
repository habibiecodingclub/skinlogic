<?php

namespace App\Services;

use Illuminate\Support\Collection;

class ProductService
{
    // HANYA BOLEH STRING BIASA, JANGAN PAKAI asset() DI SINI
    private array $products = [
        [
            'id' => 1,
            'slug' => 'glow-revitalizing-serum',
            'name' => 'Glow Revitalizing Serum',
            'category' => 'Serum',
            'price' => 150000,
            'image' => 'produk1.jpeg', // Cukup nama file saja
            'description' => 'Serum wajah dengan ekstrak vitamin C murni untuk mencerahkan kulit kusam dan memudarkan noda hitam dalam 14 hari.',
            'benefits' => ['Mencerahkan Wajah', 'Anti-aging', 'Menghilangkan Bekas Jerawat']
        ],
        [
            'id' => 2,
            'slug' => 'daily-hydrating-toner',
            'name' => 'Daily Hydrating Toner',
            'category' => 'Toner',
            'price' => 85000,
            'image' => 'produk2.jpeg', // Cukup nama file saja
            'description' => 'Toner ringan bebas alkohol yang menyeimbangkan pH kulit dan memberikan kesegaran instan setelah mencuci muka.',
            'benefits' => ['Menyeimbangkan pH', 'Menyegarkan', 'Melembabkan']
        ],
        [
            'id' => 3,
            'slug' => 'night-repair-cream',
            'name' => 'Night Repair Cream',
            'category' => 'Cream',
            'price' => 120000,
            'image' => 'produk3.jpeg', // Cukup nama file saja
            'description' => 'Krim malam intensif yang bekerja memperbaiki skin barrier yang rusak saat kamu tidur.',
            'benefits' => ['Memperbaiki Skin Barrier', 'Nutrisi Malam', 'Kulit Kenyal']
        ],
        [
            'id' => 4,
            'slug' => 'sunscreen-protection',
            'name' => 'UV Shield Sunscreen',
            'category' => 'Protection',
            'price' => 95000,
            'image' => 'produk4.jpeg', // Cukup nama file saja
            'description' => 'Perlindungan maksimal SPF 50 PA++++ tanpa whitecast, cocok untuk semua jenis kulit.',
            'benefits' => ['SPF 50 PA++++', 'Tanpa Whitecast', 'Ringan']
        ],
    ];

    public function getAllProducts(): Collection
    {
        return collect($this->products);
    }

    public function getProductBySlug(string $slug)
    {
        return collect($this->products)->firstWhere('slug', $slug);
    }

    public function getRelatedProducts(string $currentSlug, int $limit = 3): Collection
    {
        return collect($this->products)
            ->where('slug', '!=', $currentSlug)
            ->take($limit);
    }
}