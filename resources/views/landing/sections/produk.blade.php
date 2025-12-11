{{-- Contoh Data Dummy (Biasanya data ini dikirim dari Controller) --}}
@php
    $products = [
        [
            'name' => 'Acne Fighter Serum',
            'category' => 'Serum Wajah',
            'price' => 125000,
            'description' => 'Formula khusus untuk meredakan jerawat aktif dan menyamarkan bekas luka dalam 7 hari pemakaian rutin.',
            'image' => 'https://via.placeholder.com/150' // Ganti dengan $product->image
        ],
        [
            'name' => 'Daily Gentle Cleanser',
            'category' => 'Face Wash',
            'price' => 85000,
            'description' => 'Pembersih wajah dengan pH seimbang yang lembut, cocok untuk kulit sensitif dan menjaga skin barrier.',
            'image' => 'https://via.placeholder.com/150'
        ],
        [
            'name' => 'Hydrating Toner',
            'category' => 'Toner',
            'price' => 105000,
            'description' => 'Mengembalikan kelembapan alami kulit dan mempersiapkan wajah untuk menyerap skincare selanjutnya.',
            'image' => 'https://via.placeholder.com/150'
        ],
    ];
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-6xl">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
            <div class="text-center md:text-left w-full md:w-auto">
                <span class="block text-xs font-bold tracking-widest text-gray-500 uppercase mb-2">
                    PRODUK KAMI
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                    Pilihan Skincare Terbaik
                </h2>
            </div>
            
            {{-- Tombol Lihat Semua (Hidden di mobile, muncul di desktop) --}}
            <a href="#" class="hidden md:flex items-center text-sm font-semibold text-slate-700 hover:text-blue-600 transition-colors mt-4 md:mt-0">
                LIHAT SEMUA 
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        {{-- Grid Produk  tessssss--}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @foreach($products as $product)
            <div class="group bg-white border border-gray-100 rounded-2xl p-8 flex flex-col items-center text-center transition-all duration-300 hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:-translate-y-1">
                
                {{-- Lingkaran Gambar --}}
                <div class="relative w-40 h-40 mb-6 rounded-full overflow-hidden bg-gray-50 group-hover:scale-105 transition-transform duration-500">
                    {{-- Ganti src dengan {{ asset('storage/' . $product->image) }} jika pakai storage --}}
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                </div>

                {{-- Konten Teks --}}
                <h3 class="text-xl font-bold text-slate-900 mb-1">
                    {{ $product['name'] }}
                </h3>
                <span class="text-sm font-medium text-gray-400 mb-4">
                    {{ $product['category'] }}
                </span>
                <p class="text-sm text-gray-500 leading-relaxed mb-6 line-clamp-3">
                    {{ $product['description'] }}
                </p>

                {{-- Harga --}}
                <div class="text-lg font-bold text-slate-900 mb-6">
                    Rp {{ number_format($product['price'], 0, ',', '.') }}
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-6 mt-auto">
                    <a href="#" class="flex items-center text-sm font-bold text-slate-800 hover:text-blue-600 transition-colors group-hover:underline decoration-2 underline-offset-4">
                        Beli Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    
                    <a href="#" class="flex items-center text-sm font-bold text-slate-800 hover:text-blue-600 transition-colors">
                        Detail
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach

        </div>

        {{-- Tombol Lihat Semua (Muncul di mobile di bawah grid) --}}
        <div class="mt-8 text-center md:hidden">
            <a href="#" class="inline-flex items-center text-sm font-bold text-slate-700">
                LIHAT SEMUA 
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

    </div>
</section>