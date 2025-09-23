<div class="space-y-6">
    <!-- Info Produk -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
        <div>
            <strong>Nama Produk:</strong> {{ $record->Nama }}
        </div>
        <div>
            <strong>SKU:</strong> {{ $record->Nomor_SKU }}
        </div>
        <div>
            <strong>Periode:</strong>
            @php
                [$bulan, $tahun] = explode('-', $bulanTahun);
                $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->monthName;
            @endphp
            {{ $namaBulan }} {{ $tahun }}
        </div>
        <div>
            <strong>Stok Saat Ini:</strong> {{ $record->Stok }}
        </div>
    </div>

    <!-- Ringkasan Stok -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center p-4 border rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ $rekap['stok_awal'] }}</div>
            <div class="text-sm text-gray-600">Stok Awal</div>
        </div>
        <div class="text-center p-4 border rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ $rekap['total_masuk'] }}</div>
            <div class="text-sm text-gray-600">Stok Masuk</div>
        </div>
        <div class="text-center p-4 border rounded-lg">
            <div class="text-2xl font-bold text-red-600">{{ $rekap['total_keluar'] }}</div>
            <div class="text-sm text-gray-600">Stok Keluar</div>
        </div>
        <div class="text-center p-4 border rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ $rekap['stok_akhir'] }}</div>
            <div class="text-sm text-gray-600">Stok Akhir</div>
        </div>
    </div>

    <!-- Riwayat Stok -->
    <div>
        <h3 class="text-lg font-semibold mb-3">Riwayat Stok</h3>
        <div class="overflow-y-auto max-h-96">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Jenis</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $movement->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded-full {{ $movement->tipe === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $movement->tipe === 'masuk' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 font-mono">{{ $movement->jumlah }}</td>
                        <td class="px-4 py-2">{{ $movement->keterangan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                            Tidak ada riwayat stok untuk periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
