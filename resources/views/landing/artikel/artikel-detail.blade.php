@extends('landing.index')

@section('title', $article->meta_title . ' - SkinLogic Clinic')
@section('meta_description', $article->meta_description)

@section('content')

@include('landing.sections.header')

{{-- Hero Section --}}
<section class="relative bg-blue-50 pt-40 pb-32 -mt-6 overflow-hidden">
    {{-- Background Image dengan Blur --}}
    <div class="absolute inset-0 z-0">
        {{-- Note: blur-sm memberikan efek blur halus. brightness-90 agar teks putih/hitam tetap kontras --}}
        <div class="absolute inset-0 bg-cover bg-center blur-sm scale-105 brightness-95" 
             style="background-image: url('{{ asset('images/herosection-on-detail.png') }}');">
        </div>
        {{-- Gradient Overlay halus agar text lebih pop-up --}}
        <div class="absolute inset-0 bg-gradient-to-b from-white/60 via-white/80 to-blue-50"></div>
    </div>

    <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
        {{-- Category Badge --}}
        @if($article->category)
        <div class="mb-6">
            <a href="{{ route('artikel.category', $article->category->slug) }}" 
               class="inline-block px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-full shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                {{ $article->category->name }}
            </a>
        </div>
        @endif

        {{-- Title --}}
        <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-8 font-poppins leading-tight tracking-tight">
            {{ $article->title }}
        </h1>

        {{-- Meta Info --}}
        <div class="flex flex-wrap justify-center items-center gap-x-6 gap-y-3 text-sm font-medium text-slate-600">
            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-white rounded-full shadow-sm">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span>{{ $article->author->name }}</span>
            </div>

            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-white rounded-full shadow-sm">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span>{{ $article->published_at->format('d M Y') }}</span>
            </div>

            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-white rounded-full shadow-sm">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span>{{ $article->reading_time }} min read</span>
            </div>

            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-white rounded-full shadow-sm">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <span>{{ $article->views }} views</span>
            </div>
        </div>
    </div>
</section>

{{-- Article Content Wrapper --}}
<article class="relative -mt-24 pb-20 z-20">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        
        {{-- Content Card --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            
            {{-- Featured Image (Inside Card now for cleaner look) --}}
            @if($article->featured_image)
            <div class="w-full h-[400px] md:h-[500px] relative">
                <img src="{{ Storage::url($article->featured_image) }}" 
                     alt="{{ $article->image_alt ?? $article->title }}"
                     class="w-full h-full object-cover">
            </div>
            @endif

            <div class="p-8 md:p-12 lg:px-16">
                {{-- Excerpt --}}
                @if($article->excerpt)
                <div class="bg-blue-50/50 p-6 rounded-2xl border-l-4 border-blue-500 mb-10">
                    <p class="text-lg md:text-xl text-slate-700 italic font-medium leading-relaxed">
                        "{{ $article->excerpt }}"
                    </p>
                </div>
                @endif

                {{-- Article Body --}}
                <div class="prose prose-lg prose-slate max-w-none 
                    prose-headings:font-poppins prose-headings:font-bold prose-headings:text-slate-900 
                    prose-h2:text-3xl prose-h2:mt-12 prose-h2:mb-6 
                    prose-h3:text-2xl prose-h3:mt-8 prose-h3:mb-4 
                    prose-p:text-slate-600 prose-p:leading-8 
                    prose-a:text-blue-600 prose-a:font-semibold prose-a:no-underline hover:prose-a:text-blue-700 hover:prose-a:underline 
                    prose-img:rounded-2xl prose-img:shadow-lg prose-img:my-8
                    prose-blockquote:border-l-blue-500 prose-blockquote:bg-gray-50 prose-blockquote:py-2 prose-blockquote:px-6 prose-blockquote:rounded-r-lg
                    prose-li:text-slate-600">
                    {!! $article->content !!}
                </div>

                {{-- Tags --}}
                @if($article->tags->count() > 0)
                <div class="mt-14 pt-8 border-t border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Tags Related</h3>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->tags as $tag)
                        <a href="{{ route('artikel.tag', $tag->slug) }}" 
                           class="px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 rounded-full text-sm font-medium hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300">
                            #{{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Share Buttons --}}
                <div class="mt-10 pt-8 border-t border-gray-100">
                    <h3 class="text-center text-sm font-bold text-gray-900 uppercase tracking-wider mb-6">Bagikan Artikel Ini</h3>
                    <div class="flex flex-wrap justify-center gap-4">
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('artikel.show', $article->slug)) }}" 
                           target="_blank"
                           class="flex items-center gap-2 px-6 py-3 bg-[#1877F2] text-white rounded-xl hover:shadow-lg hover:-translate-y-1 transition-all duration-300 font-medium">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                        {{-- Twitter / X --}}
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('artikel.show', $article->slug)) }}&text={{ urlencode($article->title) }}" 
                           target="_blank"
                           class="flex items-center gap-2 px-6 py-3 bg-black text-white rounded-xl hover:shadow-lg hover:-translate-y-1 transition-all duration-300 font-medium">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            Twitter
                        </a>
                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('artikel.show', $article->slug)) }}" 
                           target="_blank"
                           class="flex items-center gap-2 px-6 py-3 bg-[#25D366] text-white rounded-xl hover:shadow-lg hover:-translate-y-1 transition-all duration-300 font-medium">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Articles --}}
        @if($relatedArticles->count() > 0)
        <div class="mt-20">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 font-poppins">Artikel Terkait</h2>
                <a href="{{ route('artikel.index') }}" class="text-blue-600 font-semibold hover:text-blue-700 hover:underline">Lihat Semua</a>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($relatedArticles as $related)
                <a href="{{ route('artikel.show', $related->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-2">
                    <div class="relative h-56 overflow-hidden">
                        @if($related->featured_image)
                            <img src="{{ Storage::url($related->featured_image) }}" 
                                 alt="{{ $related->image_alt ?? $related->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-50"></div>
                        @endif
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors"></div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs text-blue-600 font-semibold mb-2 uppercase tracking-wide">
                            {{ $related->category->name ?? 'Article' }}
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 mb-3 leading-snug">
                            {{ $related->title }}
                        </h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4">
                            {{ $related->excerpt }}
                        </p>
                        <div class="flex items-center text-xs text-slate-400">
                            <span>{{ $related->published_at->format('d M Y') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $related->reading_time }} min read</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Back Button --}}
        <div class="mt-16 mb-8 text-center">
            <a href="{{ route('artikel.index') }}" 
               class="inline-flex items-center gap-2 px-8 py-3 bg-white text-slate-700 border border-slate-200 rounded-full font-semibold shadow-sm hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition-all group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Artikel
            </a>
        </div>
    </div>
</article>

@include('landing.sections.footer')

@endsection