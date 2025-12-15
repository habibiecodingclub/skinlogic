{{-- resources/views/landing/pages/semua-produk.blade.php --}}
@extends('landing.index')

@section('content')
    @include('landing.sections.header')

    {{-- 1. HERO SECTION (Gaya Premium seperti Halaman Perawatan) --}}
    <section class="relative bg-gradient-to-br from-pink-50 via-white to-pink-50 pt-32 pb-20 -mt-6 overflow-hidden">
        {{-- Background Pattern/Image (Opsional) --}}
        <div class="absolute inset-0 opacity-50 pointer-events-none">
            {{-- Kalau punya gambar hero khusus produk, ganti url di bawah --}}
            <div class="absolute inset-0" style="background-image: url('{{ asset('images/herosection-on-detail.png') }}'); background-size: cover; background-position: center;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="text-pink-500 font-bold tracking-widest uppercase text-sm mb-2 block animate-fade-in-up">Skinlogic Collection</span>
            <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 font-poppins leading-tight">
                Koleksi Produk Eksklusif
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg mb-6 font-light">
                Temukan rangkaian perawatan kulit terbaik yang diformulasikan khusus untuk kebutuhan kulit Anda.
            </p>

            {{-- Breadcrumbs --}}
            <div class="flex items-center justify-center gap-2 text-sm font-medium text-gray-500">
                <a href="/" class="hover:text-pink-500 transition-colors">Home</a>
                <span>/</span>
                <span class="text-pink-500">Shop</span>
            </div>
        </div>
    </section>

    {{-- 2. PRODUCT GRID SECTION --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            {{-- Filter / Sort Header (Hiasan UI agar terlihat profesional) --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 pb-6 border-b border-gray-100">
                <p class="text-gray-500 text-sm mb-4 md:mb-0">
                    Menampilkan <span class="font-bold text-slate-900">{{ count($products) }}</span> produk terbaik
                </p>
                <div class="flex gap-4">
                    {{-- Tombol Filter Dummy (Visual Only) --}}
                    <button class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-full text-sm hover:border-pink-500 hover:text-pink-500 transition">
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

            {{-- 3. CTA SECTION (Bagian Bawah Grid) --}}
            <div class="mt-24 bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-10 md:p-16 text-center relative overflow-hidden shadow-2xl">
                {{-- Decorative Circle --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                
                <h3 class="text-3xl font-bold text-white mb-4 font-poppins relative z-10">
                    Bingung Memilih Produk?
                </h3>
                <p class="text-slate-300 mb-8 max-w-2xl mx-auto relative z-10 text-lg">
                    Konsultasikan masalah kulit Anda dengan dokter ahli kami untuk mendapatkan rekomendasi produk yang tepat.
                </p>
                <a href="https://wa.me/628123456789"
                   class="inline-flex items-center gap-3 bg-white text-slate-900 px-8 py-4 rounded-full font-bold hover:bg-pink-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 relative z-10">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.463 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    Chat WhatsApp
                </a>
            </div>

        </div>
    </section>
    @include('landing.sections.footer')
@endsection
