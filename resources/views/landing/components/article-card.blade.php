@props(['image', 'title', 'category', 'excerpt'])

<div class="group relative flex flex-col gap-3 h-full">
    {{-- 1. Bagian Gambar & Tombol Aksi --}}
    {{-- Aspect Ratio 4/3 agar sama persis dengan ukuran kartu Produk --}}
    <div class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden bg-gray-100 border border-gray-100">
        
        {{-- Gambar --}}
        <img src="{{ $image }}" 
             alt="{{ $title }}" 
             class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
        
        {{-- Tombol Panah (Melayang di Pojok Kanan Bawah - Mirip tombol Cart Produk) --}}
        <a href="#" class="absolute bottom-3 right-3 bg-slate-900 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center hover:bg-blue-600 hover:scale-110 transition-all duration-300 z-10">
            {{-- Icon Arrow Right --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- 2. Bagian Teks (Di Bawah Gambar) --}}
    <div class="flex flex-col px-1 flex-grow">
        {{-- Kategori (Kuning/Emas) --}}
        <span class="text-xs font-extrabold font-poppins text-yellow-600 uppercase tracking-widest mb-2">
            {{ $category }}
        </span>

        {{-- Judul Artikel (Font Standar Bold - Tanpa Serif) --}}
        <h3 class="text-lg font-bold font-poppins text-slate-900 mb-3 leading-snug group-hover:text-blue-600 transition-colors">
            {{ $title }}
        </h3>
        
        {{-- Deskripsi Singkat --}}
        <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
            {{ $excerpt }}
        </p>
    </div>
</div>