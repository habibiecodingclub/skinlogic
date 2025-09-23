<x-filament::page>
    <div class="space-y-6">
        <!-- Header Info -->
        @php
            $rekap = $this->getRekapData();
            [$bulan, $tahun] = explode('-', $this->bulanTahun);
            $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->monthName;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-filament::card>
                <div class="text-center p-4">
                    <div class="text-2xl font-bold text-primary-600">
                        {{ $rekap['stok_awal'] }}
                    </div>
                    <div class="text-sm text-gray-500">Stok Awal Bulan</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center p-4">
                    <div class="text-2xl font-bold text-success-600">
                        {{ $rekap['total_masuk'] }}
                    </div>
                    <div class="text-sm text-gray-500">Total Masuk</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center p-4">
                    <div class="text-2xl font-bold text-danger-600">
                        {{ $rekap['total_keluar'] }}
                    </div>
                    <div class="text-sm text-gray-500">Total Keluar</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center p-4">
                    <div class="text-2xl font-bold text-warning-600">
                        {{ $rekap['stok_akhir'] }}
                    </div>
                    <div class="text-sm text-gray-500">Stok Akhir Bulan</div>
                </div>
            </x-filament::card>
        </div>

        <!-- Info Produk -->
        <x-filament::card>
            <div class="p-4">
                <h3 class="text-lg font-semibold">Informasi Produk</h3>
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <div>
                        <span class="font-medium">Nama Produk:</span> {{ $this->record->Nama }}
                    </div>
                    <div>
                        <span class="font-medium">SKU:</span> {{ $this->record->Nomor_SKU }}
                    </div>
                    <div>
                        <span class="font-medium">Periode:</span> {{ $namaBulan }} {{ $tahun }}
                    </div>
                    <div>
                        <span class="font-medium">Stok Saat Ini:</span> {{ $this->record->Stok }}
                    </div>
                </div>
            </div>
        </x-filament::card>

        <!-- Tabel Movements -->
        <x-filament::card>
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-4">Riwayat Stok</h3>
                {{ $this->table }}
            </div>
        </x-filament::card>
    </div>
</x-filament::page>
