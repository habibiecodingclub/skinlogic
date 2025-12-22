{{-- resources/views/landing/pages/treatment-detail.blade.php --}}
@extends('layouts.landing')

@section('content')
    @include('landing.sections.header')

    {{-- Hero Section with Breadcrumb --}}
    <section class="relative bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-[70vh] -mt-6 flex items-center">
        <div class="absolute inset-0 opacity-75">
            <div class="absolute inset-0" style="background-image: url('{{ asset('images/herosection-on-detail.png') }}'); background-size: cover;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-regular text-gray-900 mb-4 font-poppins hover:text-blue-600 transition-colors">
                    {{ $treatment['title'] }}
                </h1>
                <div class="flex items-center justify-center gap-2 text-gray-900 font-poppins hover:text-blue-600 transition-colors">
                    <a href="/" class="hover:text-gray-900 transition-colors">Home</a>
                    <span>/</span>
                    <a href="{{ route('perawatan.index') }}" class="hover:text-blue-600 font-poppins transition-colors">Perawatan</a>
                    <span>/</span>
                    <span class="text-gray-900 font-medium hover:text-blue-600 font-poppins transition-colors">{{ $treatment['title'] }} </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Treatment Detail Section --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid lg:grid-cols-2 gap-12 items-start mb-20">

                {{-- Left Side - Content --}}
                <div class="space-y-8">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-poppins">
                            {{ $treatment['title'] }}
                        </h2>

                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            <p class="mb-4">
                                {{ $treatment['description'] }}
                            </p>
                            <p>
                                {{ $treatment['long_description'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Treatment Info Cards --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-xl border border-blue-100">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-900 font-poppins">Durasi</h4>
                            </div>
                            <p class="text-gray-700 text-sm">{{ $treatment['duration'] }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-white p-5 rounded-xl border border-green-100">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-900 font-poppins">Harga</h4>
                            </div>
                            <p class="text-gray-700 text-sm">{{ $treatment['price_range'] }}</p>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-3">
                        @foreach($treatment['tags'] as $tag)
                        <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">
                            {{ $tag }}
                        </span>
                        @endforeach
                    </div>

                    {{-- CTA Button --}}
                    <div class="flex flex-wrap gap-4">
                        <a href="#reservasi"
                           class="inline-flex items-center gap-2 bg-[#001a4d] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#003d82] transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 font-poppins">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Reservasi Sekarang
                        </a>

                        <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan treatment {{ $treatment['title'] }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-green-500 text-white px-8 py-4 rounded-lg font-semibold hover:bg-green-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 font-poppins">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Hubungi WhatsApp
                        </a>
                    </div>
                </div>

                {{-- Right Side - Image --}}
                <div class="relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img src="{{ $treatment['image'] }}"
                             alt="{{ $treatment['title'] }}"
                             class="w-full h-auto object-cover">

                        {{-- Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
                    </div>

                    {{-- Floating Card - Benefits --}}
                    <div class="absolute -bottom-8 -left-8 bg-white rounded-xl shadow-2xl p-6 max-w-xs hidden lg:block">
                        <h3 class="font-bold text-gray-900 mb-3 font-poppins flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Manfaat Treatment
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-700">
                            @foreach(array_slice($treatment['benefits'], 0, 3) as $benefit)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ $benefit }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>

            {{-- Benefits Section for Mobile & Desktop Extended --}}
            <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 md:p-12 mb-20">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 font-poppins text-center">Manfaat Treatment</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($treatment['benefits'] as $benefit)
                    <div class="flex items-start gap-3 bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-gray-700">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Treatment Process Section --}}
            <div class="mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4 font-poppins">Proses Perawatan</h3>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Langkah-langkah treatment yang akan Anda dapatkan
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($treatment['process'] as $step)
                    <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-xl hover:shadow-lg transition-all duration-300 border border-blue-100 group">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#001a4d] to-[#003d82] text-white rounded-full flex items-center justify-center font-bold text-xl mb-4 font-poppins shadow-lg group-hover:scale-110 transition-transform">
                            {{ $step['step'] }}
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2 font-poppins">{{ $step['title'] }}</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- FAQ Section --}}
            <div class="mb-20" x-data="{ openFaq: null }">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4 font-poppins">Pertanyaan Umum</h3>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Jawaban untuk pertanyaan yang sering ditanyakan
                    </p>
                </div>

                <div class="max-w-3xl mx-auto space-y-4">
                    @foreach($treatment['faq'] as $index => $faq)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                        <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                                class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900 font-poppins pr-4">{{ $faq['q'] }}</span>
                            <svg class="w-5 h-5 text-gray-500 flex-shrink-0 transition-transform duration-300"
                                 :class="openFaq === {{ $index }} ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openFaq === {{ $index }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="px-6 pb-5 text-gray-600 leading-relaxed">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Related Treatments --}}
            @if(isset($relatedTreatments) && count($relatedTreatments) > 0)
            <div class="mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4 font-poppins">Treatment Lainnya</h3>
                    <p class="text-gray-600 font-poppins max-w-2xl mx-auto">
                        Perawatan lain dalam kategori {{ $treatment['category'] }} yang mungkin Anda minati
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($relatedTreatments as $related)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ $related['image'] }}"
                                 alt="{{ $related['title'] }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-6">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold font-poppins rounded-full mb-3">
                                {{ $related['category'] }}
                            </span>
                            <h4 class="text-xl font-bold text-gray-900 mb-2 font-poppins">{{ $related['title'] }}</h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $related['short_description'] }}
                            </p>
                            <a href="{{ route('perawatan.show', $related['slug']) }}"
                               class="inline-flex items-center gap-2 text-[#001a4d] font-semibold hover:gap-3 transition-all">
                                Lihat Detail
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Final CTA --}}
            <div class="bg-gradient-to-br from-[#001a4d] to-[#003d82] rounded-2xl p-12 text-center relative overflow-hidden">
                {{-- Decorative Elements --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-400 rounded-full opacity-10 -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-300 rounded-full opacity-10 translate-y-1/2 -translate-x-1/2"></div>

                <div class="relative z-10">
                    <h3 class="text-3xl md:text-4xl font-bold text-white mb-4 font-poppins">
                        Siap Untuk Transformasi Kulit Anda?
                    </h3>
                    <p class="text-blue-100 font-poppins mb-8 max-w-2xl mx-auto text-lg">
                        Jadwalkan konsultasi gratis dengan dokter kecantikan kami dan dapatkan treatment terbaik untuk kulit Anda
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="#reservasi"
                           class="inline-flex items-center gap-2 bg-white text-[#001a4d] px-8 py-4 rounded-full font-semibold font-poppins hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Buat Janji Sekarang
                        </a>
                        <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan treatment {{ $treatment['title'] }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-green-500 text-white font-poppins px-8 py-4 rounded-full font-semibold hover:bg-green-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
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
