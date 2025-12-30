{{-- resources/views/landing/pages/semua-produk.blade.php --}}
@extends('landing.index')

@section('content')
    @include('landing.sections.header')

    {{-- Hero Section Produk --}}
    <section class="relative bg-gradient-to-br from-blue-50 via-white to-blue-50 h-[420px] -mt-6 flex items-center overflow-hidden">

        <div class="absolute inset-0 opacity-75">
            <div class="absolute inset-0" style="background-image: url('{{ asset('images/herosection-on-detail.png') }}'); background-size: cover;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 w-full">
            <div class="text-center relative top-6 md:top-10">
                <h1 class="text-4xl md:text-5xl font-regular text-[#001a4d] mb-4 font-poppins hover:text-white transition-colors">
                    Produk
                </h1>
            </div>
        </div>
    </section>

    {{-- 2. PRODUCT GRID SECTION --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            {{-- Filter / Sort Header (Hiasan UI agar terlihat profesional) --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 pb-6 border-b border-gray-100">
                <p class="text-gray-500 font-poppins text-sm mb-4 md:mb-0">
                    Menampilkan <span class="font-bold text-slate-900">{{ count($products) }}</span> produk terbaik
                </p>
                <div class="flex gap-4">
                    {{-- Tombol Filter Dummy (Visual Only) --}}
                    <button class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-full text-sm font-poppins hover:border-pink-500 hover:text-pink-500 transition">
                        <span>Filter Category</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>
            </div>

            {{-- GRID PRODUK --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 gap-y-12">
                @foreach($products as $product)
                    <div class="transform hover:-translate-y-2 transition-transform duration-300">
                        @include('landing.components.product-card', [
                            'image' => asset('images/' . ($product['image'] ?? 'default.jpg')),
                            'name' => $product['name'] ?? 'Nama Produk',
                            'category' => $product['category'] ?? 'Skin Care',
                            'price' => $product['price'] ?? 0,
                            'slug' => $product['slug'] ?? 'demo-produk'
                        ])
                    </div>
                @endforeach
            </div>

            {{-- Final CTA --}}
        <section class="mt-20">
        <div class="bg-gradient-to-br from-[#001a4d] to-[#003d82] rounded-2xl p-12 text-center relative overflow-hidden">
            {{-- Decorative Elements --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-400 rounded-full opacity-10 -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-300 rounded-full opacity-10 translate-y-1/2 -translate-x-1/2"></div>

            <div class="relative z-10">
                <h3 class="text-3xl md:text-4xl font-bold text-white mb-4 font-poppins">
                    Siap Memulai Perjalanan Kulit Sehat?
                </h3>
                <p class="text-blue-100 mb-8 max-w-2xl mx-auto text-lg">
                    Konsultasikan kebutuhan kulit Anda dengan dokter spesialis kami dan dapatkan solusi terbaik yang dipersonalisasi
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <button onclick="window.dispatchEvent(new CustomEvent('open-reservation'))" 
                       class="inline-flex items-center gap-2 bg-white text-[#001a4d] px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Buat Janji Sekarang
                    </button>
                    <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan treatment"
                       target="_blank"
                       class="inline-flex items-center gap-2 bg-green-500 text-white px-8 py-4 rounded-full font-semibold hover:bg-green-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>
        </div>
    </section>
    @include('landing.sections.footer')
@endsection
