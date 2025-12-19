<section id="lokasi" class="py-24 bg-gray-50 relative overflow-hidden">
    
    {{-- Background Pattern (Optional) --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('{{ asset('images/pattern-dot.png') }}');"></div>

    <div class="container mx-auto px-6 max-w-7xl relative z-10">
        
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <span class="inline-block py-1 px-3 rounded-full bg-blue-100 text-blue-700 text-xs font-bold tracking-widest uppercase mb-4 font-poppins">
                LOKASI KAMI
            </span>
            <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6 font-poppins leading-tight">
                Kunjungi Klinik Terdekat
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto font-poppins font-light">
                Kami hadir di 3 kota besar untuk melayani kebutuhan perawatan kulit Anda. Temukan SkinLogic di kota Anda.
            </p>
        </div>

        {{-- Grid Lokasi --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            @php
                $locations = [
                    [
                        'city' => 'Makassar',
                        'address' => 'Jl. Pengayoman No. 123, Panakkukang, Kota Makassar.',
                        'phone' => '6281234567890', // Format 62...
                        'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.654727827827!2d119.4433!3d-5.1583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMDknMjkuOSJTIDExOcKwMjYnMzUuOSJF!5e0!3m2!1sen!2sid!4v1600000000000!5m2!1sen!2sid', // Ganti dengan embed map asli
                        'map_url' => 'https://maps.google.com/?q=SkinLogic+Makassar' // Link ke Google Maps
                    ],
                    [
                        'city' => 'Bone',
                        'address' => 'Jl. Jend. Sudirman No. 45, Watampone, Kab. Bone.',
                        'phone' => '6285298765432',
                        'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.6!2d120.33!3d-4.54!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMDknMjkuOSJTIDExOcKwMjYnMzUuOSJF!5e0!3m2!1sen!2sid!4v1600000000000!5m2!1sen!2sid',
                        'map_url' => 'https://maps.google.com/?q=SkinLogic+Bone'
                    ],
                    [
                        'city' => 'Polman',
                        'address' => 'Jl. Andi Depu No. 88, Polewali, Kab. Polewali Mandar.',
                        'phone' => '6281311223344',
                        'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3978.6!2d119.33!3d-3.43!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMDknMjkuOSJTIDExOcKwMjYnMzUuOSJF!5e0!3m2!1sen!2sid!4v1600000000000!5m2!1sen!2sid',
                        'map_url' => 'https://maps.google.com/?q=SkinLogic+Polman'
                    ]
                ];
            @endphp

            @foreach($locations as $loc)
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group flex flex-col h-full border border-gray-100">
                
                {{-- Bagian Peta --}}
                <div class="relative h-64 w-full bg-gray-100 overflow-hidden">
                    <iframe 
                        src="{{ $loc['map_embed'] }}" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        class="w-full h-full grayscale group-hover:grayscale-0 transition-all duration-700 opacity-90 group-hover:opacity-100">
                    </iframe>
                    
                    {{-- Overlay Gradient --}}
                    <div class="absolute inset-0 pointer-events-none border-b border-gray-200"></div>
                </div>

                {{-- Konten --}}
                <div class="p-8 flex flex-col flex-grow">
                    
                    {{-- Link Selengkapnya (Baru) --}}
                    <div class="mb-4">
                        <a href="{{ $loc['map_url'] }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold font-poppins text-blue-600 uppercase tracking-wider hover:text-blue-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Lihat Peta Selengkapnya
                        </a>
                    </div>

                    <h3 class="text-2xl font-bold font-poppins text-slate-900 mb-3">{{ $loc['city'] }}</h3>
                    
                    <p class="text-gray-600 mb-8 leading-relaxed font-light font-poppins text-sm flex-grow">
                        {{ $loc['address'] }}
                    </p>

                    {{-- Tombol Kontak --}}
                    <a href="https://wa.me/{{ $loc['phone'] }}?text=Halo%20Admin%20SkinLogic%20Cabang%20{{ $loc['city'] }}" 
                       target="_blank" 
                       class="flex items-center justify-center gap-3 w-full py-3.5 bg-slate-900 text-white font-semiboldfont-poppins rounded-xl hover:bg-slate-800 hover:-translate-y-1 transition-all duration-300 shadow-md">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Hubungi Cabang Ini
                    </a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>