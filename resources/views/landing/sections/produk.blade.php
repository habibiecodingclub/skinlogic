@php
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

<section class="py-16 bg-white">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold font-poppins text-slate-900">Produk Eksklusif</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($products as $product)
                @include('landing.components.product-card', [
                    'image' => $product['image'],
                    'name' => $product['name'],
                    'category' => $product['category'],
                    'price' => $product['price'],
                ])
            @endforeach
        </div>
    </div>
</section>
