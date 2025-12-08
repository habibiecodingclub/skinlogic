<section id="kenapa" class="py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <!-- Header -->
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold font-poppins mb-4 text-gray-900">
                KENAPA MEMILIH SKINLOGIC ?
            </h2>
            <p class="text-gray-600 font-poppins max-w-2xl mx-auto">
                Kami berkomitmen untuk selalu memberikanmu pelayanan terbaik sebaik mungkin
            </p>
        </div>

        <!-- Content Grid -->
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Image Side with Curved Background -->
            <div class="relative" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
                <!-- Dark Blue Curved Background -->
                <div class="absolute inset-0 bg-[#1a2942] rounded-[80px] transform md:-translate-x-8"
                     :class="loaded ? 'opacity-100 scale-100' : 'opacity-0 scale-95'"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     style="z-index: 1;">
                </div>

                <!-- Image Container with Circular Mask -->
                <div class="relative z-10 flex justify-center items-center py-8">
                    <div class="w-80 h-80 md:w-96 md:h-96 rounded-full overflow-hidden shadow-2xl"
                         :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                         x-transition:enter="transition ease-out duration-700 delay-300"
                         x-transition:enter-start="opacity-0 translate-y-8"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <img src="{{ asset('images/treatment-skinlogic.jpg') }}"
                             alt="Treatment SkinLogic"
                             class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Features Side -->
            <div class="space-y-8" x-data="{ activeIndex: null }">
                <!-- Feature 1: Berpengalaman -->
                <div class="flex items-start group cursor-pointer"
                     @mouseenter="activeIndex = 0"
                     @mouseleave="activeIndex = null"
                     x-data="{ show: false }"
                     x-intersect="show = true">
                    <div class="flex-shrink-0 mr-6 transition-transform duration-300"
                         :class="activeIndex === 0 ? 'scale-110' : 'scale-100'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-500 delay-100"
                         x-transition:enter-start="opacity-0 -translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">
                        <!-- Lightbulb Icon -->
                        <div class="w-16 h-16 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                    </div>
                    <div x-cloak
                         x-transition:enter="transition ease-out duration-500 delay-200"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">
                        <h3 class="text-xl font-bold text-gray-900 font-poppins mb-2 transition-colors duration-300"
                            :class="activeIndex === 0 ? 'text-skinlogic' : ''">
                            Berpengalaman
                        </h3>
                        <p class="text-gray-600 font-poppins leading-relaxed">
                            Dengan lebih dari 15 tahun di dunia kecantikan, SkinLogic adalah destinasi terpercaya dan terlengkap untuk perawatan wajah, rambut, dan tubuh di Indonesia.
                        </p>
                    </div>
                </div>

                <!-- Feature 2: Pelayanan Ramah -->
                <div class="flex items-start group cursor-pointer"
                     @mouseenter="activeIndex = 1"
                     @mouseleave="activeIndex = null"
                     x-data="{ show: false }"
                     x-intersect="show = true">
                    <div class="flex-shrink-0 mr-6 transition-transform duration-300"
                         :class="activeIndex === 1 ? 'scale-110' : 'scale-100'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-500 delay-300"
                         x-transition:enter-start="opacity-0 -translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">
                        <!-- Hand Heart Icon -->
                        <div class="w-16 h-16 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                    </div>
                    <div x-cloak
                         x-transition:enter="transition ease-out duration-500 delay-400"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">
                        <h3 class="text-xl font-bold text-gray-900 font-poppins mb-2 transition-colors duration-300"
                            :class="activeIndex === 1 ? 'text-skinlogic' : ''">
                            Pelayanan Ramah
                        </h3>
                        <p class="text-gray-600 font-poppins leading-relaxed">
                            Staf kami selalu mengutamakan pelayanan personal dengan kenyamanan dan keramahan.
                        </p>
                    </div>
                </div>

                <!-- Feature 3: Teknologi Terbaru -->
                <div class="flex items-start group cursor-pointer"
                     @mouseenter="activeIndex = 2"
                     @mouseleave="activeIndex = null"
                     x-data="{ show: false }"
                     x-intersect="show = true">
                    <div class="flex-shrink-0 mr-6 transition-transform duration-300"
                         :class="activeIndex === 2 ? 'scale-110' : 'scale-100'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-500 delay-500"
                         x-transition:enter-start="opacity-0 -translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">
                        <!-- Chip/CPU Icon -->
                        <div class="w-16 h-16 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                    </div>
                    <div x-cloak
                         x-transition:enter="transition ease-out duration-500 delay-600"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">
                        <h3 class="text-xl font-bold text-gray-900 font-poppins mb-2 transition-colors duration-300"
                            :class="activeIndex === 2 ? 'text-skinlogic' : ''">
                            Teknologi Terbaru
                        </h3>
                        <p class="text-gray-600 font-poppins leading-relaxed">
                            Skinlogic menggunakan teknologi terbaik yang terbukti efektif, dengan standar medis yang ketat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
