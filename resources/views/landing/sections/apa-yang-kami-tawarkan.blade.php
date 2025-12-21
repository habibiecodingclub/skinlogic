<section id="treatment" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <!-- Header -->
        <div class="text-center mb-4">
            <p class="text-sm font-semibold font-poppins text-gray-600 tracking-wider uppercase mb-2">TREATMENT</p>
            <h2 class="text-3xl md:text-4xl font-bold font-poppins text-gray-900 mb-8">
                Apa Yang Kami Tawarkan
            </h2>
        </div>

        <!-- Link Lihat Semua -->
        <div class="flex justify-end mb-8">
            <a href="{{ route('perawatan.index') }}" class="text-gray-900 font-semibold font-poppins hover:text-skinlogic transition-colors duration-300 flex items-center gap-2 group">
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
                'title' => 'Facial Premium',
                'category' => 'Facial',
                'description' => 'Facial lengkap dengan serum dan teknologi HF......',
                'appointmentUrl' => '#',
                'detailUrl' => route('perawatan.show', ['slug' => 'facial-premium'])
            ])

            @include('landing.components.treatment-card', [
                'image' => asset('images/produk2.jpeg'),
                'title' => 'IPL Rejuve',
                'category' => 'Laser',
                'description' => 'Peremajaan kulit dengan teknologi cahaya .....',
                'appointmentUrl' => '#',
                'detailUrl' => route('perawatan.show', ['slug' => 'ipl-rejuve'])
            ])

            @include('landing.components.treatment-card', [
                'image' => asset('images/produk3.jpeg'),
                'title' => 'Peeling Acne',
                'category' => 'Peeling',
                'description' => 'Mengeringkan jerawat dan mengurangkan minyak .....',
                'appointmentUrl' => '#',
                'detailUrl' => route('perawatan.show', ['slug' => 'peeling-acne'])
            ])
        </div>
    </div>
</section>
