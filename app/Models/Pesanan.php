<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function produk(): BelongsToMany
    {
        return $this->belongsToMany(Produk::class, 'pesanan_produk');
    }

    public function detailProduk(): HasMany
    {
        return $this->hasMany(PesananProduk::class);
    }

    // Tambahkan relasi untuk perawatan
    public function perawatans(): BelongsToMany
    {
        return $this->belongsToMany(Perawatan::class, 'pesanan_perawatan');
    }

    public function detailPerawatan(): HasMany
    {
        return $this->hasMany(PesananPerawatan::class);
    }

    // Method untuk total harga
    public function getTotalHargaAttribute()
    {
        $totalProduk = $this->detailProduk->sum(function($item) {
            return $item->harga * $item->qty;
        });

        $totalPerawatan = $this->detailPerawatan->sum(function($item) {
            return $item->harga * $item->qty;
        });

        return $totalProduk + $totalPerawatan;
    }
}
