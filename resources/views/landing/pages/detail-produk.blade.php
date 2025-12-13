<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product['name'] }} - SkinLogic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">

    @include('landing.sections.header')

    <div class="pt-32 pb-20 px-6">
        <div class="container mx-auto max-w-6xl">
            
            {{-- Breadcrumb (Navigasi Kecil) --}}
            <nav class="text-sm text-gray-500 mb-8">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a> 
                <span class="mx-2">/</span>
                <a href="{{ route('produk.index') }}" class="hover:text-blue-600">Produk</a>
                <span class="mx-2">/</span>
                <span class="text-slate-900 font-bold">{{ $product['name'] }}</span>
            </nav>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
                
                {{-- KOLOM KIRI: Gambar Produk --}}
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 flex justify-center items-center">
                    <img src="{{ asset('images/' . $product['image']) }}" 
                         alt="{{ $product['name'] }}" 
                         class="w-full max-w-sm object-cover rounded-2xl shadow-lg hover:scale-105 transition-transform duration-500">
                </div>

                {{-- KOLOM KANAN: Informasi Produk --}}
                <div>
                    <span class="text-blue-600 font-bold tracking-wider uppercase text-sm">
                        {{ $product['category'] }}
                    </span>
                    
                    <h1 class="text-4xl font-extrabold text-slate-900 mt-2 mb-4">
                        {{ $product['name'] }}
                    </h1>

                    <p class="text-3xl font-bold text-yellow-600 mb-6">
                        Rp {{ number_format($product['price'], 0, ',', '.') }}
                    </p>

                    <p class="text-gray-600 leading-relaxed mb-8 text-lg">
                        {{ $product['description'] }}
                    </p>

                    {{-- List Manfaat --}}
                    <div class="mb-8">
                        <h3 class="font-bold text-slate-900 mb-3">Manfaat Utama:</h3>
                        <ul class="space-y-2">
                            @foreach($product['benefits'] as $benefit)
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ $benefit }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Tombol Beli --}}
                    <div class="flex gap-4">
                        <button class="flex-1 bg-slate-900 text-white py-4 px-8 rounded-full font-bold hover:bg-blue-700 transition-colors shadow-lg">
                            Beli Sekarang
                        </button>
                        <button class="bg-gray-100 text-slate-900 p-4 rounded-full hover:bg-gray-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('landing.sections.footer')

</body>
</html>