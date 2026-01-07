@extends('landing.index')

@section('content')

{{-- ================= HEADER ================= --}}
@include('landing.sections.header')

{{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-blue-50 via-white to-blue-50 h-[420px] -mt-6 flex items-center overflow-hidden">

        <div class="absolute inset-0 opacity-75">
            <div class="absolute inset-0" style="background-image: url('{{ asset('images/herosection-on-detail.png') }}'); background-size: cover;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 w-full">
            <div class="text-center relative top-6 md:top-10">
                <h1 class="text-4xl md:text-5xl font-regular text-[#001a4d] mb-4 font-poppins hover:text-white transition-colors">
                    Tentang Kami
                </h1>
            </div>
        </div>
    </section>

{{-- About Section --}}
<section class="relative -mt-20 pb-10">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        {{-- Main Story Card --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-20">
            <div class="grid md:grid-cols-2 gap-0">
                {{-- Image Side --}}
                <div class="relative h-64 md:h-auto min-h-[400px]">
                    <img
                        src="{{ asset('images/produk8.jpeg') }}"
                        alt="SkinLogic Clinic"
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/20 to-transparent"></div>
                    {{-- Decorative Element --}}
                    <div class="absolute top-8 left-8">
                        <div class="bg-white/90 backdrop-blur-sm px-6 py-3 rounded-full shadow-lg">
                            <p class="text-primary font-poppins font-bold text-lg">Est. 2019</p>
                        </div>
                    </div>
                </div>

                {{-- Content Side --}}
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    <div class="inline-block px-5 py-2 bg-primary/10 text-primary text-sm font-semibold font-poppins rounded-full mb-6 self-start tracking-wide">
                        CERITA KAMI
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-poppins leading-tight">
                        Perjalanan Menuju<br>Kulit Sehat
                    </h2>
                    <div class="space-y-4">
                        <p class="text-gray-600 font-poppins leading-relaxed">
                            SkinLogic Clinic didirikan dengan visi untuk menghadirkan perawatan kulit berbasis ilmu pengetahuan dan teknologi terkini. Kami percaya bahwa setiap orang berhak mendapatkan kulit yang sehat dan terawat dengan pendekatan yang tepat.
                        </p>
                        <p class="text-gray-600 font-poppins leading-relaxed">
                            Dengan tim dokter spesialis bersertifikat dan peralatan medis modern, kami memberikan solusi personal untuk setiap kebutuhan kulit Anda. Dari konsultasi mendalam hingga perawatan lanjutan, semua dirancang dengan standar medis tertinggi dan hasil yang terukur.
                        </p>
                    </div>
                    {{-- Quick Stats --}}
                    <div class="grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-gray-200">
                        <div>
                            <p class="text-3xl font-bold font-poppins text-primary mb-1">1000+</p>
                            <p class="text-gray-600 font-poppins text-sm">Klien Puas</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold  font-poppins text-primary mb-1">5+</p>
                            <p class="text-gray-600 font-poppins text-sm">Tahun Pengalaman</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Values Section --}}
        <div class="mb-20">
            <div class="text-center mb-12">
                <div class="inline-block px-5 py-2 bg-primary/10 text-primary text-sm font-semibold font-poppins rounded-full mb-4 tracking-wide">
                    NILAI-NILAI KAMI
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 font-poppins">
                    Prinsip yang Kami Pegang
                </h2>
                <p class="text-gray-600 font-poppins max-w-2xl mx-auto">
                    Komitmen kami dalam memberikan layanan terbaik untuk kesehatan kulit Anda
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Value 1 --}}
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary/80 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-poppins">
                        Keamanan & Standar
                    </h3>
                    <p class="text-gray-600 font-poppins leading-relaxed">
                        Setiap prosedur mengikuti protokol medis ketat dengan peralatan tersertifikasi dan steril untuk keamanan maksimal.
                    </p>
                </div>

                {{-- Value 2 --}}
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary/80 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-poppins">
                        Berbasis Ilmu
                    </h3>
                    <p class="text-gray-600 font-poppins leading-relaxed">
                        Pendekatan evidence-based dengan riset terkini dalam dermatologi dan estetika medis untuk hasil optimal.
                    </p>
                </div>

                {{-- Value 3 --}}
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary/80 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-poppins">
                        Personal & Peduli
                    </h3>
                    <p class="text-gray-600 font-poppins leading-relaxed">
                        Setiap klien mendapat perhatian personal dengan rencana perawatan yang disesuaikan kebutuhan kulitnya.
                    </p>
                </div>
            </div>
        </div>


        {{-- Final CTA --}}
        <div class="bg-gradient-to-br from-[#001a4d] to-[#003d82] rounded-2xl p-12 text-center relative overflow-hidden">
            {{-- Decorative Elements --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-400 rounded-full opacity-10 -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-300 rounded-full opacity-10 translate-y-1/2 -translate-x-1/2"></div>

            <div class="relative z-10">
                <h3 class="text-3xl md:text-4xl font-bold text-white mb-4 font-poppins">
                    Siap Memulai Perjalanan Kulit Sehat?
                </h3>
                <p class="text-blue-100 font-poppins mb-8 max-w-2xl mx-auto text-lg">
                    Konsultasikan kebutuhan kulit Anda dengan dokter spesialis kami dan dapatkan solusi terbaik yang dipersonalisasi
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#reservasi"
                       class="inline-flex items-center gap-2 bg-white text-[#001a4d] px-8 py-4 rounded-full font-semibold font-poppins hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Buat Janji Sekarang
                    </a>
                    <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan treatment"
                       target="_blank"
                       class="inline-flex items-center gap-2 bg-green-500 text-white px-8 py-4 rounded-full font-semibold font-poppins hover:bg-green-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

@include('landing.sections.footer')
@endsection
