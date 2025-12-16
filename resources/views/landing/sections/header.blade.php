{{-- resources/views/components/header.blade.php --}}
<header x-data="{ mobileMenuOpen: false, scrolled: false, reservationModal: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.pageYOffset > 10 })"
        :class="scrolled ? 'bg-[#001a4d]/85 backdrop-blur-md shadow-lg' : 'bg-[#001a4d]'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                {{-- Pastikan gambar logo ada di public/images/brandSL.png --}}
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

                {{-- TOMBOL RESERVASI DESKTOP (Trigger Modal) --}}
                <button @click="reservationModal = true"
                        class="flex items-center gap-2 bg-white text-[#001a4d] px-6 py-2.5 rounded-full font-semibold hover:bg-blue-50 hover:scale-105 transition-all duration-300 shadow-md font-poppins">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Reservasi Sekarang
                </button>
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
             style="display: none;"
             class="lg:hidden py-4 border-t border-white/10">

            <div class="flex flex-col gap-2">
                <a href="{{ route('home') }}" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">Home</a>
                <a href="{{ route('produk.index') }}" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">Produk</a>
                <a href="{{ route('perawatan.index') }}" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">Perawatan</a>
                <a href="#tentang" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">Tentang Kami</a>
                <a href="#artikel" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 font-poppins">Artikel</a>

                {{-- TOMBOL RESERVASI MOBILE (Trigger Modal & Close Menu) --}}
                <button @click="reservationModal = true; mobileMenuOpen = false"
                        class="flex items-center justify-center gap-2 bg-white text-[#001a4d] px-4 py-3 rounded-lg font-semibold mt-2 hover:bg-blue-50 transition-colors duration-200 font-poppins w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Reservasi Sekarang
                </button>
            </div>
        </nav>
    </div>

    {{-- === MODAL POPUP PILIHAN RESERVASI (Menggunakan Alpine x-show) === --}}
    <div x-show="reservationModal"
         style="display: none;"
         class="fixed inset-0 z-[100] overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">

        {{-- Background Overlay (Gelap + Blur) --}}
        <div x-show="reservationModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-[#001a4d]/80 backdrop-blur-sm transition-opacity"
             @click="reservationModal = false"></div>

        {{-- Modal Panel --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="reservationModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">

                {{-- Tombol Close X --}}
                <button @click="reservationModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="bg-white px-4 pb-4 pt-5 sm:p-8">
                    <div class="mx-auto flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 mb-6 animate-pulse">
                        <svg class="h-8 w-8 text-[#001a4d]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-2xl font-bold leading-6 text-[#001a4d] font-poppins mb-2">Metode Reservasi</h3>
                        <p class="text-sm text-gray-500 mb-8">Silakan pilih cara pemesanan yang paling nyaman untuk Anda.</p>

                        <div class="space-y-3">
                            {{-- Pilihan 1: Via WhatsApp --}}
                            <a href="https://wa.me/628123456789?text=Halo%20Admin%20SkinLogic,%20saya%20ingin%20reservasi%20perawatan"
                               target="_blank"
                               class="group flex items-center justify-center gap-3 w-full rounded-xl bg-green-500 px-4 py-4 text-sm font-semibold text-white shadow-md hover:bg-green-600 transition-all hover:-translate-y-1">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <span>Reservasi via WhatsApp</span>
                            </a>

                            {{-- Pilihan 2: Via Website Form --}}
                            <a href="#booking-form"
                               @click="reservationModal = false"
                               class="group flex items-center justify-center gap-3 w-full rounded-xl bg-[#001a4d] px-4 py-4 text-sm font-semibold text-white shadow-md hover:bg-[#002a66] transition-all hover:-translate-y-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Isi Formulir Website</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>

{{-- Spacer untuk kompensasi fixed header --}}
<div class="h-20"></div>