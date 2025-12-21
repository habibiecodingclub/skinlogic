{{-- resources/views/sections/header.blade.php --}}
<header x-data="{
            mobileMenuOpen: false,
            scrolled: false
        }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })"
        :class="scrolled ? 'bg-[#001a4d] shadow-lg py-0' : 'bg-[#001a4d]'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/brandSL.png') }}"
                     alt="SkinLogic Logo"
                     class="w-12 h-12 object-contain group-hover:scale-105 transition-transform duration-300">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide font-poppins">SkinLogic</h1>
                    <p class="text-xs text-blue-200 tracking-widest uppercase font-poppins">Beauty Clinic</p>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-white hover:text-blue-300 font-medium transition-colors font-poppins">Home</a>
                <a href="{{ route('produk.index') }}" class="text-white hover:text-blue-300 font-medium transition-colors font-poppins">Produk</a>
                <a href="{{ route('perawatan.index') }}" class="text-white hover:text-blue-300 font-medium transition-colors font-poppins">Perawatan</a>
                <a href="{{ route('tentang-kami') }}" class="text-white hover:text-blue-300 font-medium transition-colors font-poppins">Tentang Kami</a>
                <a href="{{ route('artikel.index') }}" class="text-white hover:text-blue-300 font-medium transition-colors font-poppins">Artikel</a>

                {{-- TOMBOL RESERVASI --}}
                {{-- PERUBAHAN UTAMA DI SINI: Gunakan $dispatch --}}
                <button @click="$dispatch('open-reservation')"
                        class="flex items-center gap-2 bg-white text-[#001a4d] px-6 py-2.5 rounded-full font-semibold font-poppins hover:bg-blue-50 hover:scale-105 transition-all duration-300 shadow-md cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Reservasi Sekarang
                </button>
            </nav>

            {{-- Mobile Menu Button --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-white p-2">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        {{-- Mobile Navigation --}}
        <nav x-show="mobileMenuOpen" style="display: none;" class="lg:hidden py-4 border-t border-white/10">
            <div class="flex flex-col gap-2">
                <a href="{{ route('home') }}" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg font-poppins">Home</a>
                {{-- Link lainnya ... --}}
                
                {{-- Tombol Mobile --}}
                <button @click="$dispatch('open-reservation'); mobileMenuOpen = false" 
                        class="w-full bg-white text-[#001a4d] px-4 py-3 rounded-lg font-semibold font-poppins text-center mt-2">
                    Reservasi Sekarang
                </button>
            </div>
        </nav>
    </div>
    
    {{-- HAPUS SEMUA KODE MODAL LAMA DARI SINI --}}

</header>