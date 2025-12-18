<section
    id="home"
    class="relative min-h-screen flex items-center justify-center text-white overflow-hidden"
    x-data="{
        activeSlide: 0,
        slides: [
            '{{ asset('images/produk7.jpeg') }}',   // Gambar 1
            '{{ asset('images/treatment1.jpeg') }}', // Gambar 2 (Ganti dengan nama file Anda)
            '{{ asset('images/produk8.jpeg') }}'  // Gambar 3 (Ganti dengan nama file Anda)
        ],
        init() {
            setInterval(() => {
                this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
            }, 5000); // Ganti gambar setiap 5000ms (5 detik)
        }
    }"
>
    <div class="absolute inset-0 w-full h-full z-0">
        <template x-for="(slide, index) in slides" :key="index">
            <div
                class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out"
                :style="`background-image: url('${slide}');`"
                :class="activeSlide === index ? 'opacity-100' : 'opacity-0'"
            ></div>
        </template>
    </div>

    <div class="absolute inset-0 bg-black bg-opacity-50 z-10"></div>

    <div class="relative z-20 text-center px-6 md:px-12 max-w-3xl">
        
        <div class="flex justify-center mb-6">
            <div class="w-16 h-1 bg-yellow-500"></div>
        </div>
        
        <h1 class="text-4xl md:text-6xl font-poopins mb-4 leading-tight font-poppins">
            Wujudkan Kulit Sehat <span class="text-blue-600 font-poppins italic">Impianmu</span>
        </h1>
        
        <p class="text-base md:text-lg mb-8 text-gray-200 font-light font-poppins max-w-2xl mx-auto">
            Solusi perawatan kulit profesional dengan teknologi terkini untuk hasil yang nyata dan tahan lama.
        </p>
        
        <a href="#reservasi" class="inline-block bg-yellow-500 text-gray-900 font-poppins   px-8 py-4 rounded-full font-semibold hover:bg-yellow-400 transition shadow-lg hover:scale-105 transform duration-300">
            Lihat Info Selengkapnya
        </a>
        
        <div class="flex justify-center gap-2 mt-12">
            <template x-for="(slide, index) in slides" :key="index">
                <button 
                    @click="activeSlide = index"
                    class="h-1 rounded-full transition-all duration-300"
                    :class="activeSlide === index ? 'w-8 bg-yellow-500' : 'w-4 bg-white bg-opacity-40 hover:bg-opacity-80'"
                ></button>
            </template>
        </div>
    </div>
</section>