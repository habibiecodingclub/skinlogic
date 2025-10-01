<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Perawatan;
use App\Models\PesananPerawatan;
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
        Log::info("📦 === BEFORE CREATE PESANAN ===");
        Log::info("📦 Status: " . ($data['status'] ?? 'null'));
        Log::info("📦 Jumlah produk: " . (isset($data['items_produk']) ? count($data['items_produk']) : 0));
        Log::info("📦 Jumlah perawatan: " . (isset($data['items_perawatan']) ? count($data['items_perawatan']) : 0));

        // Debug detail produk dan perawatan
        if (isset($data['items_produk'])) {
            foreach ($data['items_produk'] as $index => $item) {
                Log::info("📦 Produk {$index}: ID={$item['produk_id']}, Qty={$item['qty']}, Harga={$item['harga']}");
            }
        }

        if (isset($data['items_perawatan'])) {
            foreach ($data['items_perawatan'] as $index => $item) {
                Log::info("📦 Perawatan {$index}: ID={$item['perawatan_id']}, Qty={$item['qty']}, Harga={$item['harga']}");
            }
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Pesanan
    {
        Log::info("🚀 === START HANDLE RECORD CREATION ===");
        Log::info("📦 ALL FORM DATA: ", $data);

        return DB::transaction(function () use ($data) {
            try {
                // 1. Buat pesanan
                $pesanan = Pesanan::create([
                    'pelanggan_id' => $data['pelanggan_id'],
                    'Metode_Pembayaran' => $data['Metode_Pembayaran'],
                    'status' => $data['status'],
                    'created_at' => now(),
                ]);

                Log::info("📝 Pesanan created: #{$pesanan->id}");

                // 2. PROSES PRODUK - DENGAN HANDLING BUNDLING & VALIDASI STOK
                if (!empty($data['items_produk']) && is_array($data['items_produk'])) {
                    Log::info("🛒 Processing produk dari items_produk: " . count($data['items_produk']) . " items");

                    // **VALIDASI STOK SEBELUM PROSES**
                    foreach ($data['items_produk'] as $index => $item) {
                        $produkId = $item['produk_id'] ?? null;
                        $qty = $item['qty'] ?? 1;

                        if (!$produkId) continue;

                        $produk = Produk::with(['produkBundlingItems.produk'])->find($produkId);
                        if (!$produk) {
                            throw new \Exception("Produk tidak ditemukan untuk ID: {$produkId}");
                        }

                        if ($data['status'] === 'Berhasil') {
                            if ($produk->is_bundling) {
                                // **VALIDASI STOK BUNDLING**
                                foreach ($produk->produkBundlingItems as $bundlingItem) {
                                    $totalQtyDigunakan = $bundlingItem->qty * $qty;
                                    if ($bundlingItem->produk->Stok < $totalQtyDigunakan) {
                                        throw new \Exception(
                                            "Stok tidak mencukupi untuk bundling {$produk->Nama}. " .
                                            "Produk {$bundlingItem->produk->Nama} hanya tersedia {$bundlingItem->produk->Stok}, " .
                                            "diperlukan {$totalQtyDigunakan}"
                                        );
                                    }
                                }
                                Log::info("🎁✅ Validasi stok bundling {$produk->Nama} - OK");
                            } else {
                                // **VALIDASI STOK PRODUK BIASA**
                                if ($produk->Stok < $qty) {
                                    throw new \Exception(
                                        "Stok produk {$produk->Nama} tidak mencukupi. " .
                                        "Stok tersedia: {$produk->Stok}, diperlukan: {$qty}"
                                    );
                                }
                                Log::info("🛒✅ Validasi stok produk {$produk->Nama} - OK");
                            }
                        }
                    }

                    // **PROSES PENYIMPANAN DAN PENGURANGAN STOK**
                    foreach ($data['items_produk'] as $index => $item) {
                        $produkId = $item['produk_id'] ?? null;
                        $qty = $item['qty'] ?? 1;
                        $harga = $item['harga'] ?? 0;

                        if (!$produkId) {
                            Log::warning("🛒 Skipping produk item {$index} - produk_id tidak ada");
                            continue;
                        }

                        $produk = Produk::with(['produkBundlingItems.produk'])->find($produkId);
                        if (!$produk) {
                            throw new \Exception("Produk tidak ditemukan untuk ID: {$produkId}");
                        }

                        // **LOG DETAIL HARGA UNTUK DEBUG**
                        Log::info("🛒🔍 Detail harga produk {$produk->Nama}:");
                        Log::info("🛒🔍   - Harga dari form: {$harga}");
                        Log::info("🛒🔍   - Harga normal: {$produk->Harga}");
                        Log::info("🛒🔍   - Harga bundling: " . ($produk->harga_bundling ?? 'null'));
                        Log::info("🛒🔍   - is_bundling: " . ($produk->is_bundling ? 'true' : 'false'));

                        // **FALLBACK: JIKA HARGA 0, AMBIL HARGA YANG BENAR**
                        if ($harga == 0) {
                            $harga = $produk->is_bundling ? $produk->harga_bundling : $produk->Harga;
                            Log::info("🛒⚠️ Harga 0, menggunakan harga " . ($produk->is_bundling ? 'bundling' : 'normal') . ": {$harga}");
                        }

                        // **VALIDASI HARGA AKHIR**
                        if ($harga == 0) {
                            Log::warning("🛒⚠️ Harga masih 0 setelah fallback, menggunakan harga default");
                            $harga = $produk->is_bundling ? ($produk->harga_bundling ?? 0) : $produk->Harga;
                        }

                        Log::info("🛒 Final harga untuk {$produk->Nama}: {$harga}");

                        // CREATE PESANAN_PRODUK RECORD
                        $pesananProduk = PesananProduk::create([
                            'pesanan_id' => $pesanan->id,
                            'produk_id' => $produkId,
                            'qty' => $qty,
                            'harga' => $harga,
                        ]);

                        Log::info("🛒✅ PesananProduk created - ID: {$pesananProduk->id}, Produk: {$produk->Nama}, Qty: {$qty}, Harga: {$harga}");

                        // Reduce stock for products
                        if ($data['status'] === 'Berhasil') {
                            if ($produk->is_bundling) {
                                // **PRODUK BUNDLING: Kurangi stok semua produk dalam bundling**
                                Log::info("🎁🔄 Calling kurangiStokBundling for produk {$produk->Nama}");
                                $produk->kurangiStokBundling(
                                    (int)$qty,
                                    "Penjualan pesanan #{$pesanan->id}",
                                    $pesanan->created_at
                                );
                                Log::info("🎁✅ Berhasil mengurangi stok bundling untuk produk {$produk->Nama}");
                            } else {
                                // **PRODUK BIASA: Kurangi stok seperti biasa**
                                Log::info("🛒🔄 Calling kurangiStok for produk {$produk->Nama}");
                                $produk->kurangiStok(
                                    (int)$qty,
                                    "Penjualan pesanan #{$pesanan->id}",
                                    $pesanan->created_at
                                );
                                Log::info("🛒✅ Berhasil mengurangi stok untuk produk {$produk->Nama}");
                            }
                        } else {
                            Log::info("🛒⏸️ Skipping stock reduction - status bukan 'Berhasil'");
                        }
                    }
                } else {
                    Log::warning("🛒❌ Tidak ada data produk di items_produk");
                }

                // 3. PROSES PERAWATAN - TETAP SAMA
                if (!empty($data['items_perawatan']) && is_array($data['items_perawatan'])) {
                    Log::info("💆 Processing perawatan dari items_perawatan: " . count($data['items_perawatan']) . " items");

                    // **VALIDASI STOK PERAWATAN SEBELUM PROSES**
                    foreach ($data['items_perawatan'] as $index => $item) {
                        $perawatanId = $item['perawatan_id'] ?? null;
                        $qty = $item['qty'] ?? 1;

                        if (!$perawatanId) continue;

                        $perawatan = Perawatan::with(['produk'])->find($perawatanId);
                        if (!$perawatan) {
                            throw new \Exception("Perawatan tidak ditemukan untuk ID: {$perawatanId}");
                        }

                        if ($data['status'] === 'Berhasil') {
                            foreach ($perawatan->produk as $produk) {
                                $qtyDigunakan = $produk->pivot->qty_digunakan * $qty;
                                if ($produk->Stok < $qtyDigunakan) {
                                    throw new \Exception(
                                        "Stok tidak mencukupi untuk perawatan {$perawatan->Nama_Perawatan}. " .
                                        "Produk {$produk->Nama} hanya tersedia {$produk->Stok}, " .
                                        "diperlukan {$qtyDigunakan}"
                                    );
                                }
                            }
                            Log::info("💆✅ Validasi stok perawatan {$perawatan->Nama_Perawatan} - OK");
                        }
                    }

                    foreach ($data['items_perawatan'] as $index => $item) {
                        $perawatanId = $item['perawatan_id'] ?? null;
                        $qty = $item['qty'] ?? 1;
                        $harga = $item['harga'] ?? 0;

                        if (!$perawatanId) {
                            Log::warning("💆 Skipping perawatan item {$index} - perawatan_id tidak ada");
                            continue;
                        }

                        $perawatan = Perawatan::with(['produk'])->find($perawatanId);
                        if (!$perawatan) {
                            throw new \Exception("Perawatan tidak ditemukan untuk ID: {$perawatanId}");
                        }

                        // **FALLBACK: JIKA HARGA 0, AMBIL DARI DATABASE**
                        if ($harga == 0) {
                            $harga = $perawatan->Harga;
                            Log::info("💆⚠️ Harga 0, menggunakan harga dari database: {$harga}");
                        }

                        Log::info("💆 Found perawatan: {$perawatan->Nama_Perawatan} (ID: {$perawatan->id}), Harga: {$harga}");

                        // CREATE PESANAN_PERAWATAN RECORD
                        $pesananPerawatan = PesananPerawatan::create([
                            'pesanan_id' => $pesanan->id,
                            'perawatan_id' => $perawatanId,
                            'qty' => $qty,
                            'harga' => $harga,
                        ]);

                        Log::info("💆✅ PesananPerawatan created - ID: {$pesananPerawatan->id}, Perawatan: {$perawatan->Nama_Perawatan}, Qty: {$qty}, Harga: {$harga}");

                        // Reduce stock for products used in perawatan
                        if ($data['status'] === 'Berhasil') {
                            Log::info("💆🔄 Calling kurangiStokProdukBulk for perawatan {$perawatan->Nama_Perawatan}");
                            $perawatan->kurangiStokProdukBulk(
                                (int)$qty,
                                $pesanan->id
                            );
                            Log::info("💆✅ Berhasil mengurangi stok untuk perawatan {$perawatan->Nama_Perawatan}");
                        } else {
                            Log::info("💆⏸️ Skipping stock reduction untuk perawatan - status bukan 'Berhasil'");
                        }
                    }
                } else {
                    Log::info("💆 No perawatan in items_perawatan");
                }

                // 4. VERIFIKASI DATA YANG DISIMPAN DAN TOTAL
                Log::info("🔍 VERIFIKASI DATA PESANAN #{$pesanan->id}:");

                // Hitung total dari database untuk memastikan akurasi
                $detailProduk = PesananProduk::where('pesanan_id', $pesanan->id)->get();
                $detailPerawatan = PesananPerawatan::where('pesanan_id', $pesanan->id)->get();

                $totalProduk = $detailProduk->sum(function ($item) {
                    return $item->harga * $item->qty;
                });

                $totalPerawatan = $detailPerawatan->sum(function ($item) {
                    return $item->harga * $item->qty;
                });

                $grandTotal = $totalProduk + $totalPerawatan;

                // **LOG DETAIL SETIAP ITEM**
                Log::info("🔍 DETAIL PRODUK:");
                foreach ($detailProduk as $item) {
                    $produk = Produk::find($item->produk_id);
                    $produkNama = $produk ? $produk->Nama : 'Produk tidak ditemukan';
                    $subtotal = $item->harga * $item->qty;
                    Log::info("🔍   - {$produkNama}: {$item->qty} x Rp {$item->harga} = Rp {$subtotal}");
                }

                Log::info("🔍 DETAIL PERAWATAN:");
                foreach ($detailPerawatan as $item) {
                    $perawatan = Perawatan::find($item->perawatan_id);
                    $perawatanNama = $perawatan ? $perawatan->Nama_Perawatan : 'Perawatan tidak ditemukan';
                    $subtotal = $item->harga * $item->qty;
                    Log::info("🔍   - {$perawatanNama}: {$item->qty} x Rp {$item->harga} = Rp {$subtotal}");
                }

                Log::info("🔍 Total Produk: Rp. " . number_format($totalProduk, 0, ',', '.'));
                Log::info("🔍 Total Perawatan: Rp. " . number_format($totalPerawatan, 0, ',', '.'));
                Log::info("🔍 Grand Total: Rp. " . number_format($grandTotal, 0, ',', '.'));

                // **VERIFIKASI STOK MOVEMENT**
                $stokMovementsCount = \App\Models\StokMovement::where('keterangan', 'like', "%pesanan #{$pesanan->id}%")->count();
                Log::info("🔍 Jumlah Stok Movement terkait: {$stokMovementsCount}");

                Log::info("🎉 === PESANAN CREATION COMPLETED SUCCESSFULLY ===");
                return $pesanan;

            } catch (\Exception $e) {
                Log::error("💥 Error creating pesanan: " . $e->getMessage());
                Log::error("💥 Stack trace: " . $e->getTraceAsString());

                // **LOG DATA YANG MENYEBABKAN ERROR**
                Log::error("💥 Data yang menyebabkan error:");
                Log::error("💥 items_produk: " . json_encode($data['items_produk'] ?? []));
                Log::error("💥 items_perawatan: " . json_encode($data['items_perawatan'] ?? []));

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
