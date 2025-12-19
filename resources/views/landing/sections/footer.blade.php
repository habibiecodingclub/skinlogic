<footer id="footer" class="bg-skinlogic text-gray-200">
    {{-- Top Divider --}}
    <div class="h-1 bg-gradient-to-r from-blue-900 via-blue-600 to-blue-900"></div>

    <div class="max-w-7xl mx-auto px-6 md:px-12 py-14 grid gap-10 md:grid-cols-3">

        <!-- Brand -->
    <div>
        <div class="flex items-center gap-3 mb-4">
            <!-- Logo -->
            <img
                src="{{ asset('images/brandSL.png') }}"
                alt="SkinLogic Logo"
                class="w-10 h-10 object-contain"
            />

            <!-- Brand Text -->
            <h3 class="text-2xl font-bold text-white tracking-wide font-poppins leading-none">
                SkinLogic</span>
            </h3>
        </div>

        <p class="text-sm leading-relaxed text-white-400 max-w-sm">
            Klinik perawatan kulit terpercaya dengan pendekatan medis, teknologi modern,
            dan tenaga profesional berpengalaman.
        </p>
    </div>

        <!-- Navigasi -->
        <div>
            <h4 class="text-lg font-semibold text-white mb-5 border-l-4 border-skinlogic-accent pl-3">
                Navigasi
            </h4>
            <ul class="space-y-3 text-sm">
                @foreach ([
                    '#home' => 'Home',
                    '#kenapa' => 'Kenapa SkinLogic',
                    '#offerings' => 'Layanan',
                    '#promo' => 'Promo',
                    '#testimonials' => 'Testimoni',
                    '#kunjungi' => 'Kunjungi Klinik',
                    '#artikel' => 'Artikel'
                ] as $link => $label)
                    <li>
                        <a href="{{ $link }}"
                           class="group flex items-center gap-2 text-gray-300 hover:text-white transition">
                            <span class="h-1.5 w-1.5 rounded-full bg-skinlogic-accent opacity-0 group-hover:opacity-100 transition"></span>
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Kontak -->
        <div>
            <h4 class="text-lg font-semibold text-white mb-5 border-l-4 border-skinlogic-accent pl-3">
                Kontak
            </h4>
            <ul class="space-y-3 text-sm text-gray-300">
                <li class="flex gap-2">
                    <span>📍</span>
                    <span>Jl. Perawatan No. 123, Makassar</span>
                </li>
                <li class="flex gap-2">
                    <span>📞</span>
                    <span>0812-3456-7890</span>
                </li>
                <li class="flex gap-2">
                    <span>✉️</span>
                    <span>info@skinlogic.com</span>
                </li>
            </ul>

            <!-- Social -->
            <div class="flex gap-4 mt-6">
                <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10 hover:bg-skinlogic-accent text-white transition">
                    🌐
                </a>
                <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10 hover:bg-skinlogic-accent text-white transition">
                    📸
                </a>
                <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10 hover:bg-skinlogic-accent text-white transition">
                    🐦
                </a>
            </div>
        </div>

    </div>

    <!-- Bottom -->
    <div class="border-t border-white/10 py-6 text-center text-sm text-gray-400">
        © {{ date('Y') }} <span class="text-white font-medium">SkinLogic</span>. All rights reserved.
    </div>
</footer>
