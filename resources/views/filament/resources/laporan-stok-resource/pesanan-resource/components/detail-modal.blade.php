<div class="space-y-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="font-semibold text-gray-900">Informasi Pelanggan</h3>
            <p class="text-sm text-gray-600">Nama: {{ $record->pelanggan->Nama }}</p>
            <p class="text-sm text-gray-600">Email: {{ $record->pelanggan->Email }}</p>
            <p class="text-sm text-gray-600">Telepon: {{ $record->pelanggan->Nomor_Telepon }}</p>
        </div>
        <div>
            <h3 class="font-semibold text-gray-900">Informasi Pesanan</h3>
            <p class="text-sm text-gray-600">Tanggal: {{ $record->created_at->format('d M Y H:i') }}</p>
            <p class="text-sm text-gray-600">Metode: {{ $record->Metode_Pembayaran }}</p>
            <p class="text-sm text-gray-600">ID Pesanan: #{{ $record->id }}</p>
        </div>
    </div>

    @if($record->detailProduk->count() > 0)
    <div>
        <h3 class="font-semibold text-gray-900 mb-3">Produk</h3>
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Produk</th>
                        <th class="px-4 py-2 text-center">Qty</th>
                        <th class="px-4 py-2 text-right">Harga</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->detailProduk as $item)
                    <tr class="border-t border-gray-200">
                        <td class="px-4 py-2">{{ $item->produk->Nama }}</td>
                        <td class="px-4 py-2 text-center">{{ $item->qty }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="3" class="px-4 py-2 text-right">Total Produk:</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($totalProduk, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($record->detailPerawatan->count() > 0)
    <div>
        <h3 class="font-semibold text-gray-900 mb-3">Perawatan</h3>
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Perawatan</th>
                        <th class="px-4 py-2 text-center">Qty</th>
                        <th class="px-4 py-2 text-right">Harga</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->detailPerawatan as $item)
                    <tr class="border-t border-gray-200">
                        <td class="px-4 py-2">{{ $item->perawatan->Nama_Perawatan }}</td>
                        <td class="px-4 py-2 text-center">{{ $item->qty }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="3" class="px-4 py-2 text-right">Total Perawatan:</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($totalPerawatan, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <h3 class="text-lg font-bold text-blue-900 text-center">
            GRAND TOTAL: Rp {{ number_format($total, 0, ',', '.') }}
        </h3>
    </div>
</div>
