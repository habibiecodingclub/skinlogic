<section id="lokasi" class="py-20 bg-white">
    <div class="container mx-auto px-6 max-w-7xl">
        
        {{-- Header Section (Gaya konsisten dengan Produk/Artikel) --}}
        <div class="text-center mb-12">
            <span class="block text-xs font-extrabold font-poppins tracking-[0.2em] text-yellow-600 uppercase mb-3">
                KUNJUNGI KAMI
            </span>
            <h2 class="text-4xl md:text-5xl font-extrabold font-poppins text-slate-900">
                Lokasi Klinik SkinLogic
            </h2>
            <p class="text-gray-500 font-poppins mt-4 max-w-2xl mx-auto">
                Temukan perawatan terbaik di cabang terdekat Anda. Kami hadir di Makassar, Bone, dan Polman.
            </p>
        </div>

        {{-- Grid 3 Lokasi --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- Data Lokasi (Makassar, Bone, Polman) --}}
            @php
                $locations = [
                    [
                        'city' => 'MAKASSAR',
                        'address' => 'Jl. Pengayoman No. 123, Panakkukang, Kota Makassar.',
                        'phone' => '0812-3456-7890',
                        'map_link' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.766264626135!2d119.4439713!3d-5.1413162!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee2a475555555%3A0x5555555555555555!2sMakassar!5e0!3m2!1sid!2sid!4v1600000000000',
                    ],
                    [
                        'city' => 'BONE',
                        'address' => 'Jl. Jend. Sudirman No. 45, Watampone, Kab. Bone.',
                        'phone' => '0852-9876-5432',
                        'map_link' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3976.225763073718!2d120.329881!3d-4.542266!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbe4e4755555555%3A0x5555555555555555!2sWatampone!5e0!3m2!1sid!2sid!4v1600000000000',
                    ],
                    [
                        'city' => 'POLMAN',
                        'address' => 'Jl. Andi Depu No. 88, Polewali, Kab. Polewali Mandar.',
                        'phone' => '0813-1122-3344',
                        'map_link' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.473434672629!2d119.343434!3d-3.434343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d94343434343434%3A0x5555555555555555!2sPolewali!5e0!3m2!1sid!2sid!4v1600000000000',
                    ]
                ];
            @endphp

            @foreach($locations as $loc)
            <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
                
                {{-- Bagian Peta (Embed Google Maps) --}}
                <div class="h-64 w-full bg-gray-200 relative">
                    <iframe 
                        src="{{ $loc['map_link'] }}" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        class="grayscale group-hover:grayscale-0 transition-all duration-500">
                    </iframe>
                </div>

                {{-- Detail Lokasi --}}
                <div class="p-8">
                    {{-- Nama Kota --}}
                    <h3 class="text-2xl font-extrabold font-poppins text-slate-900 mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $loc['city'] }}
                    </h3>

                    {{-- Alamat --}}
                    <p class="text-gray-500  font-poppins mb-6 leading-relaxed">
                        {{ $loc['address'] }}
                    </p>

                    {{-- Tombol Kontak --}}
                    <a href="https://wa.me/{{ str_replace('-', '', $loc['phone']) }}" target="_blank" class="block w-full py-3 bg-slate-50 text-slate-900 font-bold font-poppins text-center rounded-xl border border-gray-200 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-colors duration-300">
                        Hubungi Cabang Ini
                    </a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>