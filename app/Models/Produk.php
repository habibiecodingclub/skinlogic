<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    //
    use HasFactory;

    protected $guarded = [];



    public function pesanan(): BelongsToMany
    {
        return $this->belongsToMany(Pesanan::class,'pesanan_produk');
    }
}
