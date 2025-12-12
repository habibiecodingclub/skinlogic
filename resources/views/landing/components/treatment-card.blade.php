<div x-data="{ hovered: false }"
     @mouseenter="hovered = true"
     @mouseleave="hovered = false"
     class="bg-white border border-gray-200 rounded-lg transition-all duration-300 hover:shadow-2xl relative h-full min-h-[560px] flex flex-col overflow-hidden">

    <!-- Hover blue border arham coba deh okeoke-->
    <div class="absolute inset-0 border-[3px] rounded-lg transition-all duration-500 pointer-events-none border-blue-600 z-30"
         :class="hovered ? 'opacity-100' : 'opacity-0'"></div>

    <!-- Ornament di paling atas -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 z-10 pointer-events-none w-full flex justify-center">
        <img src="{{ asset('images/ornament-treatment.png') }}"
             alt="ornament"
             class="w-[500px] h-auto object-contain select-none transition-all duration-700"
             :class="hovered ? 'opacity-90 scale-100' : 'opacity-0 scale-95'" />
    </div>

    <!-- Card Content -->
    <div class="relative z-20 p-8 flex flex-col h-full">
        <!-- Gambar bulat -->
        <div class="relative flex justify-center mb-6">
            <div class="w-40 h-40 md:w-44 md:h-44 rounded-full overflow-hidden border-4 border-gray-100 z-20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                 :class="hovered ? 'scale-110 border-blue-100 shadow-2xl' : 'scale-100'">
                <img src="{{ $image }}" alt="{{ $title }}"
                     class="w-full h-full object-cover transition-transform duration-500"
                     :class="hovered ? 'scale-105' : 'scale-100'">
            </div>
        </div>

        <!-- Title -->
        <h3 class="text-2xl font-bold text-gray-900 text-center mb-2 font-poppins relative z-20 bg-white/80 backdrop-blur-sm rounded-lg py-1">
            {{ $title }}
        </h3>

        <!-- Category -->
        <p class="text-center text-gray-600 font-medium mb-4 relative z-20 bg-white/70 backdrop-blur-sm rounded-lg py-1">
            {{ $category }}
        </p>

        <!-- Description -->
        <div class="text-center text-gray-500 text-sm mb-8 leading-relaxed flex-grow relative z-20 bg-white/70 backdrop-blur-sm rounded-lg p-3">
            {{ $description }}
        </div>

        <!-- Actions -->
    <div class="flex justify-center gap-6 text-sm font-semibold mt-auto pt-2 relative z-20">
            <a href="{{ $appointmentUrl }}"
               class="text-gray-900 hover:text-blue-600 transition-all duration-300 flex items-center gap-1 group/link px-4 py-2 rounded-lg hover:bg-blue-50 bg-white/80 backdrop-blur-sm">
                Buat janji
                <svg class="w-4 h-4 transform transition-transform duration-300 group-hover/link:translate-x-1"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ $detailUrl }}"
               class="text-gray-900 hover:text-blue-600 transition-all duration-300 flex items-center gap-1 group/link px-4 py-2 rounded-lg hover:bg-blue-50 bg-white/80 backdrop-blur-sm">
                Detail
                <svg class="w-4 h-4 transform transition-transform duration-300 group-hover/link:translate-x-1"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- tess -->
