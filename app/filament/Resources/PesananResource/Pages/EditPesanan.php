<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use App\Models\Pesanan;
use App\Models\Produk;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditPesanan extends EditRecord
{
    protected static string $resource = PesananResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $originalStatus = $this->record->status;
        $newStatus = $data['status'];

        Log::info("=== BEFORE EDIT PESANAN ===");
        Log::info("Original Status: {$originalStatus}, New Status: {$newStatus}");

        // Handle status change
        if ($originalStatus !== $newStatus) {
            if ($newStatus === 'Dibatalkan' && $originalStatus === 'Berhasil') {
                // Kembalikan stok jika dibatalkan
                Log::info("Mengembalikan stok karena pembatalan pesanan");
                foreach ($this->record->detailProduk as $detail) {
                    $detail->produk->tambahStok(
                        $detail->qty,
                        "Pengembalian stok karena pembatalan pesanan #{$this->record->id}"
                    );
                }
            } elseif ($newStatus === 'Berhasil' && $originalStatus === 'Dibatalkan') {
                // Kurangi stok jika diaktifkan kembali
                Log::info("Mengurangi stok karena aktivasi pesanan");
                foreach ($this->record->detailProduk as $detail) {
                    $detail->produk->kurangiStok(
                        $detail->qty,
                        "Pengurangan stok karena aktivasi pesanan #{$this->record->id}"
                    );
                }
            }
        }

        return $data;
    }

    protected function handleRecordUpdate($record, array $data): Pesanan
    {
        return DB::transaction(function () use ($record, $data) {
            Log::info("=== HANDLE RECORD UPDATE ===");

            // Update pesanan
            $record->update([
                'pelanggan_id' => $data['pelanggan_id'],
                'Metode_Pembayaran' => $data['Metode_Pembayaran'],
                'status' => $data['status'],
            ]);

            // Handle produk changes
            $record->detailProduk()->delete();
            if (isset($data['detailProduk'])) {
                foreach ($data['detailProduk'] as $item) {
                    $record->detailProduk()->create([
                        'produk_id' => $item['produk_id'],
                        'qty' => $item['qty'],
                        'harga' => $item['harga'],
                    ]);

                    // Jika status Berhasil, kurangi stok untuk produk baru
                    if ($data['status'] === 'Berhasil') {
                        $produk = Produk::find($item['produk_id']);
                        if ($produk) {
                            $produk->kurangiStok(
                                $item['qty'],
                                "Penjualan pesanan #{$record->id} (edit)"
                            );
                        }
                    }
                }
            }

            // Sync perawatan
            $record->detailPerawatan()->delete();
            if (isset($data['detailPerawatan'])) {
                foreach ($data['detailPerawatan'] as $item) {
                    $record->detailPerawatan()->create([
                        'perawatan_id' => $item['perawatan_id'],
                        'qty' => $item['qty'],
                        'harga' => $item['harga'],
                    ]);
                }
            }

            return $record;
        });
    }
}
