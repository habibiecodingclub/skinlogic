{{-- resources/views/landing/components/product-card.blade.php --}}
<a href="{{ route('produk.show', $slug) }}" class="group block h-full">
    <div class="relative flex flex-col h-full bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-pink-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        
        {{-- 1. Bagian Gambar --}}
        <div class="relative w-full aspect-[4/3] overflow-hidden bg-gray-50">
            {{-- Gambar dengan efek Zoom saat Hover --}}
            <img src="{{ $image }}" 
                 alt="{{ $name }}" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            
            {{-- Overlay halus saat hover (biar teks lebih kontras kalau nanti ada overlay text) --}}
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
        </div>

        {{-- 2. Bagian Konten --}}
        <div class="p-5 flex flex-col flex-grow">
            {{-- Kategori --}}
            <p class="text-xs font-bold text-pink-500 uppercase tracking-widest mb-2">
                {{ $category }}
            </p>
            
            {{-- Nama Produk --}}
            <h3 class="text-lg font-bold text-slate-900 font-poppins mb-2 line-clamp-2 group-hover:text-pink-600 transition-colors">
                {{ $name }}
            </h3>
            
            {{-- Harga --}}
            <p class="text-lg font-bold text-slate-900 mt-auto">
                Rp {{ number_format($price, 0, ',', '.') }}
            </p>

            {{-- 3. Tombol Detail (Baru) --}}
            <div class="mt-5 pt-4 border-t border-dashed border-gray-200">
                <div class="flex items-center justify-between text-slate-600 group-hover:text-pink-600 transition-colors">
                    <span class="text-sm font-semibold font-poppins">Lihat Detail</span>
                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-pink-500 group-hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>