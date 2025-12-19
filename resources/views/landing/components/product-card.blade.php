{{-- resources/views/landing/components/product-card.blade.php --}}
<a href="{{ route('produk.show', $slug ?? '#') }}" class="group block h-full">
    
    {{-- CONTAINER UTAMA --}}
    {{-- rounded-3xl: Sudut sangat bulat (soft) --}}
    {{-- hover:-translate-y-2: Efek melayang halus --}}
    <div class="relative flex flex-col h-full bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 border border-gray-50">
        
        {{-- 1. BAGIAN GAMBAR --}}
        {{-- Aspect Ratio 4:5 (Portrait) agar terlihat jenjang/elegan --}}
        <div class="relative w-full aspect-[4/5] overflow-hidden bg-gray-100">
            
            {{-- Badge Kategori (Pojok Kiri Atas) --}}
            <span class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase text-slate-800 shadow-sm">
                {{ $category }}
            </span>

            {{-- Gambar Produk --}}
            <img src="{{ $image }}" 
                 alt="{{ $name }}" 
                 class="w-full h-full object-cover object-center transition-transform duration-700 ease-in-out group-hover:scale-110">
            
            {{-- Overlay Gelap Halus saat Hover --}}
            <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </div>

        {{-- 2. BAGIAN KONTEN --}}
        <div class="p-6 flex flex-col flex-grow text-center">
            
            {{-- Nama Produk --}}
            <h3 class="text-lg font-bold text-slate-800 font-poppins mb-2 line-clamp-2 group-hover:text-yellow-600 transition-colors">
                {{ $name }}
            </h3>
            
            {{-- Harga --}}
            <p class="text-yellow-600 font-medium text-lg mb-4">
                Rp {{ number_format($price, 0, ',', '.') }}
            </p>

            {{-- 3. TOMBOL 'BACA SELENGKAPNYA' (Di Bawah Harga) --}}
            {{-- mt-auto: Memaksa tombol selalu di posisi paling bawah --}}
            <div class="mt-auto w-full pt-2">
                <span class="inline-block w-full py-2.5 px-4 rounded-full border border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-widest transition-all duration-300 group-hover:bg-slate-900 group-hover:text-white group-hover:border-slate-900">
                    Baca Selengkapnya
                </span>
            </div>

        </div>
    </div>
</a>