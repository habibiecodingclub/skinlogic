<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\PesananProduk;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info("=== BEFORE CREATE PESANAN ===");
        Log::info("Status: " . ($data['status'] ?? 'null'));
        Log::info("Jumlah produk: " . (isset($data['detailProduk']) ? count($data['detailProduk']) : 0));

        return $data;
    }

    protected function handleRecordCreation(array $data): Pesanan
    {
        Log::info("=== START HANDLE RECORD CREATION ===");

        return DB::transaction(function () use ($data) {
            try {
                // 1. Validasi stok sebelum membuat pesanan
                if ($data['status'] === 'Berhasil') {
                    foreach ($data['detailProduk'] ?? [] as $index => $item) {
                        $produk = Produk::find($item['produk_id']);
                        if (!$produk) {
                            throw new \Exception("Produk tidak ditemukan");
                        }

                        if ($produk->Stok < (int)$item['qty']) {
                            throw new \Exception("Stok produk {$produk->Nama} tidak mencukupi. Stok tersedia: {$produk->Stok}, diperlukan: {$item['qty']}");
                        }

                        Log::info("Validasi produk {$index}: {$produk->Nama}, Stok: {$produk->Stok}, Qty: {$item['qty']} - OK");
                    }
                }

                // 2. Buat pesanan
                $pesanan = Pesanan::create([
                    'pelanggan_id' => $data['pelanggan_id'],
                    'Metode_Pembayaran' => $data['Metode_Pembayaran'],
                    'status' => $data['status'],
                    'created_at' => now(),
                ]);

                Log::info("Pesanan created: #{$pesanan->id}");

                // 3. Process produk - MANUAL CREATION tanpa relationship
                if (isset($data['detailProduk'])) {
                    foreach ($data['detailProduk'] as $index => $item) {
                        $produk = Produk::find($item['produk_id']);

                        if (!$produk) {
                            throw new \Exception("Produk tidak ditemukan");
                        }

                        Log::info("Processing product {$index}: {$produk->Nama} (ID: {$produk->id})");
                        Log::info("Stok sebelum: {$produk->Stok}, Qty: {$item['qty']}");

                        if ($data['status'] === 'Berhasil') {
                            // **PANGGIL METHOD KURANGI STOK DI SINI**
                            Log::info("Calling kurangiStok for product {$produk->Nama}");

                            $produk->kurangiStok(
                                (int)$item['qty'],
                                "Penjualan pesanan #{$pesanan->id}"
                            );

                            Log::info("Stok movement created for product {$produk->Nama}");
                        } else {
                            Log::info("Skipping stock reduction - status: {$data['status']}");
                        }

                        // 4. Create pesanan_produk record MANUALLY
                        PesananProduk::create([
                            'pesanan_id' => $pesanan->id,
                            'produk_id' => $item['produk_id'],
                            'qty' => $item['qty'],
                            'harga' => $item['harga'],
                        ]);

                        Log::info("PesananProduk created for product {$produk->Nama}");
                    }
                }

                // 5. Process perawatan (tetap menggunakan relationship)
                if (isset($data['detailPerawatan'])) {
                    foreach ($data['detailPerawatan'] as $item) {
                        $pesanan->detailPerawatan()->create([
                            'perawatan_id' => $item['perawatan_id'],
                            'qty' => $item['qty'],
                            'harga' => $item['harga'],
                        ]);
                    }
                }

                Log::info("=== PESANAN CREATION COMPLETED SUCCESSFULLY ===");
                return $pesanan;

            } catch (\Exception $e) {
                Log::error("Error creating pesanan: " . $e->getMessage());
                Log::error("Stack trace: " . $e->getTraceAsString());
                throw $e;
            }
        });
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Pesanan berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
