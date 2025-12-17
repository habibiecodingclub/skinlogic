{{-- resources/views/components/header.blade.php --}}
<header x-data="{
            mobileMenuOpen: false,
            scrolled: false,
            reservationModal: false,
            activeTab: 'whatsapp',      // Tab aktif default
            paymentMethod: 'qris',      // Metode bayar default
            waName: '',                 // Data form WA
            waDate: ''                  // Data form WA
        }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.pageYOffset > 10 })"
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
                <button @click="reservationModal = true"
                        class="flex items-center gap-2 bg-white text-[#001a4d] px-6 py-2.5 rounded-full font-semibold hover:bg-blue-50 hover:scale-105 transition-all duration-300 shadow-md font-poppins">
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
                <a href="{{ route('produk.index') }}" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg font-poppins">Produk</a>
                <a href="{{ route('perawatan.index') }}" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg font-poppins">Perawatan</a>
                <a href="{{ route('tentang-kami') }}" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg font-poppins">Tentang Kami</a>
                <a href="{{ route('artikel.index') }}" class="text-white hover:bg-white/10 px-4 py-3 rounded-lg font-poppins">Artikel</a>
                <a href="#reservasi" @click="reservationModal = true; mobileMenuOpen = false" class="bg-white text-[#001a4d] px-4 py-3 rounded-lg font-semibold font-poppins text-center mt-2">Reservasi Sekarang</a>
            </div>
        </nav>
    </div>

    {{-- === MODAL POPUP RESERVASI (SESUAI DESAIN GAMBAR) === --}}
    <div x-show="reservationModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">

        {{-- Backdrop --}}
        <div x-show="reservationModal"
             x-transition.opacity
             class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
             @click="reservationModal = false"></div>

        {{-- Modal Content --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="reservationModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 font-poppins">

                {{-- Header Modal --}}
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-[#001a4d]">
                        Reservasi: <span class="text-[#c5a365]">Umum</span>
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">Pilih metode pemesanan yang Anda inginkan.</p>
                </div>

                {{-- Tabs --}}
                <div class="flex border-b border-gray-200 mb-6">
                    <button @click="activeTab = 'whatsapp'"
                            :class="activeTab === 'whatsapp' ? 'border-[#c5a365] text-slate-900' : 'border-transparent text-gray-400 hover:text-gray-600'"
                            class="flex-1 pb-3 text-sm font-semibold border-b-2 transition-colors duration-300 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Via WhatsApp
                    </button>
                    <button @click="activeTab = 'website'"
                            :class="activeTab === 'website' ? 'border-[#c5a365] text-slate-900' : 'border-transparent text-gray-400 hover:text-gray-600'"
                            class="flex-1 pb-3 text-sm font-semibold border-b-2 transition-colors duration-300 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                        Via Website
                    </button>
                </div>

                {{-- CONTENT 1: FORM WHATSAPP --}}
                <div x-show="activeTab === 'whatsapp'" x-transition:enter="transition ease-out duration-200 opacity-0" x-transition:enter-end="opacity-100">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" x-model="waName" placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 rounded-lg border border-gray-200 text-sm focus:border-[#001a4d] focus:ring-1 focus:ring-[#001a4d] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Rencana</label>
                            <input type="date" x-model="waDate" class="w-full px-4 py-3 rounded-lg border border-gray-200 text-sm text-gray-500 focus:border-[#001a4d] focus:ring-1 focus:ring-[#001a4d] outline-none transition">
                        </div>

                        {{-- Tombol Chat Admin (Hijau) --}}
                        <a :href="'https://wa.me/628123456789?text=Halo Admin, saya ' + waName + ' ingin reservasi untuk tanggal ' + waDate"
                           target="_blank"
                           class="block w-full bg-[#25d366] hover:bg-[#20bd5a] text-white text-center font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 mt-6 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Chat Admin Sekarang
                        </a>
                    </div>
                </div>

                {{-- CONTENT 2: FORM WEBSITE --}}
                <div x-show="activeTab === 'website'" x-transition:enter="transition ease-out duration-200 opacity-0" x-transition:enter-end="opacity-100">
                    <form action="#" method="POST" class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs focus:border-[#001a4d] outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-1">No. WhatsApp</label>
                                <input type="text" class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs focus:border-[#001a4d] outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-1">Tanggal</label>
                                <input type="date" class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs text-gray-500 focus:border-[#001a4d] outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-1">Jam</label>
                                <select class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs text-gray-500 focus:border-[#001a4d] outline-none">
                                    <option>10:00</option>
                                    <option>13:00</option>
                                    <option>16:00</option>
                                </select>
                            </div>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-700 mb-2">Metode Pembayaran</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" @click="paymentMethod = 'qris'"
                                        :class="paymentMethod === 'qris' ? 'border-[#001a4d] bg-blue-50 text-[#001a4d]' : 'border-gray-200 text-gray-500'"
                                        class="border rounded-lg p-2 flex flex-col items-center justify-center gap-1 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                    <span class="text-[10px] font-bold">QRIS</span>
                                </button>
                                <button type="button" @click="paymentMethod = 'transfer'"
                                        :class="paymentMethod === 'transfer' ? 'border-[#001a4d] bg-blue-50 text-[#001a4d]' : 'border-gray-200 text-gray-500'"
                                        class="border rounded-lg p-2 flex flex-col items-center justify-center gap-1 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                    <span class="text-[10px] font-bold">Transfer</span>
                                </button>
                                <button type="button" @click="paymentMethod = 'ewallet'"
                                        :class="paymentMethod === 'ewallet' ? 'border-[#001a4d] bg-blue-50 text-[#001a4d]' : 'border-gray-200 text-gray-500'"
                                        class="border rounded-lg p-2 flex flex-col items-center justify-center gap-1 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    <span class="text-[10px] font-bold">E-Wallet</span>
                                </button>
                            </div>
                        </div>

                        {{-- Tombol Submit (Biru Tua) --}}
                        <button type="submit" class="block w-full bg-[#001a4d] hover:bg-[#002a66] text-white text-center font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 mt-4">
                            Bayar & Booking (POS)
                        </button>
                    </form>
                </div>

                {{-- Footer: Tombol Batal --}}
                <div class="mt-6 text-center">
                    <button @click="reservationModal = false" class="text-xs text-gray-400 hover:text-gray-600 underline">
                        Batal
                    </button>
                </div>

            </div>
        </div>
    </div>
</header>

{{-- Spacer --}}
<div class="h-20"></div>
