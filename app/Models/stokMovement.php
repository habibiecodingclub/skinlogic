<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokMovement extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Event boot untuk update stok produk
    protected static function boot()
    {
        parent::boot();

        // Saat stok movement dibuat, update stok produk
        static::creating(function ($stokMovement) {
            $produk = $stokMovement->produk;

            if (!$produk) {
                throw new \Exception("Produk tidak ditemukan");
            }

            // Update stok produk berdasarkan tipe movement
            if ($stokMovement->tipe === 'masuk') {
                $produk->increment('Stok', $stokMovement->jumlah);
            } else if ($stokMovement->tipe === 'keluar') {
                if ($produk->Stok < $stokMovement->jumlah) {
                    throw new \Exception("Stok {$produk->Nama} tidak mencukupi. Stok tersedia: {$produk->Stok}, diperlukan: {$stokMovement->jumlah}");
                }
                $produk->decrement('Stok', $stokMovement->jumlah);
            }
        });

        // Saat stok movement diupdate, adjust stok produk
        static::updating(function ($stokMovement) {
            $originalJumlah = $stokMovement->getOriginal('jumlah');
            $newJumlah = $stokMovement->jumlah;
            $originalTipe = $stokMovement->getOriginal('tipe');
            $newTipe = $stokMovement->tipe;

            $produk = $stokMovement->produk;

            if (!$produk) {
                throw new \Exception("Produk tidak ditemukan");
            }

            // Logic yang kompleks untuk handle update, lebih simple: hapus dan buat baru
            // Untuk sementara, kita batalkan update yang mengubah tipe atau jumlah
            if ($originalTipe !== $newTipe || $originalJumlah !== $newJumlah) {
                throw new \Exception("Tidak dapat mengubah tipe atau jumlah stok movement. Silahkan hapus dan buat baru.");
            }
        });

        // Saat stok movement dihapus, kembalikan stok produk
        static::deleting(function ($stokMovement) {
            $produk = $stokMovement->produk;

            if ($produk) {
                // Kembalikan stok berdasarkan tipe movement yang dihapus
                if ($stokMovement->tipe === 'masuk') {
                    $produk->decrement('Stok', $stokMovement->jumlah);
                } else if ($stokMovement->tipe === 'keluar') {
                    $produk->increment('Stok', $stokMovement->jumlah);
                }
            }
        });
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
