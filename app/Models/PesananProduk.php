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

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }
}

