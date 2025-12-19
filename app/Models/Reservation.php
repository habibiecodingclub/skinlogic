<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Casting attributes
     */
    protected $casts = [
        'tanggal_reservasi' => 'date',
        'jam_reservasi' => 'string',
    ];

    /**
     * Status constants
     */
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    const STATUS_DIKERJAKAN = 'dikerjakan';
    const STATUS_SELESAI = 'selesai';
    const STATUS_BATAL = 'batal';

    /**
     * Get all status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_MENUNGGU => 'Menunggu',
            self::STATUS_DIKONFIRMASI => 'Dikonfirmasi',
            self::STATUS_DIKERJAKAN => 'Dikerjakan',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_BATAL => 'Batal',
        ];
    }

    /**
     * Get status label with badge color
     */
    public function getStatusLabelAttribute()
    {
        $colors = [
            self::STATUS_MENUNGGU => 'warning',
            self::STATUS_DIKONFIRMASI => 'info',
            self::STATUS_DIKERJAKAN => 'primary',
            self::STATUS_SELESAI => 'success',
            self::STATUS_BATAL => 'danger',
        ];

        return [
            'label' => self::getStatusOptions()[$this->status] ?? $this->status,
            'color' => $colors[$this->status] ?? 'gray'
        ];
    }

    /**
     * Relationships
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function terapis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'terapis_id');
    }

    public function perawatans(): BelongsToMany
    {
        return $this->belongsToMany(Perawatan::class, 'reservation_perawatan', 'reservation_id', 'perawatan_id')
            ->withPivot('id', 'qty', 'harga', 'created_at', 'updated_at');
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    /**
     * Accessor for tanggal_reservasi
     */
    protected function tanggalReservasi(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Carbon\Carbon::parse($value),
            set: fn ($value) => $value,
        );
    }

    /**
     * Calculate total price
     */
    public function getTotalHargaAttribute()
    {
        return $this->perawatans->sum(function ($perawatan) {
            return $perawatan->pivot->harga * $perawatan->pivot->qty;
        });
    }

    /**
     * Check if reservation can be converted to order
     */
    public function canConvertToOrder()
    {
        return $this->status === self::STATUS_SELESAI && !$this->pesanan_id;
    }

    /**
     * Scope for active reservations
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_SELESAI, self::STATUS_BATAL]);
    }

    /**
     * Scope for today's reservations
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_reservasi', today());
    }

    /**
     * Convert reservation to order
     */
    public function convertToOrder($metodePembayaran = 'Cash')
    {
        if (!$this->canConvertToOrder()) {
            throw new \Exception('Reservasi tidak bisa dikonversi ke pesanan');
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($metodePembayaran) {
            // Create new order
            $pesanan = Pesanan::create([
                'pelanggan_id' => $this->pelanggan_id,
                'Metode_Pembayaran' => $metodePembayaran,
                'status' => 'Berhasil',
            ]);

            // Add perawatans to order
            foreach ($this->perawatans as $perawatan) {
                $pesanan->detailPerawatan()->create([
                    'perawatan_id' => $perawatan->id,
                    'qty' => $perawatan->pivot->qty,
                    'harga' => $perawatan->pivot->harga,
                ]);
            }

            // Link reservation to order
            $this->update(['pesanan_id' => $pesanan->id]);

            return $pesanan;
        });
    }

    /**
     * Save perawatans from form data
     */
    public function savePerawatans(array $perawatansData)
    {
        $syncData = [];

        foreach ($perawatansData as $item) {
            if (!empty($item['perawatan_id'])) {
                $syncData[$item['perawatan_id']] = [
                    'qty' => $item['qty'] ?? 1,
                    'harga' => $item['harga'] ?? 0,
                ];
            }
        }

        $this->perawatans()->sync($syncData);
    }
}
