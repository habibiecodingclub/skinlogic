<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use Filament\Resources\Pages\EditRecord;

class EditPesanan extends EditRecord
{
    protected static string $resource = PesananResource::class;

    protected function afterSave(): void
    {
        $pesanan = $this->record;
        $data = $this->data;

        // Handle update produk manually
        if (isset($data['detailProduk'])) {
            // Hapus yang lama dan buat yang baru
            $pesanan->detailProduk()->delete();

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
                        "Update pesanan #{$pesanan->id}",
                        now()
                    );
                }
            }
        }
    }
}
