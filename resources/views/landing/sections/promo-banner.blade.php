<section id="promo" class="py-16 bg-white">
    {{-- Container diubah agar lebih lebar (menghapus max-w-7xl) --}}
    <div class="w-full px-4 md:px-8 lg:px-12">
        
        {{-- HEADER SECTION: DIUBAH JADI KETENGAH --}}
        <div class="mb-12 text-center max-w-3xl mx-auto">
            {{-- Tracking diperlebar sedikit, margin bawah ditambah --}}
            <span class="text-yellow-600 font-bold tracking-[0.2em] uppercase text-sm mb-3 block font-poppins">
                SPECIAL OFFERS
            </span>
            {{-- Ukuran font diperbesar sedikit untuk impact lebih --}}
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 font-poppins">
                Promo Spesial Bulan Ini
            </h2>
        </div>

        {{-- AREA SLIDER --}}
        {{-- Ditambahkan max-w-screen-2xl agar sangat lebar tapi tidak pecah di layar ultrawide --}}
        <div 
            x-data="{
                activeSlide: 0,
                slides: [
                    '{{ asset('images/promo-banner-1.jpg') }}', 
                    '{{ asset('images/promo-banner-2.jpg') }}', 
                    '{{ asset('images/promo-banner-3.jpg') }}'
                ],
                next() {
                    this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
                },
                prev() {
                    this.activeSlide = (this.activeSlide === 0) ? this.slides.length - 1 : this.activeSlide - 1;
                },
                init() {
                    // Auto slide setiap 5 detik
                    setInterval(() => { this.next() }, 5000);
                }
            }"
            class="relative w-full max-w-screen-2xl mx-auto rounded-[2rem] overflow-hidden shadow-2xl group aspect-[21/9] md:aspect-[21/7]"
        >
            {{-- Catatan: aspect ratio saya ubah sedikit di md:aspect-[21/7] agar sedikit lebih tinggi proporsinya saat lebar --}}
            
            {{-- Loop Gambar --}}
            <template x-for="(slide, index) in slides" :key="index">
                <div 
                    class="absolute inset-0 w-full h-full transition-all duration-1000 ease-in-out transform"
                    :class="{
                        'opacity-100 scale-100 z-10': activeSlide === index,
                        'opacity-0 scale-105 z-0': activeSlide !== index
                    }"
                >
                    <img :src="slide" alt="Promo Banner" class="w-full h-full object-cover">
                    
                    {{-- Overlay Gradient diperhalus --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                </div>
            </template>

            {{-- Tombol Navigasi di Dalam Gambar (Muncul saat Hover) --}}
            <button @click="prev()" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm p-3 md:p-4 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white text-slate-900 shadow-lg z-20 hover:scale-110">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="next()" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm p-3 md:p-4 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white text-slate-900 shadow-lg z-20 hover:scale-110">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Indikator Dots (Bawah Tengah) --}}
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
                <template x-for="(slide, index) in slides" :key="index">
                    <button 
                        @click="activeSlide = index"
                        class="h-2 rounded-full transition-all duration-500"
                        :class="activeSlide === index ? 'w-8 bg-yellow-500' : 'w-2 bg-white/60 hover:bg-white'"
                    ></button>
                </template>
            </div>

        </div>
    </div>
</section>