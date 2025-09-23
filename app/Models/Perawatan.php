<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perawatan extends Model
{
    use HasFactory;

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

        // RELASI BARU: Produk yang digunakan dalam perawatan - DIPERBAIKI
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

    // Method untuk mengurangi stok produk ketika perawatan dilakukan
    public function kurangiStokProduk()
    {
        foreach ($this->produk as $produk) {
            $qtyDigunakan = $produk->pivot->qty_digunakan;

            if ($produk->Stok < $qtyDigunakan) {
                throw new \Exception("Stok {$produk->Nama} tidak mencukupi untuk perawatan {$this->Nama_Perawatan}");
            }

            // Kurangi stok produk
            $produk->decrement('Stok', $qtyDigunakan);

            // Catat di stok movement
            \App\Models\StokMovement::create([
                'produk_id' => $produk->id,
                'tipe' => 'keluar',
                'jumlah' => $qtyDigunakan,
                'keterangan' => "Penggunaan untuk perawatan: {$this->Nama_Perawatan}",
                'tanggal' => now(),
            ]);
        }
    }

    // Method untuk mengembalikan stok produk ketika perawatan dibatalkan
    public function kembalikanStokProduk()
    {
        foreach ($this->produk as $produk) {
            $qtyDigunakan = $produk->pivot->qty_digunakan;

            // Kembalikan stok produk
            $produk->increment('Stok', $qtyDigunakan);

            // Catat di stok movement
            \App\Models\StokMovement::create([
                'produk_id' => $produk->id,
                'tipe' => 'masuk',
                'jumlah' => $qtyDigunakan,
                'keterangan' => "Pembatalan penggunaan untuk perawatan: {$this->Nama_Perawatan}",
                'tanggal' => now(),
            ]);
        }
    }
}
