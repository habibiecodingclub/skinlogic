@extends('landing.index')

@section('content')

@include('landing.sections.header')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-br from-blue-50 via-white to-blue-50 pt-32 pb-32 -mt-6">
    <div class="absolute inset-0 opacity-75">
        <div class="absolute inset-0" style="background-image: url('{{ asset('images/herosection-on-detail.png') }}'); background-size: cover;"></div>
    </div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-regular text-gray-900 mb-4 font-poppins hover:text-white transition-colors">
                @if(isset($category))
                    Kategori: {{ $category->name }}
                @elseif(isset($tag))
                    Tag: {{ $tag->name }}
                @else
                    Artikel & Tips Perawatan Kulit
                @endif
            </h1>
            <div class="flex items-center justify-center gap-2 text-gray-900">
                <a href="/" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <span class="text-gray-900 font-regular font-poppins hover:text-white transition-colors">Artikel</span>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="relative -mt-20 pb-20">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        
        {{-- Featured Articles (Only on main page) --}}
        @if(!isset($category) && !isset($tag) && isset($featuredArticles) && $featuredArticles->count() > 0)
        <div class="mb-16">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 font-poppins">Artikel Terbaru</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($featuredArticles as $featured)
                        <a href="{{ route('artikel.show', $featured->slug) }}" class="group">
                            <div class="relative h-48 rounded-xl overflow-hidden mb-4">
                                @if($featured->featured_image)
                                    <img src="{{ Storage::url($featured->featured_image) }}" 
                                         alt="{{ $featured->image_alt ?? $featured->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/5"></div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                @if($featured->category)
                                <span class="absolute top-4 left-4 px-3 py-1 bg-primary text-white text-xs font-medium rounded-full">
                                    {{ $featured->category->name }}
                                </span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2 mb-2">
                                {{ $featured->title }}
                            </h3>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                {{ $featured->excerpt }}
                            </p>
                            <div class="flex items-center text-xs text-gray-500 gap-3">
                                <span>{{ $featured->published_at->format('d M Y') }}</span>
                                <span>•</span>
                                <span>{{ $featured->reading_time }} min read</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid lg:grid-cols-4 gap-8">
            {{-- Sidebar --}}
            <div class="lg:col-span-1 order-2 lg:order-1">
                {{-- Categories --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Kategori</h3>
                    <div class="space-y-2">
                        <a href="{{ route('artikel.index') }}" 
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors {{ !isset($category) && !isset($tag) ? 'bg-primary/10 text-primary' : 'text-gray-700' }}">
                            <span>Semua Artikel</span>
                            <span class="text-sm">{{ $articles->total() }}</span>
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('artikel.category', $cat->slug) }}" 
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors {{ isset($category) && $category->id === $cat->id ? 'bg-primary/10 text-primary' : 'text-gray-700' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="text-sm">{{ $cat->articles_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Search --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Cari Artikel</h3>
                    <form action="{{ route('artikel.index') }}" method="GET">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari artikel..."
                                   class="w-full px-4 py-3 pr-10 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Articles Grid --}}
            <div class="lg:col-span-3 order-1 lg:order-2">
                @if($articles->count() > 0)
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                    @foreach($articles as $article)
                    <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col">
                        {{-- Image --}}
                        <a href="{{ route('artikel.show', $article->slug) }}" class="relative h-48 overflow-hidden group">
                            @if($article->featured_image)
                                <img src="{{ Storage::url($article->featured_image) }}" 
                                     alt="{{ $article->image_alt ?? $article->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/5"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            @if($article->category)
                            <span class="absolute top-4 left-4 px-3 py-1 bg-primary text-white text-xs font-medium rounded-full">
                                {{ $article->category->name }}
                            </span>
                            @endif
                        </a>

                        {{-- Content --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <a href="{{ route('artikel.show', $article->slug) }}">
                                <h3 class="text-xl font-bold text-gray-900 hover:text-primary transition-colors line-clamp-2 mb-3">
                                    {{ $article->title }}
                                </h3>
                            </a>
                            
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                                {{ $article->excerpt }}
                            </p>

                            {{-- Meta --}}
                            <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $article->published_at->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>{{ $article->views }}</span>
                                    </div>
                                    <span>{{ $article->reading_time }} min</span>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="flex justify-center">
                    {{ $articles->links() }}
                </div>
                @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Artikel</h3>
                    <p class="text-gray-600">Artikel akan segera hadir di kategori ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@include('landing.sections.footer')

@endsection