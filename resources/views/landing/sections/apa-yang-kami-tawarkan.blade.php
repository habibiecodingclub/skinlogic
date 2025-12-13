<section id="treatment" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <!-- Header -->
        <div class="text-center mb-4">
            <p class="text-sm font-semibold text-gray-600 tracking-wider uppercase mb-2">TREATMENT</p>
            <h2 class="text-3xl md:text-4xl font-bold font-poppins text-gray-900 mb-8">
                Apa Yang Kami Tawarkan
            </h2>
        </div>

        <!-- Link Lihat Semua -->
        <div class="flex justify-end mb-8">
            <a href="{{ route('perawatan.index') }}" class="text-gray-900 font-semibold hover:text-skinlogic transition-colors duration-300 flex items-center gap-2 group">
                LIHAT SEMUA
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <!-- Treatment Cards Grid -->
        <div class="grid md:grid-cols-3 gap-8">
            @include('landing.components.treatment-card', [
                'image' => asset('images/produk1.jpeg'),
                'title' => 'Facial Acne',
                'category' => 'Facial',
                'description' => 'Perawatan khusus untuk mengatasi jerawat dan bekasnya dengan teknologi.....',
                'appointmentUrl' => '#',
                'detailUrl' => route('perawatan.show', ['slug' => 'facial-acne'])
            ])

            @include('landing.components.treatment-card', [
                'image' => asset('images/produk2.jpeg'),
                'title' => 'Laser Hair Removal',
                'category' => 'Laser',
                'description' => 'Menghilangkan bulu secara permanen dengan teknologi laser yang aman dan.....',
                'appointmentUrl' => '#',
                'detailUrl' => route('perawatan.show', ['slug' => 'laser-hair-removal'])
            ])

            @include('landing.components.treatment-card', [
                'image' => asset('images/produk3.jpeg'),
                'title' => 'Facial Acne',
                'category' => 'Facial',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmo.....',
                'appointmentUrl' => '#',
                'detailUrl' => '#'
            ])
        </div>
    </div>
</section>
