{{-- resources/views/landing/components/article-card.blade.php --}}
<div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">
    {{-- Image --}}
    <div class="relative h-56 overflow-hidden">
        <img src="{{ $image }}" 
             alt="{{ $title }}" 
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        
        {{-- Category Badge --}}
        @if(isset($category))
            <div class="absolute top-4 left-4">
                <span class="px-4 py-1.5 bg-slate-900 text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                    {{ $category }}
                </span>
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-6 flex flex-col flex-grow">
        {{-- Title --}}
        <h3 class="text-xl font-extrabold text-slate-900 mb-3 line-clamp-2 hover:text-slate-900 transition-colors">
            <a href="{{ $url ?? '#' }}">{{ $title }}</a>
        </h3>
        
        {{-- Excerpt --}}
        <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3 flex-grow">
            {{ $excerpt }}
        </p>

        {{-- Meta Info --}}
        @if(isset($date) || isset($reading_time))
            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4 pb-4 border-b border-gray-200">
                @if(isset($date))
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $date }}</span>
                    </div>
                @endif
                @if(isset($reading_time))
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $reading_time }} min</span>
                    </div>
                @endif
            </div>
        @endif

        {{-- Baca Selengkapnya Button --}}
        <a href="{{ $url ?? '#' }}" 
           class="inline-flex items-center justify-center gap-2 w-full px-6 py-3 bg-slate-900 hover:bg-slate-900 text-white font-bold rounded-lg transition-all duration-300 shadow-md hover:shadow-xl group">
            <span>Baca Selengkapnya</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</div>