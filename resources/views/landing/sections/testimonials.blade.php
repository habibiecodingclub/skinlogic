<section id="testimoni" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 max-w-7xl">
        
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <span class="block text-xs font-extrabold tracking-[0.2em] text-yellow-600 uppercase mb-3">
                TESTIMONI
            </span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900">
                Apa Kata Mereka?
            </h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto">
                Ribuan pelanggan telah membuktikan hasil nyata perawatan di SkinLogic. Berikut adalah cerita mereka.
            </p>
        </div>

        {{-- Grid Testimoni --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            @php
                $testimonials = [
                    [
                        'name' => 'Sarah Amalia',
                        'role' => 'Mahasiswi',
                        'image' => 'https://randomuser.me/api/portraits/women/44.jpg', // Gambar random wanita
                        'content' => 'Awalnya ragu karena kulitku sensitif banget. Tapi setelah konsul dan coba treatment acne, jerawat beneran kempes dalam 3 hari! Bakal langganan terus sih ini.',
                        'rating' => 5
                    ],
                    [
                        'name' => 'Dina Kusuma',
                        'role' => 'Karyawan Swasta',
                        'image' => 'https://randomuser.me/api/portraits/women/68.jpg', // Gambar random wanita
                        'content' => 'Pelayanannya juara! Terapisnya ramah dan dokter menjelaskan detail banget. Wajah jadi glowing dan lebih sehat setelah rutin facial di sini.',
                        'rating' => 5
                    ],
                    [
                        'name' => 'Rina Wulandari',
                        'role' => 'Ibu Rumah Tangga',
                        'image' => 'https://randomuser.me/api/portraits/women/90.jpg', // Gambar random wanita
                        'content' => 'Tempatnya nyaman dan bersih banget. Suka sama produk serumnya, ringan tapi ngefek banget buat mudarin flek hitam. Recommended banget pokoknya!',
                        'rating' => 5
                    ]
                ];
            @endphp

            @foreach($testimonials as $testi)
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 flex flex-col items-center text-center group">
                
                {{-- Foto Profil (Lingkaran dengan Border) --}}
                <div class="relative w-24 h-24 mb-6">
                    <div class="absolute inset-0 bg-blue-100 rounded-full transform rotate-6 group-hover:rotate-12 transition-transform duration-300"></div>
                    <img src="{{ $testi['image'] }}" 
                         alt="{{ $testi['name'] }}" 
                         class="relative w-full h-full object-cover rounded-full border-4 border-white shadow-md">
                    
                    {{-- Icon Kutip (Quote) Kecil --}}
                    <div class="absolute -bottom-2 -right-2 bg-yellow-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017V5H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM5.0166 21L5.0166 18C5.0166 16.8954 5.91203 16 7.0166 16H10.0166C10.5689 16 11.0166 15.5523 11.0166 15V9C11.0166 8.44772 10.5689 8 10.0166 8H6.0166C5.46432 8 5.0166 8.44772 5.0166 9V11C5.0166 11.5523 4.56889 12 4.0166 12H3.0166V5H13.0166V15C13.0166 18.3137 10.3303 21 7.0166 21H5.0166Z"/>
                        </svg>
                    </div>
                </div>

                {{-- Rating Bintang --}}
                <div class="flex gap-1 mb-4 text-yellow-400">
                    @for($i = 0; $i < $testi['rating']; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>

                {{-- Isi Testimoni --}}
                <p class="text-gray-600 mb-6 italic leading-relaxed text-sm">
                    "{{ $testi['content'] }}"
                </p>

                {{-- Nama & Role --}}
                <div>
                    <h4 class="font-extrabold text-slate-900 text-lg">{{ $testi['name'] }}</h4>
                    <span class="text-xs text-blue-500 font-bold uppercase tracking-wider">{{ $testi['role'] }}</span>
                </div>

            </div>
            @endforeach

        </div>
    </div>
</section>