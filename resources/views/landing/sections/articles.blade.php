<!-- resource/views/landing/sections/articles.blade.php -->

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 max-w-6xl">
        
        {{-- Header Section --}}
        <div class="text-center mb-12">
            <span class="block text-xs font-poppins font-extrabold tracking-[0.2em] text-yellow-600 uppercase mb-3">
                BLOG & INFO
            </span>
            <h2 class="text-4xl md:text-5xl font-poppins font-extrabold text-slate-900">
                Artikel & Berita Terbaru
            </h2>
        </div>

        {{-- Grid 3 Kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @if(isset($articles) && $articles->count() > 0)
                
                {{-- Artikel dari Database --}}
                @foreach($articles as $article)
                    @include('landing.components.article-card', [
                        'image' => $article->featured_image 
                            ? asset('storage/' . $article->featured_image) 
                            : asset('images/treatment1.jpeg'),
                        'title' => $article->title,
                        'category' => $article->category?->name ?? 'Umum',
                        'excerpt' => $article->excerpt,
                        'url' => route('artikel.show', $article->slug),
                        'date' => $article->published_at->format('d M Y'),
                        'reading_time' => $article->reading_time ?? 5
                    ])
                @endforeach

            @else
                
                {{-- Data Dummy --}}
                @include('landing.components.article-card', [
                    'image' => asset('images/treatment1.jpeg'),
                    'category' => 'Tips',
                    'title' => '5 Tips Merawat Kulit di Musim Panas',
                    'excerpt' => 'Musim panas bisa bikin kulitmu kering. Yuk simak tips menjaga kelembapan kulit agar tetap glowing.',
                    'url' => '#'
                ])

                @include('landing.components.article-card', [
                    'image' => asset('images/treatment2.jpeg'),
                    'category' => 'Promo',
                    'title' => 'Promo Spesial Akhir Tahun',
                    'excerpt' => 'Dapatkan diskon hingga 40% untuk semua layanan SkinLogic selama bulan Desember ini.',
                    'url' => '#'
                ])

                @include('landing.components.article-card', [
                    'image' => asset('images/treatment1.jpeg'),
                    'category' => 'Review',
                    'title' => 'Review Treatment Laser SkinLogic',
                    'excerpt' => 'Simak pengalaman pelanggan setelah mencoba treatment laser untuk mengatasi bekas jerawat.',
                    'url' => '#'
                ])

            @endif
        </div>

        {{-- Tombol Lihat Semua Artikel --}}
        @if(isset($articles) && $articles->count() > 0)
            <div class="text-center mt-12">
                <a href="{{ route('artikel.index') }}"
                   class="inline-block bg-slate-900 hover:bg-slate-900 text-white font-bold font-poppins px-8 py-4 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl uppercase tracking-wider">
                    Lihat Semua Artikel →
                </a>
            </div>
        @endif

    </div>
</section>