{{-- resources/views/components/header.blade.php --}}
<header x-data="{ mobileMenuOpen: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.pageYOffset > 10 })"
        :class="scrolled ? 'bg-[#001a4d]/65 backdrop-blur-md shadow-lg' : 'bg-[#001a4d]/90 backdrop-blur-sm'"
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
                <a href="{{ route('home') }}"
                   class="text-white hover:text-blue-300 font-medium transition-colors duration-200 relative group font-poppins">
                    Home
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300"></span>
                </a>

                <a href="{{ route('produk.index') }}"
                   class="text-white hover:text-blue-300 font-medium transition-colors duration-200 relative group font-poppins">
                    Produk
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300"></span>
                </a>

                <a href="{{ route('perawatan.index') }}"
                   class="text-white hover:text-blue-300 font-medium transition-colors duration-200 relative group font-poppins">
                    Perawatan
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300"></span>
                </a>

                <a href="#tentang"
                   class="text-white hover:text-blue-300 font-medium transition-colors duration-200 relative group font-poppins">
                    Tentang Kami
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300"></span>
                </a>

                <a href="#artikel"
                   class="text-white hover:text-blue-300 font-medium transition-colors duration-200 relative group font-poppins">
                    Artikel
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300"></span>
                </a>

                <a href="#reservasi"
                   class="flex items-center gap-2 bg-white text-[#001a4d] px-6 py-2.5 rounded-full font-semibold hover:bg-blue-50 hover:scale-105 transition-all duration-300 shadow-md font-poppins">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Reservasi Sekarang
                </a>
            </nav>

            {{-- Mobile Menu Button --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden text-white p-2 rounded-md hover:bg-white/10 transition-colors duration-200">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile Navigation --}}
        <nav x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @click.away="mobileMenuOpen = false"
             class="lg:hidden py-4 border-t border-white/10">

            <div class="flex flex-col gap-2">
                <a href="{{ route('home') }}"
                   class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">
                    Home
                </a>

                <a href="{{ route('produk.index') }}"
                   class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">
                    Produk
                </a>

                <a href="{{ route('perawatan.index') }}"
                   class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">
                    Perawatan
                </a>

                <a href="#tentang"
                   class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">
                    Tentang Kami
                </a>

                <a href="#artikel"
                   class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">
                    Artikel
                </a>

                <a href="#reservasi"
                   class="flex items-center justify-center gap-2 bg-white text-[#001a4d] px-4 py-3 rounded-lg font-semibold mt-2 hover:bg-blue-50 transition-colors duration-200 font-poppins">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Reservasi Sekarang
                </a>
            </div>
        </nav>
    </div>
</header>

{{-- Spacer untuk kompensasi fixed header --}}
<div class="h-20"></div>
