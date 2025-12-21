@php
    $products = [
        [
            // HAPUS asset() dan 'images/', cukup nama file saja
            'image' => 'produk2.jpeg', 
            'name' => 'Paket Lengkap SkinLogic',
            'category' => 'Kecantikan',
            'price' => 350000,
            'slug' => 'paket-lengkap-glowing' // Tambahkan slug jika perlu
        ],
        [
            'image' => 'produk1.jpeg',
            'name' => 'Serum Retinol Intensif',
            'category' => 'Serum',
            'price' => 150000,
            'slug' => 'serum-retinol-intensif'
        ],
        [
            'image' => 'produk3.jpeg',
            'name' => 'Night Cream Acne',
            'category' => 'Cream',
            'price' => 120000,
            'slug' => 'night-cream-acne'
        ],
    ];
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold font-poppins text-slate-900">Produk Eksklusif</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($products as $product)
                @include('landing.components.product-card', [
                    {{-- Logic ini sudah benar: dia akan mengambil 'images/' + nama file dari array --}}
                    'image' => asset('images/' . ($product['image'] ?? 'default.jpg')),
                    
                    'name' => $product['name'] ?? 'Nama Produk',
                    'category' => $product['category'] ?? 'Umum',
                    'price' => $product['price'] ?? 0,
                    'slug' => $product['slug'] ?? 'demo-produk' 
                ])
            @endforeach
        </div>
    </div>
</section>