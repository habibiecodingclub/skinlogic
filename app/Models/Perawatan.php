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

    // Tambahkan relasi ke pesanan
    public function pesanans(): BelongsToMany
    {
        return $this->belongsToMany(Pesanan::class, 'pesanan_perawatan');
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(PesananPerawatan::class);
    }
}
