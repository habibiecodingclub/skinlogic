<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Produk - SkinLogic</title>
    {{-- Pastikan baris ini sesuai dengan konfigurasi project Anda (Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">

    {{-- Header / Navbar --}}
    @include('landing.sections.header')

    <div class="pt-32 pb-12 px-6">
        <div class="container mx-auto max-w-6xl">
            <h1 class="text-4xl font-extrabold text-slate-900 text-center mb-12">Katalog Produk</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    // Data Dummy untuk halaman ini
                    $products = [
                        [
                            'image' => asset('images/produk1.jpeg'),
                            'name' => 'Acne Fighter Serum',
                            'category' => 'Serum Wajah',
                            'price' => 125000,
                        ],
                        [
                            'image' => asset('images/produk2.jpeg'),
                            'name' => 'Daily Gentle Cleanser',
                            'category' => 'Pembersih',
                            'price' => 85000,
                        ],
                        [
                            'image' => asset('images/produk3.jpeg'),
                            'name' => 'Hydrating Toner',
                            'category' => 'Toner',
                            'price' => 105000,
                        ],
                    ];
                @endphp

                @foreach($products as $product)
                    {{-- Menggunakan @include agar lebih aman --}}
                    @include('landing.components.product-card', [
                        'image' => $product['image'],
                        'name' => $product['name'],
                        'category' => $product['category'],
                        'price' => $product['price']
                    ])
                @endforeach
            </div>
        </div>
    </div>

</body>
</html>