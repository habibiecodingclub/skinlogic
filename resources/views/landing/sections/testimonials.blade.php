{{-- Pastikan Swiper CSS sudah di-load di layout utama atau di sini --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<section id="testimoni" class="py-24 bg-gradient-to-b from-white via-pink-50 to-white overflow-hidden">
    <div class="container mx-auto px-6 max-w-7xl relative">
        
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <span class="inline-block py-1 px-3 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold tracking-widest uppercase mb-4 font-poppins">
                TESTIMONI
            </span>
            <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6 font-poppins leading-tight">
                Apa Kata Mereka?
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto font-light">
                Ribuan pelanggan telah membuktikan hasil nyata perawatan di SkinLogic. Berikut adalah cerita mereka.
            </p>
        </div>

        {{-- SWIPER CONTAINER --}}
        <div class="relative px-4 md:px-12">
            <div class="swiper testimonialSwiper !pb-16">
                <div class="swiper-wrapper">
                    
                    @php
                        $testimonials = [
                            [
                                'name' => 'Sarah Amalia',
                                'role' => 'MAHASISWI',
                                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                                'content' => 'Awalnya ragu karena kulitku sensitif banget. Tapi setelah konsul dan coba treatment acne, jerawat beneran kempes dalam 3 hari! Bakal langganan terus sih ini.',
                                'rating' => 5
                            ],
                            [
                                'name' => 'Dina Kusuma',
                                'role' => 'KARYAWAN SWASTA',
                                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                                'content' => 'Pelayanannya juara! Terapisnya ramah dan dokter menjelaskan detail banget. Wajah jadi glowing dan lebih sehat setelah rutin facial di sini.',
                                'rating' => 5
                            ],
                            [
                                'name' => 'Rina Wulandari',
                                'role' => 'IBU RUMAH TANGGA',
                                'image' => 'https://randomuser.me/api/portraits/women/90.jpg',
                                'content' => 'Tempatnya nyaman dan bersih banget. Suka sama produk serumnya, ringan tapi ngefek banget buat mudarin flek hitam. Recommended banget pokoknya!',
                                'rating' => 5
                            ],
                             [
                                'name' => 'Bella Puspita',
                                'role' => 'MODEL',
                                'image' => 'https://randomuser.me/api/portraits/women/32.jpg',
                                'content' => 'Facial di SkinLogic beneran bikin relax. Hasilnya instan glowing dan gak merah-merah. Cocok banget buat persiapan sebelum photoshoot.',
                                'rating' => 5
                            ]
                        ];
                    @endphp

                    @foreach($testimonials as $testi)
                    <div class="swiper-slide h-auto">
                        <div class="bg-white p-10 rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:shadow-[0_20px_50px_-10px_rgba(0,0,0,0.12)] border border-gray-100 transition-all duration-300 flex flex-col items-center text-center h-full relative group">
                            
                            {{-- Foto Profil --}}
                            <div class="relative mb-6">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-[5px] border-white shadow-lg">
                                    <img src="{{ $testi['image'] }}" 
                                         alt="{{ $testi['name'] }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                {{-- Icon Quote Kuning (Mirip Referensi) --}}
                                <div class="absolute -bottom-2 -right-2 bg-yellow-400 text-white w-9 h-9 rounded-full flex items-center justify-center shadow-md border-2 border-white">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017V5H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM5.0166 21L5.0166 18C5.0166 16.8954 5.91203 16 7.0166 16H10.0166C10.5689 16 11.0166 15.5523 11.0166 15V9C11.0166 8.44772 10.5689 8 10.0166 8H6.0166C5.46432 8 5.0166 8.44772 5.0166 9V11C5.0166 11.5523 4.56889 12 4.0166 12H3.0166V5H13.0166V15C13.0166 18.3137 10.3303 21 7.0166 21H5.0166Z"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Rating Bintang --}}
                            <div class="flex gap-1 mb-6 text-yellow-400">
                                @for($i = 0; $i < $testi['rating']; $i++)
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>

                            {{-- Isi Testimoni --}}
                            <p class="text-gray-600 mb-8 italic leading-relaxed text-base font-light flex-grow">
                                "{{ $testi['content'] }}"
                            </p>

                            {{-- Nama & Role --}}
                            <div>
                                <h4 class="font-bold text-slate-900 text-xl font-poppins mb-1">{{ $testi['name'] }}</h4>
                                <span class="text-xs text-blue-600 font-bold uppercase tracking-widest">{{ $testi['role'] }}</span>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Pagination Dots --}}
                <div class="swiper-pagination !bottom-0"></div>
            </div>
            
            {{-- Navigation Arrows (Optional - di luar container card) --}}
            <div class="swiper-button-prev !text-[#001a4d] !w-12 !h-12 !bg-white !rounded-full !shadow-lg hover:!bg-gray-50 transition-colors after:!text-lg hidden md:flex"></div>
            <div class="swiper-button-next !text-[#001a4d] !w-12 !h-12 !bg-white !rounded-full !shadow-lg hover:!bg-gray-50 transition-colors after:!text-lg hidden md:flex"></div>
        </div>

    </div>
</section>

{{-- Script Swiper --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".testimonialSwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 40,
            },
        },
    });
</script>