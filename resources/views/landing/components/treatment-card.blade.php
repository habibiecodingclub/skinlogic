{{-- Treatment Card Component --}}
<div x-data="{ hovered: false }"
     @mouseenter="hovered = true"
     @mouseleave="hovered = false"
     class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-2xl group relative">

    <!-- Decorative Corners (Visible on Hover) -->
    <div class="absolute inset-0 pointer-events-none z-10">
        <!-- Top Left -->
        <svg class="absolute top-0 left-0 w-16 h-16 text-blue-600 transform transition-all duration-500"
             :class="hovered ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"
             viewBox="0 0 64 64" fill="currentColor">
            <path d="M0,0 L0,20 C0,8.954 8.954,0 20,0 L0,0 Z" opacity="0.3"/>
            <circle cx="12" cy="12" r="2"/>
            <circle cx="20" cy="8" r="1.5"/>
            <circle cx="8" cy="20" r="1.5"/>
        </svg>

        <!-- Top Right -->
        <svg class="absolute top-0 right-0 w-16 h-16 text-blue-600 transform transition-all duration-500"
             :class="hovered ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"
             viewBox="0 0 64 64" fill="currentColor">
            <path d="M64,0 L44,0 C55.046,0 64,8.954 64,20 L64,0 Z" opacity="0.3"/>
            <circle cx="52" cy="12" r="2"/>
        </svg>

        <!-- Bottom Left -->
        <svg class="absolute bottom-0 left-0 w-16 h-16 text-blue-600 transform transition-all duration-500"
             :class="hovered ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"
             viewBox="0 0 64 64" fill="currentColor">
            <path d="M0,64 L0,44 C0,55.046 8.954,64 20,64 L0,64 Z" opacity="0.3"/>
            <circle cx="12" cy="52" r="2"/>
        </svg>

        <!-- Bottom Right -->
        <svg class="absolute bottom-0 right-0 w-16 h-16 text-blue-600 transform transition-all duration-500"
             :class="hovered ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"
             viewBox="0 0 64 64" fill="currentColor">
            <path d="M64,64 L44,64 C55.046,64 64,55.046 64,44 L64,64 Z" opacity="0.3"/>
            <circle cx="52" cy="52" r="2"/>
        </svg>
    </div>

    <!-- Blue Border (Visible on Hover) -->
    <div class="absolute inset-0 border-4 rounded-lg transition-all duration-500 pointer-events-none"
         :class="hovered ? 'border-blue-600 opacity-100' : 'border-transparent opacity-0'">
    </div>

    <!-- Card Content -->
    <div class="p-8 relative z-20">
        <!-- Image -->
        <div class="flex justify-center mb-6">
            <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-gray-100 transition-transform duration-300"
                 :class="hovered ? 'scale-110 border-blue-100' : 'scale-100'">
                <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Title -->
        <h3 class="text-2xl font-bold text-gray-900 text-center mb-2 font-poppins">
            {{ $title }}
        </h3>

        <!-- Category -->
        <p class="text-center text-gray-600 font-medium mb-4">{{ $category }}</p>

        <!-- Description -->
        <p class="text-center text-gray-500 text-sm mb-8 leading-relaxed">
            {{ $description }}
        </p>

        <!-- Actions -->
        <div class="flex justify-center gap-6 text-sm font-semibold">
            <a href="{{ $appointmentUrl }}" class="text-gray-900 hover:text-blue-600 transition-colors duration-300 flex items-center gap-1 group/link">
                Buat janji
                <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ $detailUrl }}" class="text-gray-900 hover:text-blue-600 transition-colors duration-300 flex items-center gap-1 group/link">
                Detail
                <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
