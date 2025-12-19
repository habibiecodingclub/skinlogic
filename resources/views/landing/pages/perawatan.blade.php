{{-- resources/views/landing/pages/perawatan.blade.php --}}
@extends('layouts.landing')

@section('content')
    @include('landing.sections.header')

    {{-- Hero Section Perawatan --}}
    <section class="relative bg-gradient-to-br from-blue-50 via-white to-blue-50 pt-32 pb-48 -mt-6">
        <div class="absolute inset-0 opacity-75">
            <div class="absolute inset-0" style="background-image: url('{{ asset('images/herosection-on-detail.png') }}'); background-size: cover;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-regular text-gray-900 mb-4 font-poppins hover:text-white transition-colors">
                    Perawatan
                </h1>
                <div class="flex items-center justify-center gap-2 text-gray-900">
                    <a href="/" class="hover:text-white transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-gray-900 font-regular font-poppins hover:text-white transition-colors">Perawatan</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Treatment Section dengan Tab & Carousel --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 md:px-12">

            {{-- Category Tabs --}}
            {{-- Default active tab 'facial' --}}
            <div x-data="{ activeTab: 'facial' }" class="mb-12">
                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    
                    {{-- Tab 1: FACIAL --}}
                    <button @click="activeTab = 'facial'"
                            :class="activeTab === 'facial' ? 'bg-[#001a4d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-8 py-3 rounded-full font-semibold transition-all duration-300 font-poppins">
                        Facial Treatment
                    </button>

                    {{-- Tab 2: PEELING (Gantikan Body Spa) --}}
                    <button @click="activeTab = 'peeling'"
                            :class="activeTab === 'peeling' ? 'bg-[#001a4d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-8 py-3 rounded-full font-semibold transition-all duration-300 font-poppins">
                        Peeling Treatment
                    </button>

                    {{-- Tab 3: LASER --}}
                    <button @click="activeTab = 'laser'"
                            :class="activeTab === 'laser' ? 'bg-[#001a4d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-8 py-3 rounded-full font-semibold transition-all duration-300 font-poppins">
                        Laser Therapy
                    </button>
                    
                </div>

                {{-- 1. Facial Treatment Content --}}
                <div x-show="activeTab === 'facial'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0">

                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3 font-poppins">Facial Treatment</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Perawatan wajah profesional untuk membersihkan, menutrisi, dan mencerahkan kulit Anda.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="swiper facialSwiper">
                            <div class="swiper-wrapper pb-12">
                                @foreach(App\Services\TreatmentDataService::getTreatmentsByCategory('facial') as $treatment)
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => $treatment['image'],
                                        'title' => $treatment['title'],
                                        'category' => $treatment['category'],
                                        'description' => $treatment['short_description'],
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => route('perawatan.show', ['slug' => $treatment['slug']])
                                    ])
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <div class="swiper-button-prev !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-left-4"></div>
                        <div class="swiper-button-next !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-right-4"></div>
                    </div>
                </div>

                {{-- 2. Peeling Treatment Content (Gantikan Body Spa) --}}
                <div x-show="activeTab === 'peeling'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0">

                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3 font-poppins">Peeling Treatment</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Eksfoliasi kulit untuk mengangkat sel kulit mati, mengatasi jerawat, dan flek hitam.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="swiper peelingSwiper">
                            <div class="swiper-wrapper pb-12">
                                @foreach(App\Services\TreatmentDataService::getTreatmentsByCategory('peeling') as $treatment)
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => $treatment['image'],
                                        'title' => $treatment['title'],
                                        'category' => $treatment['category'],
                                        'description' => $treatment['short_description'],
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => route('perawatan.show', ['slug' => $treatment['slug']])
                                    ])
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <div class="swiper-button-prev !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-left-4"></div>
                        <div class="swiper-button-next !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-right-4"></div>
                    </div>
                </div>

                {{-- 3. Laser Therapy Content --}}
                <div x-show="activeTab === 'laser'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0">

                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3 font-poppins">Laser Therapy</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Teknologi laser canggih untuk peremajaan, acne, hair removal, dan penghapusan tato.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="swiper laserSwiper">
                            <div class="swiper-wrapper pb-12">
                                @foreach(App\Services\TreatmentDataService::getTreatmentsByCategory('laser') as $treatment)
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => $treatment['image'],
                                        'title' => $treatment['title'],
                                        'category' => $treatment['category'],
                                        'description' => $treatment['short_description'],
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => route('perawatan.show', ['slug' => $treatment['slug']])
                                    ])
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <div class="swiper-button-prev !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-left-4"></div>
                        <div class="swiper-button-next !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-right-4"></div>
                    </div>
                </div>

            </div>

            {{-- CTA Section --}}
            <div class="mt-20 bg-gradient-to-br from-[#001a4d] to-[#003d82] rounded-2xl p-12 text-center">
                <h3 class="text-3xl font-bold text-white mb-4 font-poppins">
                    Tidak Menemukan Perawatan Yang Anda Cari?
                </h3>
                <p class="text-blue-100 mb-8 max-w-2xl mx-auto">
                    Konsultasikan kebutuhan perawatan kecantikan Anda dengan tim profesional kami
                </p>
                <a href="#reservasi"
                   class="inline-flex items-center gap-2 bg-white text-[#001a4d] px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Konsultasi Sekarang
                </a>
            </div>

        </div>
    </section>

    @include('landing.sections.footer')

    {{-- Swiper JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const commonSwiperConfig = {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                }
            }
        };

        // Initialize Swipers
        new Swiper('.facialSwiper', commonSwiperConfig);
        new Swiper('.peelingSwiper', commonSwiperConfig);
        new Swiper('.laserSwiper', commonSwiperConfig);
    </script>
@endsection