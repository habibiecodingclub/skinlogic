<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    use HasFactory;
    protected $guarded = [];

     public function getNamaAttribute()
    {
        return $this->attributes['Nama'] ?? $this->attributes['nama'] ?? null;
    }

    public function getStatusAttribute()
    {
        return $this->attributes['Status'] ?? $this->attributes['status'] ?? null;
    }

    // Perbaiki relasi pesanans
    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'pelanggan_id'); // Perbaiki di sini
    }
}
