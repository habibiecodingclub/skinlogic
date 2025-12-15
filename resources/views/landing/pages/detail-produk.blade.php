{{-- Produk Lainnya (Rekomendasi) --}}
        <div class="mt-16">
            <h3 class="text-2xl font-bold font-poppins mb-8">Produk Lainnya</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach($relatedProducts as $related)
                     @include('landing.components.product-card', [
                        'image' => asset('images/' . $related['image']),
                        'name' => $related['name'],
                        'category' => $related['category'],
                        'price' => $related['price'],
                        'slug' => $related['slug']
                    ])
                @endforeach
            </div>
        </div>