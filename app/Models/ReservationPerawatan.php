<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationPerawatan extends Model
{
    use HasFactory;

    protected $table = 'reservation_perawatan';

    protected $guarded = [];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function perawatan(): BelongsTo
    {
        return $this->belongsTo(Perawatan::class, 'perawatan_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->harga * $this->qty;
    }
}
