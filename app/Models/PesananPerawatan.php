<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananPerawatan extends Model
{
    use HasFactory;

    protected $table = 'pesanan_perawatan';
    protected $guarded = [];

    public function perawatan(): BelongsTo
    {
        return $this->belongsTo(Perawatan::class, 'perawatan_id')->withTrashed();
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
