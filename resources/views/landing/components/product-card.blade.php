@props(['image', 'name', 'category', 'price'])

<div class="group relative flex flex-col gap-3">
    {{-- 1. Bagian Gambar & Tombol Cart --}}
    {{-- Aspect Ratio 4/3 agar gambar terlihat kotak persegi panjang rapi --}}
    <div class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden bg-gray-100 border border-gray-100">
        
        {{-- Gambar --}}
        <img src="{{ $image }}" 
             alt="{{ $name }}" 
             class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
        
        {{-- Tombol Cart (Bulat Melayang di Pojok Kanan Bawah) --}}
        <button class="absolute bottom-3 right-3 bg-slate-900 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center hover:bg-blue-600 hover:scale-110 transition-all duration-300 z-10">
            {{-- Icon Cart SVG --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </button>
    </div>

    {{-- 2. Bagian Teks (Di Bawah Gambar) --}}
    <div class="flex flex-col px-1">
        {{-- Nama Produk (Font Bold Besar) --}}
        <h3 class="text-lg font-bold font-poppins text-slate-900 mb-0.5 group-hover:text-blue-600 transition-colors">
            {{ $name }}
        </h3>
        
        {{-- Kategori (Abu-abu Kecil) --}}
        <p class="text-xs font-semibold font-poppins text-gray-400 uppercase tracking-wide mb-2">
            {{ $category }}
        </p>
        
        {{-- Harga (Warna Emas/Kuning) --}}
        <p class="text-base font-bold font-poppins text-yellow-600">
            Rp {{ number_format($price, 0, ',', '.') }}
        </p>
    </div>
</div>