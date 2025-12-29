<?php

namespace App\Services;

use Illuminate\Support\Collection;

class ProductService
{
    // Data disesuaikan dengan List Harga WhatsApp (Real Data)
    // Image menggunakan placeholder (produk1 s/d produk4) secara bergantian
    private array $products = [
        [
            'id' => 1,
            'slug' => 'skinlogic-facewash',
            'name' => 'Skinlogic Facewash',
            'category' => 'Cleanser',
            'price' => 60000,
            'image' => 'produk1.jpeg', 
            'description' => 'Sabun pembersih wajah yang lembut, efektif mengangkat kotoran dan sisa makeup tanpa membuat kulit terasa kering atau ketarik.',
            'benefits' => ['Membersihkan Pori', 'Tidak Membuat Kering', 'pH Balance']
        ],
        [
            'id' => 2,
            'slug' => 'skinlogic-toner',
            'name' => 'Skinlogic Toner',
            'category' => 'Toner',
            'price' => 60000,
            'image' => 'produk2.jpeg',
            'description' => 'Toner penyegar yang membantu mengembalikan keseimbangan pH kulit setelah mencuci muka dan mempersiapkan kulit menerima skincare selanjutnya.',
            'benefits' => ['Menyegarkan', 'Melembabkan', 'Menyeimbangkan pH']
        ],
        [
            'id' => 3,
            'slug' => 'serum-aloe-vera',
            'name' => 'Serum Aloe Vera',
            'category' => 'Serum',
            'price' => 70000,
            'image' => 'produk3.jpeg',
            'description' => 'Serum dengan ekstrak Aloe Vera murni untuk menenangkan kulit kemerahan, melembabkan, dan meredakan iritasi ringan.',
            'benefits' => ['Soothing', 'Calming', 'Melembabkan Ekstra']
        ],
        [
            'id' => 4,
            'slug' => 'sunscreen-peachglow',
            'name' => 'Sunscreen Peachglow',
            'category' => 'Protection',
            'price' => 120000,
            'image' => 'produk4.jpeg',
            'description' => 'Tabir surya dengan efek tone-up alami (Peach) yang membuat wajah tampak cerah merona seketika sekaligus melindungi dari sinar UV.',
            'benefits' => ['UV Protection', 'Efek Glowing Merona', 'Tidak Lengket']
        ],
        [
            'id' => 5,
            'slug' => 'sunscreen-snowwhite',
            'name' => 'Sunscreen Snowwhite',
            'category' => 'Protection',
            'price' => 120000,
            'image' => 'produk5.jpeg',
            'description' => 'Tabir surya dengan finish mencerahkan (Brightening) untuk tampilan kulit yang lebih putih bersih dan terlindungi maksimal.',
            'benefits' => ['UV Protection', 'Efek Mencerahkan', 'Ringan di Wajah']
        ],
        [
            'id' => 6,
            'slug' => 'night-cream-retinol-c',
            'name' => 'Night Cream Retinol-C',
            'category' => 'Night Cream',
            'price' => 120000,
            'image' => 'produk6.jpeg',
            'description' => 'Krim malam dengan kombinasi Retinol dan Vitamin C untuk anti-aging, memudarkan garis halus, dan mencerahkan noda hitam.',
            'benefits' => ['Anti Aging', 'Mencerahkan', 'Regenerasi Kulit']
        ],
        [
            'id' => 7,
            'slug' => 'night-cream-acne',
            'name' => 'Night Cream Acne',
            'category' => 'Night Cream',
            'price' => 120000,
            'image' => 'produk7.jpeg',
            'description' => 'Krim malam formulasi khusus untuk kulit berjerawat. Membantu mengeringkan jerawat aktif dan mencegah timbulnya jerawat baru.',
            'benefits' => ['Mengeringkan Jerawat', 'Kontrol Minyak', 'Anti Bakteri']
        ],
        [
            'id' => 8,
            'slug' => 'serum-retinol-intensif',
            'name' => 'Serum Retinol Intensif',
            'category' => 'Serum',
            'price' => 150000,
            'image' => 'produk8.jpeg',
            'description' => 'Serum anti-aging potent untuk mempercepat pergantian sel kulit, menyamarkan kerutan, dan memperbaiki tekstur kulit.',
            'benefits' => ['Menyamarkan Kerutan', 'Memperbaiki Tekstur', 'Kencangkan Kulit']
        ],
        [
            'id' => 9,
            'slug' => 'serum-succinic-acid',
            'name' => 'Serum Succinic Acid',
            'category' => 'Serum',
            'price' => 150000,
            'image' => 'produk1.jpeg',
            'description' => 'Serum eksfoliasi lembut yang efektif membersihkan pori-pori tersumbat, komedo, dan aman untuk kulit sensitif berjerawat.',
            'benefits' => ['Atasi Komedo', 'Bersihkan Pori', 'Gentle Exfoliation']
        ],
        [
            'id' => 10,
            'slug' => 'paket-lengkap-glowing',
            'name' => 'Paket Lengkap Glowing (5 Item)',
            'category' => 'Paket Hemat',
            'price' => 350000,
            'image' => 'produk2.jpeg', // Gambar mewakili paket
            'description' => 'Paket hemat lebih murah! Dapatkan 5 item sekaligus: Facewash, Toner, Serum Aloe, Sunscreen, dan Night Cream.',
            'benefits' => ['Lebih Hemat', 'Perawatan Lengkap', 'Hasil Maksimal']
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

    public function getRelatedProducts(string $currentSlug, int $limit = 4): Collection
    {
        return collect($this->products)
            ->where('slug', '!=', $currentSlug)
            ->take($limit);
    }
}