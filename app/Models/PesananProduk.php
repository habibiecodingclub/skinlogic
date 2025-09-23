<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananProduk extends Model
{
    use HasFactory;

    protected $table = 'pesanan_produk';

    protected $guarded = [];

    // Event boot untuk handle stok via StokMovement
    protected static function boot()
    {
        parent::boot();

        // Saat pesanan produk dibuat, buat stok movement
        static::created(function ($pesananProduk) {
            $produk = $pesananProduk->produk;

            if ($produk) {
                // Buat stok movement untuk penjualan
                \App\Models\StokMovement::create([
                    'produk_id' => $produk->id,
                    'tipe' => 'keluar',
                    'jumlah' => $pesananProduk->qty,
                    'keterangan' => "Penjualan pesanan #{$pesananProduk->pesanan_id}",
                    'tanggal' => now(),
                ]);
            }
        });

        // Saat pesanan produk diupdate, update stok movement
        static::updated(function ($pesananProduk) {
            $originalQty = $pesananProduk->getOriginal('qty');
            $newQty = $pesananProduk->qty;

            if ($newQty != $originalQty) {
                $produk = $pesananProduk->produk;

                if ($produk) {
                    // Hapus movement lama dan buat baru
                    \App\Models\StokMovement::where('keterangan', "Penjualan pesanan #{$pesananProduk->pesanan_id}")
                        ->where('produk_id', $produk->id)
                        ->delete();

                    // Buat movement baru dengan quantity yang updated
                    \App\Models\StokMovement::create([
                        'produk_id' => $produk->id,
                        'tipe' => 'keluar',
                        'jumlah' => $newQty,
                        'keterangan' => "Penjualan pesanan #{$pesananProduk->pesanan_id}",
                        'tanggal' => now(),
                    ]);
                }
            }
        });

        // Saat pesanan produk dihapus, buat stok movement pengembalian
        static::deleted(function ($pesananProduk) {
            $produk = $pesananProduk->produk;

            if ($produk) {
                // Buat stok movement untuk pengembalian
                \App\Models\StokMovement::create([
                    'produk_id' => $produk->id,
                    'tipe' => 'masuk',
                    'jumlah' => $pesananProduk->qty,
                    'keterangan' => "Pembatalan pesanan #{$pesananProduk->pesanan_id}",
                    'tanggal' => now(),
                ]);
            }
        });
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->harga * $this->qty;
    }
}
