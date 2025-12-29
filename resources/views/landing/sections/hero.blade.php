<section
    id="home"
    class="relative min-h-screen flex items-center justify-center text-white overflow-hidden"
    x-data="{
        activeSlide: 0,
        slides: [
            '{{ asset('images/produk7.jpeg') }}',   // Gambar 1
            '{{ asset('images/treatment1.jpeg') }}', // Gambar 2
            '{{ asset('images/produk8.jpeg') }}'    // Gambar 3
        ],
        init() {
            setInterval(() => {
                this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
            }, 5000); // Ganti gambar setiap 5 detik
        }
    }"
>
    {{-- Background Slider --}}
    <div class="absolute inset-0 w-full h-full z-0">
        <template x-for="(slide, index) in slides" :key="index">
            <div
                class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out"
                :style="`background-image: url('${slide}');`"
                :class="activeSlide === index ? 'opacity-100' : 'opacity-0'"
            ></div>
        </template>
    </div>

    {{-- Dark Overlay (Agar teks terbaca jelas) --}}
    <div class="absolute inset-0 bg-black/50 z-10 backdrop-blur-[1px]"></div>

    {{-- Content Wrapper --}}
    <div class="relative z-20 container mx-auto px-6 md:px-12 text-center">
        
        {{-- Badge Kecil (Opsional: Memberi kesan profesional) --}}
        <div class="inline-block mb-6 animate-fade-in-up">
            <span class="py-1.5 px-4 border border-white/20 rounded-full bg-white/10 backdrop-blur-md text-yellow-400 text-xs md:text-sm font-semibold tracking-widest uppercase font-poppins shadow-sm">
                Professional Skin Care
            </span>
        </div>
        
        {{-- Headline Utama --}}
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight tracking-tight font-poppins drop-shadow-lg">
            Wujudkan Kulit Sehat <br class="hidden md:block" />
            {{-- Gradient Text: Memberikan efek glowing/menyala --}}
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-blue-400 to-blue-500 italic">
                Impianmu
            </span>
        </h1>
        
        {{-- Subheadline --}}
        <p class="text-base md:text-lg text-gray-100 mb-10 font-normal leading-relaxed max-w-2xl mx-auto font-poppins opacity-90 drop-shadow-md">
            Solusi perawatan kulit profesional dengan teknologi terkini untuk hasil yang nyata, aman, dan tahan lama.
        </p>
        
        {{-- Call to Action Button --}}
        <div class="flex flex-col md:flex-row justify-center gap-4">
            <a href="#reservasi" class="group relative px-8 py-4 bg-yellow-500 rounded-full font-semibold text-gray-900 font-poppins overflow-hidden transition-all hover:scale-105 hover:bg-yellow-400 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                <span class="relative z-10 flex items-center justify-center gap-2">
                    Lihat Info Selengkapnya
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </span>
            </a>
        </div>
        
        {{-- Slider Indicators (Dots) --}}
        <div class="flex justify-center gap-3 mt-16">
            <template x-for="(slide, index) in slides" :key="index">
                <button 
                    @click="activeSlide = index"
                    class="h-1.5 rounded-full transition-all duration-500 ease-out shadow-sm"
                    :class="activeSlide === index ? 'w-8 bg-yellow-500' : 'w-2 bg-white/40 hover:bg-white/70'"
                    aria-label="Slide navigation"
                ></button>
            </template>
        </div>
    </div>
</section>