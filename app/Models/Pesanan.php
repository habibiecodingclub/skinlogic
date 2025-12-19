<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Tambahkan relasi
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    // **RELASI DETAIL PRODUK - PASTIKAN NAMA TABLE BENAR**
    public function detailProduk(): HasMany
    {
        return $this->hasMany(PesananProduk::class, 'pesanan_id');
    }

    // **RELASI DETAIL PERAWATAN - PASTIKAN NAMA TABLE BENAR**
    public function detailPerawatan(): HasMany
    {
        return $this->hasMany(PesananPerawatan::class, 'pesanan_id');
    }

    // **ATTRIBUTE TOTAL HARGA - PAKAI CARA YANG LEBIH SIMPLE**
    public function getTotalAttribute()
    {
        $total = 0;

        // Hitung total dari produk
        foreach ($this->detailProduk as $item) {
            $total += $item->harga * $item->qty;
        }

        // Hitung total dari perawatan
        foreach ($this->detailPerawatan as $item) {
            $total += $item->harga * $item->qty;
        }

        return $total;
    }

    // **ALIAS UNTUK COMPATIBILITY**
    public function getTotalHargaAttribute()
    {
        return $this->total;
    }

    public function getTotalPembayaranAttribute()
    {
        return $this->total;
    }
}
