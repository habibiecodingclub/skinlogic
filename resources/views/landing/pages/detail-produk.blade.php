{{-- resources/views/landing/pages/detail-produk.blade.php --}}
@extends('landing.index')

@section('content')
    @include('landing.sections.header')
    
    {{-- Hero Section with Breadcrumb --}}
    <section class="relative bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-[70vh] -mt-6 flex items-center">
        <div class="absolute inset-0 opacity-75">
            <div class="absolute inset-0" style="background-image: url('{{ asset('images/herosection-on-detail.png') }}'); background-size: cover;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-regular text-gray-900 mb-4 font-poppins hover:text-blue-600 transition-colors">
                    {{ $product['category'] }}
                </h1>
            </div>
        </div>
    </section>

    {{-- 2. PRODUCT DETAIL SECTION --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            {{-- Grid Layout: Content Left, Image Right --}}
            <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">

                {{-- LEFT SIDE: INFO & ACTION --}}
                <div class="space-y-8 order-2 lg:order-1">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-2 font-poppins">Deskripsi Produk</h2>
                        <div class="w-20 h-1 bg-pink-500 rounded-full mb-6"></div>
                        
                        <p class="text-gray-600 leading-relaxed text-lg mb-6">
                            {{ $product['description'] }}
                        </p>
                    </div>

                    {{-- Price Card --}}
                    <div class="bg-gradient-to-r from-pink-50 to-white p-6 rounded-2xl border border-pink-100 flex items-center justify-between shadow-sm">
                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-1">Harga Resmi</p>
                            <h3 class="text-3xl font-bold text-slate-900 font-poppins">
                                Rp {{ number_format($product['price'], 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center text-pink-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10v10H7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 16l-4-4-4 4"/></svg>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        
                        {{-- =================================================== --}}
                        {{-- UPDATE: Tombol Beli Sekarang (Design Tetap Sama) --}}
                        {{-- =================================================== --}}
                        <button onclick="addToCart('{{ $product['slug'] }}')" 
                                id="btn-add-to-cart"
                                class="flex-1 inline-flex justify-center items-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-slate-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 font-poppins">
                            
                            {{-- Icon Cart --}}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            
                            {{-- Text --}}
                            <span id="btn-text">Beli Sekarang</span>

                            {{-- Loading Spinner (Hidden by default) --}}
                            <svg id="btn-loading" class="hidden w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>

                        {{-- Button WhatsApp --}}
                        <a href="https://wa.me/628123456789?text=Halo%20kak,%20saya%20mau%20pesan%20{{ $product['name'] }}" 
                           target="_blank"
                           class="flex-1 inline-flex justify-center items-center gap-2 bg-green-500 text-white px-8 py-4 rounded-xl font-semibold hover:bg-green-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 font-poppins">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Order via WhatsApp
                        </a>
                    </div>
                </div>

                {{-- RIGHT SIDE: IMAGE --}}
                <div class="relative order-1 lg:order-2 group">
                    {{-- Decorative Blob Background --}}
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-pink-100 rounded-full blur-3xl opacity-50 -z-10"></div>
                    
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                        <img src="{{ asset('images/' . $product['image']) }}"
                             alt="{{ $product['name'] }}"
                             class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700">
                        
                        {{-- Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent pointer-events-none"></div>
                    </div>

                    {{-- Floating Badge --}}
                    <div class="absolute -bottom-6 -left-6 bg-white py-4 px-6 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce-slow">
                        <div class="bg-green-100 p-2 rounded-full text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Status</p>
                            <p class="text-sm font-bold text-slate-900">Ready Stock</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 3. BENEFITS SECTION (Mirip Treatment Detail) --}}
            <div class="bg-gradient-to-br from-slate-50 to-white rounded-3xl p-8 md:p-12 mb-20 border border-slate-100">
                <h3 class="text-2xl md:text-3xl font-bold text-slate-900 mb-8 font-poppins text-center">
                    Manfaat Utama
                </h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($product['benefits'] as $benefit)
                    <div class="flex items-start gap-4 bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
                        <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-slate-700 font-medium pt-2">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 4. RELATED PRODUCTS (Menggunakan Component Product Card) --}}
            @if(count($relatedProducts) > 0)
            <div class="mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-slate-900 mb-4 font-poppins">Produk Sejenis</h3>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Lengkapi perawatan kulit Anda dengan produk rekomendasi lainnya
                    </p>
                </div>

                {{-- Disini kita tetap pakai Product Card Component --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $related)
                        @include('landing.components.product-card', [
                            'image' => asset('images/' . $related['image']),
                            'name' => $related['name'],
                            'category' => $related['category'],
                            'price' => $related['price'],
                            'slug' => $related['slug']
                        ])
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 5. FINAL CTA (Sama seperti Treatment) --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-12 text-center relative overflow-hidden shadow-2xl">
                {{-- Decorative Elements --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-pink-500 rounded-full opacity-10 -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
                
                <div class="relative z-10">
                    <h3 class="text-3xl md:text-4xl font-bold text-white mb-4 font-poppins">
                        Ingin Konsultasi Dulu?
                    </h3>
                    <p class="text-slate-300 mb-8 max-w-2xl mx-auto text-lg">
                        Jika Anda ragu memilih produk, konsultasikan dengan dokter ahli kami secara gratis via WhatsApp.
                    </p>
                    <a href="https://wa.me/628123456789"
                       class="inline-flex items-center gap-2 bg-white text-slate-900 px-8 py-4 rounded-full font-semibold hover:bg-pink-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                             <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Chat via WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </section>
    @include('landing.sections.footer')

    {{-- Script untuk Handle Add to Cart --}}
    <script>
        function addToCart(slug) {
            const btn = document.getElementById('btn-add-to-cart');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');

            // 1. UI Loading State
            btn.disabled = true;
            btnText.textContent = 'Menambahkan...';
            btnLoading.classList.remove('hidden');

            // 2. Kirim Request ke Controller
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    slug: slug,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 3. Trigger Modal Cart (Event ini akan ditangkap oleh Cart Modal di Layout)
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    window.dispatchEvent(new CustomEvent('open-cart-modal')); 
                    
                    // Reset Tombol
                    setTimeout(() => {
                        btnText.textContent = 'Berhasil!';
                        setTimeout(() => {
                            btn.disabled = false;
                            btnText.textContent = 'Beli Sekarang';
                            btnLoading.classList.add('hidden');
                        }, 1000);
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                btnText.textContent = 'Beli Sekarang';
                btnLoading.classList.add('hidden');
                alert('Gagal menambahkan ke keranjang.');
            });
        }
    </script>
@endsection