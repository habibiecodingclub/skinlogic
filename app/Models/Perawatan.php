<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Perawatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    // Relasi ke pesanan
    public function pesanans(): BelongsToMany
    {
        return $this->belongsToMany(Pesanan::class, 'pesanan_perawatan');
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(PesananPerawatan::class);
    }

    // RELASI PRODUK - PASTIKAN INI BEKERJA
    public function produk(): BelongsToMany
    {
        return $this->belongsToMany(Produk::class, 'perawatan_produk')
            ->withPivot('qty_digunakan', 'keterangan')
            ->withTimestamps();
    }

    // Method untuk menghitung total harga produk yang digunakan
    public function getTotalHargaProdukAttribute()
    {
        return $this->produk->sum(function ($produk) {
            return $produk->Harga * $produk->pivot->qty_digunakan;
        });
    }

    // Method untuk menghitung harga jual recommended (Harga perawatan + total harga produk)
    public function getHargaJualRecommendedAttribute()
    {
        return $this->Harga + $this->total_harga_produk;
    }

    /**
     * Method untuk mengurangi stok produk ketika perawatan dilakukan
     * VERSI DEBUG - LEBIH DETAIL
     */
    public function kurangiStokProdukBulk($quantity, $pesananId = null)
    {
        Log::info("🎯 === KURANGI STOK PRODUK BULK DIPANGGIL ===");
        Log::info("🔧 Perawatan ID: {$this->id}");
        Log::info("🔧 Nama Perawatan: {$this->Nama_Perawatan}");
        Log::info("🔧 Quantity Perawatan: {$quantity}");
        Log::info("🔧 Pesanan ID: " . ($pesananId ?? 'Tidak ada'));

        // PASTIKAN PRODUK TERLOAD DENGAN BENAR
        $this->load('produk');

        Log::info("🔧 Jumlah produk dalam perawatan: " . $this->produk->count());

        if ($this->produk->count() === 0) {
            Log::warning("⚠️ Tidak ada produk yang terkait dengan perawatan ini!");
            return;
        }

        foreach ($this->produk as $index => $produk) {
            Log::info("🔧 Processing produk {$index}:");
            Log::info("   - Produk ID: {$produk->id}");
            Log::info("   - Nama Produk: {$produk->Nama}");
            Log::info("   - Stok Sebelum: {$produk->Stok}");
            Log::info("   - Qty Digunakan per Unit: {$produk->pivot->qty_digunakan}");

            $totalQtyDigunakan = $produk->pivot->qty_digunakan * $quantity;
            Log::info("   - Total Qty Digunakan: {$totalQtyDigunakan}");

            // VALIDASI STOK
            // if ($produk->Stok < $totalQtyDigunakan) {
            //     $errorMsg = "❌ Stok produk {$produk->Nama} tidak mencukupi. Stok tersedia: {$produk->Stok}, diperlukan: {$totalQtyDigunakan}";
            //     Log::error($errorMsg);
            //     throw new \Exception($errorMsg);
            // }

            // KURANGI STOK - PAKAI METHOD YANG SUDAH TERBUKTI BEKERJA
            try {
                Log::info("   📝 Memanggil kurangiStok untuk produk {$produk->Nama}...");

                $produk->kurangiStok(
                    $totalQtyDigunakan,
                    "Penggunaan untuk perawatan: {$this->Nama_Perawatan} (Qty: {$quantity})" . ($pesananId ? " (Pesanan #{$pesananId})" : ""),
                    now()
                );

                Log::info("   ✅ Berhasil mengurangi stok untuk produk {$produk->Nama}");

            } catch (\Exception $e) {
                Log::error("   ❌ Gagal mengurangi stok untuk produk {$produk->Nama}: " . $e->getMessage());
                throw $e;
            }
        }

        Log::info("🎯 === BERHASIL KURANGI SEMUA STOK UNTUK PERAWATAN ===");
    }

    /**
     * Method untuk mengembalikan stok produk ketika perawatan dibatalkan
     */
    /**
 * Method untuk mengembalikan stok produk ketika perawatan dibatalkan
 * VERSI DEBUG - LEBIH DETAIL
 */
public function kembalikanStokProdukBulk($quantity, $pesananId = null)
{
    Log::info("🔄🎯 === KEMBALIKAN STOK PRODUK BULK DIPANGGIL ===");
    Log::info("🔄🔧 Perawatan ID: {$this->id}");
    Log::info("🔄🔧 Nama Perawatan: {$this->Nama_Perawatan}");
    Log::info("🔄🔧 Quantity Perawatan: {$quantity}");
    Log::info("🔄🔧 Pesanan ID: " . ($pesananId ?? 'Tidak ada'));

    // PASTIKAN PRODUK TERLOAD DENGAN BENAR
    $this->load('produk');

    Log::info("🔄🔧 Jumlah produk dalam perawatan: " . $this->produk->count());

    if ($this->produk->count() === 0) {
        Log::warning("🔄⚠️ Tidak ada produk yang terkait dengan perawatan ini!");
        return;
    }

    foreach ($this->produk as $index => $produk) {
        Log::info("🔄🔧 Processing produk {$index}:");
        Log::info("🔄   - Produk ID: {$produk->id}");
        Log::info("🔄   - Nama Produk: {$produk->Nama}");
        Log::info("🔄   - Stok Sebelum: {$produk->Stok}");
        Log::info("🔄   - Qty Digunakan per Unit: {$produk->pivot->qty_digunakan}");

        $totalQtyDikembalikan = $produk->pivot->qty_digunakan * $quantity;
        Log::info("🔄   - Total Qty Dikembalikan: {$totalQtyDikembalikan}");

        // KEMBALIKAN STOK - PAKAI METHOD YANG SUDAH TERBUKTI BEKERJA
        try {
            Log::info("🔄   📝 Memanggil tambahStok untuk produk {$produk->Nama}...");

            $produk->tambahStok(
                $totalQtyDikembalikan,
                "Pembatalan penggunaan untuk perawatan: {$this->Nama_Perawatan} (Qty: {$quantity})" . ($pesananId ? " (Pesanan #{$pesananId})" : ""),
                now()
            );

            Log::info("🔄   ✅ Berhasil mengembalikan stok untuk produk {$produk->Nama}");

        } catch (\Exception $e) {
            Log::error("🔄   ❌ Gagal mengembalikan stok untuk produk {$produk->Nama}: " . $e->getMessage());
            throw $e;
        }
    }

    Log::info("🔄🎯 === BERHASIL KEMBALIKAN SEMUA STOK UNTUK PERAWATAN ===");
}
}
