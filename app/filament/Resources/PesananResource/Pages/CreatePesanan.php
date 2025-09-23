<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Buat pesanan terlebih dahulu
        $pesanan = static::getModel()::create($data);

        // Handle produk manually
        if (isset($data['detailProduk'])) {
            foreach ($data['detailProduk'] as $produkData) {
                if (!empty($produkData['produk_id']) && !empty($produkData['qty'])) {

                    $produk = \App\Models\Produk::find($produkData['produk_id']);

                    if (!$produk) {
                        throw new \Exception("Produk tidak ditemukan");
                    }

                    // Validasi stok
                    if ($produk->Stok < $produkData['qty']) {
                        throw new \Exception("Stok {$produk->Nama} tidak mencukupi. Stok tersedia: {$produk->Stok}");
                    }

                    // Buat pesanan produk
                    $pesananProduk = $pesanan->detailProduk()->create([
                        'produk_id' => $produkData['produk_id'],
                        'qty' => $produkData['qty'],
                        'harga' => $produkData['harga'] ?? $produk->Harga,
                    ]);

                    // Kurangi stok secara manual
                    $produk->decrement('Stok', $produkData['qty']);

                    // Catat di stok movement
                    $produk->kurangiStok(
                        $produkData['qty'],
                        "Penjualan pesanan #{$pesanan->id}",
                        now()
                    );
                }
            }
        }

        // Handle perawatan
        if (isset($data['detailPerawatan'])) {
            foreach ($data['detailPerawatan'] as $perawatanData) {
                if (!empty($perawatanData['perawatan_id']) && !empty($perawatanData['qty'])) {
                    $pesanan->detailPerawatan()->create([
                        'perawatan_id' => $perawatanData['perawatan_id'],
                        'qty' => $perawatanData['qty'],
                        'harga' => $perawatanData['harga'] ?? \App\Models\Perawatan::find($perawatanData['perawatan_id'])->Harga,
                    ]);
                }
            }
        }

        return $pesanan;
    }

    protected function afterCreate(): void
{
    $pesanan = $this->record;

    // ... kode untuk produk ...

    // Handle pengurangan stok untuk perawatan
    foreach ($pesanan->detailPerawatan as $detailPerawatan) {
        $perawatan = $detailPerawatan->perawatan;

        // Kurangi stok produk yang digunakan dalam perawatan
        // Multiply dengan quantity pesanan
        foreach ($perawatan->produk as $produk) {
            $totalQtyDigunakan = $produk->pivot->qty_digunakan * $detailPerawatan->qty;

            if ($produk->Stok < $totalQtyDigunakan) {
                throw new \Exception("Stok {$produk->Nama} tidak mencukupi untuk perawatan {$perawatan->Nama_Perawatan}");
            }

            // Kurangi stok produk
            $produk->decrement('Stok', $totalQtyDigunakan);

            // Catat di stok movement
            \App\Models\StokMovement::create([
                'produk_id' => $produk->id,
                'tipe' => 'keluar',
                'jumlah' => $totalQtyDigunakan,
                'keterangan' => "Penggunaan untuk perawatan: {$perawatan->Nama_Perawatan} (Pesanan #{$pesanan->id})",
                'tanggal' => now(),
            ]);
        }
    }
}
}
