@extends('landing.index')

@section('title', $article->meta_title . ' - SkinLogic Clinic')
@section('meta_description', $article->meta_description)

@section('content')

@include('landing.sections.header')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-br from-blue-50 via-white to-blue-50 pt-32 pb-16 -mt-6">
    <div class="absolute inset-0 opacity-75">
        <div class="absolute inset-0" style="background-image: url('{{ asset('images/herosection-on-detail.png') }}'); background-size: cover;"></div>
    </div>
    <div class="max-w-4xl mx-auto px-6 relative z-10">
        {{-- Breadcrumb --}}
    
        {{-- Title --}}
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 font-poppins leading-tight">
            {{ $article->title }}
        </h1>

        {{-- Meta Info --}}
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
            @if($article->category)
            <a href="{{ route('artikel.category', $article->category->slug) }}" 
               class="px-4 py-1.5 bg-primary text-white rounded-full hover:bg-primary/90 transition-colors">
                {{ $article->category->name }}
            </a>
            @endif
            
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>{{ $article->author->name }}</span>
            </div>

            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $article->published_at->format('d M Y') }}</span>
            </div>

            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $article->reading_time }} min read</span>
            </div>

            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>{{ $article->views }} views</span>
            </div>
        </div>
    </div>
</section>

{{-- Article Content --}}
<article class="relative -mt-8 pb-20">
    <div class="max-w-4xl mx-auto px-6">
        {{-- Featured Image --}}
        @if($article->featured_image)
        <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-12">
            <img src="{{ Storage::url($article->featured_image) }}" 
                 alt="{{ $article->image_alt ?? $article->title }}"
                 class="w-full h-auto">
        </div>
        @endif

        {{-- Content --}}
        <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 mb-12">
            {{-- Excerpt --}}
            @if($article->excerpt)
            <div class="text-xl text-gray-700 leading-relaxed mb-8 pb-8 border-b border-gray-200 font-medium">
                {{ $article->excerpt }}
            </div>
            @endif

            {{-- Article Body --}}
            <div class="prose prose-lg max-w-none prose-headings:font-poppins prose-headings:font-bold prose-h2:text-3xl prose-h2:mt-12 prose-h2:mb-6 prose-h3:text-2xl prose-h3:mt-8 prose-h3:mb-4 prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-primary prose-a:no-underline hover:prose-a:underline prose-img:rounded-xl prose-img:shadow-lg prose-strong:text-gray-900 prose-ul:my-6 prose-ol:my-6">
                {!! $article->content !!}
            </div>

            {{-- Tags --}}
            @if($article->tags->count() > 0)
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Tags:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($article->tags as $tag)
                    <a href="{{ route('artikel.tag', $tag->slug) }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-primary hover:text-white transition-colors">
                        #{{ $tag->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Share Buttons --}}
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Bagikan Artikel:</h3>
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('artikel.show', $article->slug)) }}" 
                       target="_blank"
                       class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('artikel.show', $article->slug)) }}&text={{ urlencode($article->title) }}" 
                       target="_blank"
                       class="flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                        Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('artikel.show', $article->slug)) }}" 
                       target="_blank"
                       class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>

        {{-- Related Articles --}}
        @if($relatedArticles->count() > 0)
        <div class="bg-gradient-to-br from-gray-50 to-blue-50/30 rounded-3xl p-8 md:p-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 font-poppins">Artikel Terkait</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                <a href="{{ route('artikel.show', $related->slug) }}" class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="relative h-48 overflow-hidden">
                        @if($related->featured_image)
                            <img src="{{ Storage::url($related->featured_image) }}" 
                                 alt="{{ $related->image_alt ?? $related->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/5"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2 mb-2">
                            {{ $related->title }}
                        </h3>
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $related->excerpt }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Back Button --}}
        <div class="mt-12 text-center">
            <a href="{{ route('artikel.index') }}" 
               class="inline-flex items-center gap-2 px-8 py-4 bg-white text-gray-900 border-2 border-gray-300 rounded-full font-semibold hover:border-primary hover:text-primary transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Artikel
            </a>
        </div>
    </div>
</article>

@include('landing.sections.footer')

@endsection