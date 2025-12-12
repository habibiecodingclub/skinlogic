{{-- resources/views/landing/pages/perawatan.blade.php --}}
@extends('layouts.landing')

@section('content')
    @include('landing.sections.header')

    {{-- Hero Section Perawatan --}}
    <section class="relative bg-gradient-to-br from-blue-50 via-white to-blue-50 pt-32 pb-48">
        <div class="absolute inset-0 opacity-50">
            <div class="absolute inset-0" style="background-image: url('{{ asset('images/promo-banner.png') }}'); background-size: cover;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-regular text-gray-900 mb-4 font-poppins">
                    Perawatan
                </h1>
                <div class="flex items-center justify-center gap-2 text-gray-600">
                    <a href="/" class="hover:text-blue-600 transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-gray-900 font-regular font-poppins">Perawatan</span>
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
            <div x-data="{ activeTab: 'facial' }" class="mb-12">
                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    <button @click="activeTab = 'facial'"
                            :class="activeTab === 'facial' ? 'bg-[#001a4d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-8 py-3 rounded-full font-semibold transition-all duration-300 font-poppins">
                        Facial Treatment
                    </button>
                    <button @click="activeTab = 'laser'"
                            :class="activeTab === 'laser' ? 'bg-[#001a4d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-8 py-3 rounded-full font-semibold transition-all duration-300 font-poppins">
                        Laser Therapy
                    </button>
                    <button @click="activeTab = 'body'"
                            :class="activeTab === 'body' ? 'bg-[#001a4d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-8 py-3 rounded-full font-semibold transition-all duration-300 font-poppins">
                        Body Spa
                    </button>
                </div>

                {{-- Facial Treatment --}}
                <div x-show="activeTab === 'facial'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0">

                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3 font-poppins">Facial Treatment</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Perawatan wajah profesional untuk mengatasi berbagai masalah kulit dan memberikan hasil optimal
                        </p>
                    </div>

                    {{-- Carousel Container --}}
                    <div class="relative">
                        <div class="swiper facialSwiper">
                            <div class="swiper-wrapper pb-12">
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk1.jpeg'),
                                        'title' => 'Facial Acne',
                                        'category' => 'Facial',
                                        'description' => 'Perawatan khusus untuk mengatasi jerawat dan bekasnya dengan teknologi terkini dan bahan aktif yang aman',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk2.jpeg'),
                                        'title' => 'Facial Brightening',
                                        'category' => 'Facial',
                                        'description' => 'Mencerahkan kulit wajah kusam dan meratakan warna kulit untuk tampilan lebih bercahaya',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk3.jpeg'),
                                        'title' => 'Facial Anti Aging',
                                        'category' => 'Facial',
                                        'description' => 'Mengurangi tanda-tanda penuaan seperti garis halus dan kerutan untuk kulit lebih muda',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk1.jpeg'),
                                        'title' => 'Facial Hydrating',
                                        'category' => 'Facial',
                                        'description' => 'Memberikan kelembaban intensif untuk kulit kering dan dehidrasi dengan serum khusus',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk2.jpeg'),
                                        'title' => 'Facial Glow',
                                        'category' => 'Facial',
                                        'description' => 'Perawatan untuk mendapatkan kulit glowing dan sehat dengan hasil instan',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk3.jpeg'),
                                        'title' => 'Facial Detox',
                                        'category' => 'Facial',
                                        'description' => 'Membersihkan racun dan kotoran dalam kulit untuk wajah lebih segar dan bersih',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="swiper-button-prev !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-left-4"></div>
                        <div class="swiper-button-next !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-right-4"></div>
                    </div>
                </div>

                {{-- Laser Therapy --}}
                <div x-show="activeTab === 'laser'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0">

                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3 font-poppins">Laser Therapy</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Teknologi laser canggih untuk mengatasi berbagai masalah kulit dengan hasil yang efektif
                        </p>
                    </div>

                    {{-- Carousel Container --}}
                    <div class="relative">
                        <div class="swiper laserSwiper">
                            <div class="swiper-wrapper pb-12">
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk1.jpeg'),
                                        'title' => 'Laser Hair Removal',
                                        'category' => 'Laser',
                                        'description' => 'Menghilangkan bulu secara permanen dengan teknologi laser yang aman dan nyaman',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk2.jpeg'),
                                        'title' => 'Laser Carbon Peel',
                                        'category' => 'Laser',
                                        'description' => 'Mengangkat sel kulit mati dan mencerahkan kulit dengan teknologi laser carbon',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk3.jpeg'),
                                        'title' => 'Laser Tattoo Removal',
                                        'category' => 'Laser',
                                        'description' => 'Menghilangkan tato dengan aman menggunakan teknologi laser Q-Switch',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk1.jpeg'),
                                        'title' => 'Laser Rejuvenation',
                                        'category' => 'Laser',
                                        'description' => 'Meremajakan kulit dan mengurangi tanda penuaan dengan laser fraksional',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk2.jpeg'),
                                        'title' => 'Laser Acne Scar',
                                        'category' => 'Laser',
                                        'description' => 'Mengatasi bekas jerawat dan lubang bekas jerawat dengan laser resurfacing',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk3.jpeg'),
                                        'title' => 'Laser Pigmentation',
                                        'category' => 'Laser',
                                        'description' => 'Menghilangkan flek hitam dan hiperpigmentasi dengan laser targeting',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="swiper-button-prev !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-left-4"></div>
                        <div class="swiper-button-next !w-12 !h-12 !bg-white !rounded-full !shadow-lg after:!text-[#001a4d] after:!text-lg !-right-4"></div>
                    </div>
                </div>

                {{-- Body Spa --}}
                <div x-show="activeTab === 'body'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0">

                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3 font-poppins">Body Spa</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Perawatan tubuh menyeluruh untuk relaksasi dan kecantikan tubuh Anda
                        </p>
                    </div>

                    {{-- Carousel Container --}}
                    <div class="relative">
                        <div class="swiper bodySwiper">
                            <div class="swiper-wrapper pb-12">
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk1.jpeg'),
                                        'title' => 'Body Slimming',
                                        'category' => 'Body Spa',
                                        'description' => 'Program pelangsingan tubuh dengan teknologi canggih untuk hasil optimal',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk2.jpeg'),
                                        'title' => 'Body Whitening',
                                        'category' => 'Body Spa',
                                        'description' => 'Mencerahkan kulit tubuh secara menyeluruh untuk tampilan lebih cerah',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk3.jpeg'),
                                        'title' => 'Body Massage',
                                        'category' => 'Body Spa',
                                        'description' => 'Pijat relaksasi untuk menghilangkan stress dan ketegangan otot',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk1.jpeg'),
                                        'title' => 'Body Scrub',
                                        'category' => 'Body Spa',
                                        'description' => 'Mengangkat sel kulit mati untuk kulit tubuh lebih halus dan cerah',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk2.jpeg'),
                                        'title' => 'Body Detox',
                                        'category' => 'Body Spa',
                                        'description' => 'Mengeluarkan racun dari tubuh untuk kesehatan dan kecantikan optimal',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                                <div class="swiper-slide">
                                    @include('landing.components.treatment-card', [
                                        'image' => asset('images/produk3.jpeg'),
                                        'title' => 'Body Firming',
                                        'category' => 'Body Spa',
                                        'description' => 'Mengencangkan kulit tubuh yang kendur dengan treatment khusus',
                                        'appointmentUrl' => '#reservasi',
                                        'detailUrl' => '#'
                                    ])
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>

                        {{-- Navigation Buttons --}}
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
        // Facial Swiper
        const facialSwiper = new Swiper('.facialSwiper', {
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
        });

        // Laser Swiper
        const laserSwiper = new Swiper('.laserSwiper', {
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
        });

        // Body Swiper
        const bodySwiper = new Swiper('.bodySwiper', {
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
        });
    </script>
@endsection
