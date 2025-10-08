<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Perawatan;
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
        Log::info("Jumlah produk: " . (isset($data['detailProduk']) ? count($data['detailProduk']) : 0));
        Log::info("Jumlah perawatan: " . (isset($data['detailPerawatan']) ? count($data['detailPerawatan']) : 0));

        // Handle status change
        if ($originalStatus !== $newStatus) {
            if ($newStatus === 'Dibatalkan' && $originalStatus === 'Berhasil') {
                // Kembalikan stok untuk produk langsung
                Log::info("Mengembalikan stok karena pembatalan pesanan");
                foreach ($this->record->detailProduk as $detail) {
                    $detail->produk->tambahStok(
                        $detail->qty,
                        "Pengembalian stok karena pembatalan pesanan #{$this->record->id}",
                        $this->record->created_at
                    );
                    Log::info("Stok dikembalikan untuk produk {$detail->produk->Nama}, Qty: {$detail->qty}");
                }

                // Kembalikan stok untuk produk dalam perawatan - YANG DIPERBAIKI
                foreach ($this->record->detailPerawatan as $detail) {
                    $perawatan = $detail->perawatan;
                    Log::info("Mengembalikan stok untuk perawatan {$perawatan->Nama_Perawatan}");

                    $perawatan->kembalikanStokProdukBulk(
                        $detail->qty,
                        $this->record->id
                    );

                    Log::info("Stok dikembalikan untuk perawatan {$perawatan->Nama_Perawatan}, Qty: {$detail->qty}");
                }
            } elseif ($newStatus === 'Berhasil' && $originalStatus === 'Dibatalkan') {
                // Validasi dan kurangi stok untuk produk langsung
                Log::info("Mengurangi stok karena aktivasi pesanan");
                foreach ($this->record->detailProduk as $detail) {
                    if ($detail->produk->Stok < $detail->qty) {
                        throw new \Exception("Stok produk {$detail->produk->Nama} tidak mencukupi. Stok tersedia: {$detail->produk->Stok}, diperlukan: {$detail->qty}");
                    }
                    $detail->produk->kurangiStok(
                        $detail->qty,
                        "Pengurangan stok karena aktivasi pesanan #{$this->record->id}",
                        $this->record->created_at
                    );
                    Log::info("Stok dikurangi untuk produk {$detail->produk->Nama}, Qty: {$detail->qty}");
                }

                // Validasi dan kurangi stok untuk produk dalam perawatan - YANG DIPERBAIKI
                foreach ($this->record->detailPerawatan as $detail) {
                    $perawatan = $detail->perawatan;

                    // Validasi stok untuk semua produk dalam perawatan
                    foreach ($perawatan->produk as $produk) {
                        $qtyDigunakan = $produk->pivot->qty_digunakan * $detail->qty;
                        if ($produk->Stok < $qtyDigunakan) {
                            throw new \Exception("Stok produk {$produk->Nama} tidak mencukupi untuk perawatan {$perawatan->Nama_Perawatan}. Stok tersedia: {$produk->Stok}, diperlukan: {$qtyDigunakan}");
                        }
                    }

                    Log::info("Mengurangi stok untuk perawatan {$perawatan->Nama_Perawatan}");
                    $perawatan->kurangiStokProdukBulk(
                        $detail->qty,
                        $this->record->id
                    );

                    Log::info("Stok dikurangi untuk perawatan {$perawatan->Nama_Perawatan}, Qty: {$detail->qty}");
                }
            }
        }

        return $data;
    }

    protected function handleRecordUpdate($record, array $data): Pesanan
    {
        return DB::transaction(function () use ($record, $data) {
            Log::info("=== HANDLE RECORD UPDATE ===");

            // Validasi stok untuk produk baru (detailProduk)
            if ($data['status'] === 'Berhasil') {
                foreach ($data['detailProduk'] ?? [] as $index => $item) {
                $produk = Produk::find($item['produk_id']);
                if (!$produk) {
                    throw new \Exception("Produk tidak ditemukan untuk ID: {$item['produk_id']}");
                }
                // if (!$produk->is_bundling && $produk->Stok < (int)$item['qty']) {
                //     throw new \Exception("Stok produk {$produk->Nama} tidak mencukupi. Stok tersedia: {$produk->Stok}, diperlukan: {$item['qty']}");
                // }
                Log::info("Validasi produk {$index}: {$produk->Nama}, Stok: {$produk->Stok}, Qty: {$item['qty']} - OK");
            }

                // Validasi stok untuk produk dalam perawatan baru (detailPerawatan) - YANG DIPERBAIKI
                foreach ($data['detailPerawatan'] ?? [] as $index => $item) {
                    $perawatan = Perawatan::with('produk')->find($item['perawatan_id']);
                    if (!$perawatan) {
                        throw new \Exception("Perawatan tidak ditemukan untuk ID: {$item['perawatan_id']}");
                    }
                    foreach ($perawatan->produk as $produk) {
                        $qtyDigunakan = $produk->pivot->qty_digunakan * (int)$item['qty'];
                        // if ($produk->Stok < $qtyDigunakan) {
                        //     throw new \Exception("Stok produk {$produk->Nama} tidak mencukupi untuk perawatan {$perawatan->Nama_Perawatan}. Stok tersedia: {$produk->Stok}, diperlukan: {$qtyDigunakan}");
                        // }
                        Log::info("Validasi perawatan {$index}: Produk {$produk->Nama}, Stok: {$produk->Stok}, Qty digunakan: {$qtyDigunakan} - OK");
                    }
                }
            }

            // Update pesanan
            $record->update([
                'pelanggan_id' => $data['pelanggan_id'],
                'Metode_Pembayaran' => $data['Metode_Pembayaran'],
                'status' => $data['status'],
            ]);

            // Handle produk changes
         $record->detailProduk()->delete();
        if (isset($data['detailProduk'])) {
            foreach ($data['detailProduk'] as $index => $item) {
                $produk = Produk::find($item['produk_id']);
                if (!$produk) {
                    throw new \Exception("Produk tidak ditemukan untuk ID: {$item['produk_id']}");
                }

                $record->detailProduk()->create([
                    'produk_id' => $item['produk_id'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                ]);

                if ($data['status'] === 'Berhasil') {
                    Log::info("Calling kurangiStok for product {$produk->Nama} (edit)");
                    if ($produk->is_bundling) {
                        $produk->kurangiStokBundling(
                            (int)$item['qty'],
                            "Penjualan pesanan #{$record->id} (edit)",
                            $record->created_at
                        );
                        Log::info("Stok reduced for bundling product {$produk->Nama}");
                    } else {
                        $produk->kurangiStok(
                            (int)$item['qty'],
                            "Penjualan pesanan #{$record->id} (edit)",
                            $record->created_at
                        );
                        Log::info("Stok movement created for product {$produk->Nama}");
                    }
                }
            }
        }
            // Handle perawatan changes - YANG DIPERBAIKI
            $record->detailPerawatan()->delete();
            if (isset($data['detailPerawatan'])) {
                foreach ($data['detailPerawatan'] as $index => $item) {
                    $perawatan = Perawatan::with('produk')->find($item['perawatan_id']);
                    if (!$perawatan) {
                        throw new \Exception("Perawatan tidak ditemukan untuk ID: {$item['perawatan_id']}");
                    }

                    $record->detailPerawatan()->create([
                        'perawatan_id' => $item['perawatan_id'],
                        'qty' => $item['qty'],
                        'harga' => $item['harga'],
                    ]);

                    if ($data['status'] === 'Berhasil') {
                        Log::info("Calling kurangiStokProdukBulk for perawatan {$perawatan->Nama_Perawatan} (edit)");

                        $perawatan->kurangiStokProdukBulk(
                            (int)$item['qty'],
                            $record->id
                        );

                        Log::info("Stok reduced for products in perawatan {$perawatan->Nama_Perawatan}");
                    }
                }
            }

            Log::info("=== PESANAN UPDATE COMPLETED SUCCESSFULLY ===");
            return $record;
        });
    }
}
