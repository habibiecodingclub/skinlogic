<div class="space-y-6">
    <!-- Informasi Produk -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-2">Informasi Produk</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">SKU</p>
                <p class="font-medium">{{ $produk->Nomor_SKU }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nama Produk</p>
                <p class="font-medium">{{ $produk->Nama }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Harga</p>
                <p class="font-medium">Rp {{ number_format($produk->Harga, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Stok Saat Ini</p>
                <p class="font-medium">{{ $produk->Stok }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Terjual</p>
                @php
                    $totalTerjual = $produk->pesanan()->sum('pesanan_produk.qty');
                    $totalPendapatan = 0;

                    // Hitung total pendapatan dengan cara manual
                    foreach ($produk->pesanan as $pesanan) {
                        $totalPendapatan += $pesanan->pivot->qty * $pesanan->pivot->harga;
                    }
                @endphp
                <p class="font-medium">{{ $totalTerjual }} unit</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Pendapatan</p>
                <p class="font-medium">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Histori Penjualan -->
    <div>
        <h3 class="text-lg font-semibold mb-4">Histori Penjualan</h3>

        @php
            // Ambil data pesanan dengan pivot dan pelanggan
            $pesananProduk = $produk->pesanan()
                ->with('pelanggan')
                ->orderBy('pesanans.created_at', 'desc')
                ->get();

            // Group by tanggal
            $historiPenjualan = $pesananProduk->groupBy(function($item) {
                return $item->pivot->created_at->format('Y-m-d');
            });
        @endphp

        @if($pesananProduk->isEmpty())
            <p class="text-gray-500 text-center py-4">Belum ada penjualan</p>
        @else
            <div class="space-y-4">
                @foreach($historiPenjualan as $tanggal => $penjualanHarian)
                    @php
                        $totalHarian = 0;
                        $totalQtyHarian = 0;

                        foreach ($penjualanHarian as $pesanan) {
                            $totalHarian += $pesanan->pivot->qty * $pesanan->pivot->harga;
                            $totalQtyHarian += $pesanan->pivot->qty;
                        }
                    @endphp

                    <div class="border rounded-lg overflow-hidden">
                        <!-- Header Harian -->
                        <div class="bg-green-50 px-4 py-2 border-b">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</span>
                                    <span class="text-sm text-gray-600 ml-2">
                                        {{ $penjualanHarian->count() }} transaksi
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                        Terjual: {{ $totalQtyHarian }} unit
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded ml-2">
                                        Pendapatan: Rp {{ number_format($totalHarian, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Transaksi -->
                        <div class="p-4">
                            <div class="space-y-3">
                                @foreach($penjualanHarian as $pesanan)
                                    @php
                                        $subtotal = $pesanan->pivot->qty * $pesanan->pivot->harga;
                                    @endphp
                                    <div class="flex justify-between items-center border-b pb-2 last:border-b-0 last:pb-0">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-sm font-medium text-gray-600">
                                                    {{ $pesanan->created_at->format('H:i') }}
                                                </span>
                                                <span class="text-sm">
                                                    &nbsp; Pesanan #{{ $pesanan->id }}
                                                </span>
                                                @if($pesanan->pelanggan)
                                                    <span class="text-sm text-gray-500">
                                                        &nbsp; • {{ $pesanan->pelanggan->Nama }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-sm text-gray-600">
                                                {{ $pesanan->pivot->qty }} unit × Rp {{ number_format($pesanan->pivot->harga, 0, ',', '.') }}
                                            </span>
                                            <span class="font-medium">
                                                &nbsp; Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Statistik Bulanan -->
<div>
    <h3 class="text-lg font-semibold mb-4">Statistik Bulanan</h3>

    @php
        $statistikBulanan = [];

        // Hitung statistik bulanan secara manual
        foreach ($pesananProduk as $pesanan) {
            $tahun = $pesanan->created_at->format('Y');
            $bulan = $pesanan->created_at->format('m');
            $key = $tahun . '-' . $bulan;

            if (!isset($statistikBulanan[$key])) {
                $statistikBulanan[$key] = [
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'total_terjual' => 0,
                    'total_pendapatan' => 0,
                    'jumlah_transaksi' => 0
                ];
            }

            $statistikBulanan[$key]['total_terjual'] += $pesanan->pivot->qty;
            $statistikBulanan[$key]['total_pendapatan'] += $pesanan->pivot->qty * $pesanan->pivot->harga;
            $statistikBulanan[$key]['jumlah_transaksi']++;
        }

        // Urutkan descending by tanggal
        krsort($statistikBulanan);
    @endphp

    @if(empty($statistikBulanan))
        <p class="text-gray-500 text-center py-4">Belum ada data statistik bulanan</p>
    @else
        <div class="overflow-hidden border rounded-lg w-full"> <!-- Tambahkan w-full di sini -->
            <table class="w-full divide-y divide-gray-200"> <!-- Ganti min-w-full dengan w-full -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-2/5">Bulan</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/5">Transaksi</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/5">Terjual</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase w-1/5">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($statistikBulanan as $statistik)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 w-2/5">
                                {{ \Carbon\Carbon::create($statistik['tahun'], $statistik['bulan'])->isoFormat('MMMM Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center text-gray-500 w-1/5">
                                {{ $statistik['jumlah_transaksi'] }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center text-gray-500 w-1/5">
                                {{ $statistik['total_terjual'] }} unit
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900 font-medium w-1/5">
                                Rp {{ number_format($statistik['total_pendapatan'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
</div>
