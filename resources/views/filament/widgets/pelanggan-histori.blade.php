<div class="space-y-6">
    <!-- Informasi Pelanggan -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-2">Informasi Pelanggan</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nama</p>
                <p class="font-medium">{{ $pelanggan->Nama ?? 'Tidak ada data' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ ($pelanggan->Status ?? 'Non Member') === 'Member' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $pelanggan->Status ?? 'Non Member' }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Pesanan</p>
                <p class="font-medium">{{ $pelanggan->pesanans()->count() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Pembelian</p>
                @php
                    $totalProduk = $pelanggan->pesanans->flatMap->detailProduk->sum('subtotal');
                    $totalPerawatan = $pelanggan->pesanans->flatMap->detailPerawatan->sum(function($item) {
                        return $item->harga * $item->qty;
                    });
                    $totalSemua = $totalProduk + $totalPerawatan;
                @endphp
                <p class="font-medium">Rp {{ number_format($totalSemua, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Histori Pesanan -->
    <div>
        <h3 class="text-lg font-semibold mb-4">Histori Pesanan</h3>

        @php
            $pesanans = $pelanggan->pesanans()
                ->with([
                    'detailProduk.produk',
                    'detailPerawatan.perawatan'
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        @endphp

        @if($pesanans->isEmpty())
            <p class="text-gray-500 text-center py-4">Belum ada pesanan</p>
        @else
            <div class="space-y-4">
                @foreach($pesanans as $pesanan)
                    <div class="border rounded-lg overflow-hidden">
                        <!-- Header Pesanan -->
                        <div class="bg-blue-50 px-4 py-2 border-b">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-semibold">Pesanan #{{ $pesanan->id }}</span>
                                    <span class="text-sm text-gray-600 ml-2">
                                        {{ $pesanan->created_at->format('d M Y H:i') }}
                                    </span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mb-1">
                                        Total: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-gray-600">
                                        {{ $pesanan->Metode_Pembayaran ?? 'Cash' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Produk -->
                        @if($pesanan->detailProduk->isNotEmpty())
                            <div class="p-4 border-b">
                                <h4 class="font-medium text-gray-700 mb-2">Produk yang Dibeli:</h4>
                                <div class="space-y-2">
                                    @foreach($pesanan->detailProduk as $item)
                                        <div class="flex justify-between items-center text-sm">
                                            <div>
                                                @if($item->produk)
                                                    <span class="font-medium">{{ $item->produk->Nama }}</span>
                                                @else
                                                    <span class="font-medium text-orange-500">
                                                        Produk (ID: {{ $item->produk_id }}) tidak ditemukan
                                                    </span>
                                                @endif
                                                <span class="text-gray-600 ml-2">x{{ $item->qty }}</span>
                                            </div>
                                            <span class="text-gray-700">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                    <div class="flex justify-between items-center pt-2 border-t">
                                        <span class="font-medium">Subtotal Produk:</span>
                                        <span class="font-medium">
                                            Rp {{ number_format($pesanan->detailProduk->sum('subtotal'), 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Detail Perawatan -->
                        @if($pesanan->detailPerawatan->isNotEmpty())
                            <div class="p-4 @if($pesanan->detailProduk->isNotEmpty()) border-t @endif">
                                <h4 class="font-medium text-gray-700 mb-2">Layanan Perawatan:</h4>
                                <div class="space-y-2">
                                    @foreach($pesanan->detailPerawatan as $item)
                                        <div class="flex justify-between items-center text-sm">
                                            <div>
                                                @if($item->perawatan)
                                                    <span class="font-medium">{{ $item->perawatan->Nama_Perawatan }}</span>
                                                @else
                                                    <span class="font-medium text-orange-500">
                                                        Perawatan (ID: {{ $item->perawatan_id }}) tidak ditemukan
                                                    </span>
                                                @endif
                                                <span class="text-gray-600 ml-2">x{{ $item->qty }}</span>
                                            </div>
                                            <span class="text-gray-700">
                                                Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                    <div class="flex justify-between items-center pt-2 border-t">
                                        <span class="font-medium">Subtotal Perawatan:</span>
                                        <span class="font-medium">
                                            Rp {{ number_format($pesanan->detailPerawatan->sum(function($item) {
                                                return $item->harga * $item->qty;
                                            }), 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Jika tidak ada produk dan perawatan -->
                        @if($pesanan->detailProduk->isEmpty() && $pesanan->detailPerawatan->isEmpty())
                            <div class="p-4 text-center text-gray-500">
                                Pesanan ini tidak memiliki item
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
