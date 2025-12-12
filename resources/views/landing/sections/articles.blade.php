@php
    $articles = [
        [
            // Ganti dengan asset('images/...') jika gambar sudah ada di folder public/images
            'image' => 'https://images.unsplash.com/photo-1556228720-1957be83f06c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 
            'category' => 'Tips',
            'title' => '5 Tips Merawat Kulit di Musim Panas',
            'excerpt' => 'Musim panas bisa bikin kulitmu kering. Yuk simak tips menjaga kelembapan kulit agar tetap glowing.',
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'category' => 'Promo',
            'title' => 'Promo Spesial Akhir Tahun',
            'excerpt' => 'Dapatkan diskon hingga 40% untuk semua layanan SkinLogic selama bulan Desember ini.',
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'category' => 'Review',
            'title' => 'Review Treatment Laser SkinLogic',
            'excerpt' => 'Simak pengalaman pelanggan setelah mencoba treatment laser untuk mengatasi bekas jerawat.',
        ],
    ];
@endphp

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 max-w-6xl">
        
        {{-- Header Section (Gaya Font Sama dengan Produk) --}}
        <div class="text-center mb-12">
            <span class="block text-xs font-extrabold tracking-[0.2em] text-yellow-600 uppercase mb-3">
                BLOG & INFO
            </span>
            {{-- Font tetap standar (Bold), bukan Serif --}}
            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900">
                Artikel & Berita Terbaru
            </h2>
        </div>

        {{-- Grid 3 Kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($articles as $article)
                
                {{-- Menggunakan @include agar lebih stabil --}}
                @include('landing.components.article-card', [
                    'image' => $article['image'],
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'excerpt' => $article['excerpt']
                ])

            @endforeach
        </div>

    </div>
</section>