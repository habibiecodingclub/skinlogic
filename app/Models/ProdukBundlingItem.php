<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukBundlingItem extends Model
{
    use HasFactory;

    protected $table = 'produk_bundling';

    protected $guarded = [];

    /**
     * Relasi ke produk bundling (parent)
     */
    public function produkBundling(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_bundling_id');
    }

    /**
     * Relasi ke produk dalam bundling (child)
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Attribute untuk subtotal
     */
    public function getSubtotalAttribute()
    {
        return $this->harga_satuan * $this->qty;
    }

    /**
     * Attribute untuk nama produk
     */
    public function getNamaProdukAttribute()
    {
        return $this->produk ? $this->produk->Nama : 'Produk tidak ditemukan';
    }

    /**
     * Attribute untuk stok produk
     */
    public function getStokProdukAttribute()
    {
        return $this->produk ? $this->produk->Stok : 0;
    }
}
