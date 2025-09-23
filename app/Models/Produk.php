<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pesanan(): BelongsToMany
    {
        return $this->belongsToMany(Pesanan::class, 'pesanan_produk')
            ->withPivot('qty', 'harga')
            ->withTimestamps();
    }

    public function stokMovements(): HasMany
    {
        return $this->hasMany(StokMovement::class, 'produk_id');
    }




    // **BARU: Method untuk mendapatkan stok awal bulan**
    public function getStokAwalBulan($tahun = null, $bulan = null)
{
    $tahun = $tahun ?? date('Y');
    $bulan = $bulan ?? date('m');

    // Untuk bulan pertama, stok awal = 0
    if ($bulan == 1) {
        return 0;
    }

    // Stok awal = stok akhir bulan sebelumnya
    $bulanSebelumnya = $bulan - 1;
    $tahunSebelumnya = $tahun;

    return $this->getStokAkhirBulan($tahunSebelumnya, $bulanSebelumnya);
}

 public function perawatans(): BelongsToMany
    {
        return $this->belongsToMany(Perawatan::class, 'perawatan_produk')
            ->withPivot('qty_digunakan', 'keterangan')
            ->withTimestamps();
    }

public function getStokAkhirBulan($tahun = null, $bulan = null)
{
    $tahun = $tahun ?? date('Y');
    $bulan = $bulan ?? date('m');

    $stokAwal = $this->getStokAwalBulan($tahun, $bulan);

    $totalMasuk = $this->stokMovements()
        ->where('tipe', 'masuk')
        ->whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulan)
        ->sum('jumlah');

    $totalKeluar = $this->stokMovements()
        ->where('tipe', 'keluar')
        ->whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulan)
        ->sum('jumlah');

    return $stokAwal + $totalMasuk - $totalKeluar;
}

public function getRekapBulanan($tahun = null, $bulan = null)
{
    $tahun = $tahun ?? date('Y');
    $bulan = $bulan ?? date('m');

    $stokAwal = $this->getStokAwalBulan($tahun, $bulan);
    $stokAkhir = $this->getStokAkhirBulan($tahun, $bulan);

    $totalMasuk = $this->stokMovements()
        ->where('tipe', 'masuk')
        ->whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulan)
        ->sum('jumlah');

    $totalKeluar = $this->stokMovements()
        ->where('tipe', 'keluar')
        ->whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulan)
        ->sum('jumlah');

    return [
        'stok_awal' => $stokAwal,
        'stok_akhir' => $stokAkhir,
        'total_masuk' => $totalMasuk,
        'total_keluar' => $totalKeluar,
    ];
}

    // **BARU: Method untuk mendapatkan history stok per tanggal**
    public function getHistoryHarian($tahun = null, $bulan = null)
    {
        $tahun = $tahun ?? date('Y');
        $bulan = $bulan ?? date('m');

        return $this->stokMovements()
            ->select(
                'tanggal',
                DB::raw('SUM(CASE WHEN tipe = "masuk" THEN jumlah ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN tipe = "keluar" THEN jumlah ELSE 0 END) as keluar')
            )
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(function ($item) use ($tahun, $bulan) {
                $stokAwalHari = $this->getStokAwalHari($item->tanggal);
                $stokAkhirHari = $stokAwalHari + $item->masuk - $item->keluar;

                return [
                    'tanggal' => $item->tanggal,
                    'stok_awal' => $stokAwalHari,
                    'masuk' => $item->masuk,
                    'keluar' => $item->keluar,
                    'stok_akhir' => $stokAkhirHari
                ];
            });
    }

    // **BARU: Method untuk stok awal hari tertentu**
    public function getStokAwalHari($tanggal)
    {
        $tanggal = \Carbon\Carbon::parse($tanggal);
        $tahun = $tanggal->year;
        $bulan = $tanggal->month;
        $hari = $tanggal->day;

        // Stok awal hari = stok akhir hari sebelumnya
        $hariSebelumnya = $tanggal->copy()->subDay();

        return $this->getStokAkhirBulan($hariSebelumnya->year, $hariSebelumnya->month)
            + $this->stokMovements()
                ->where('tipe', 'masuk')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->whereDay('tanggal', '<', $hari)
                ->sum('jumlah')
            - $this->stokMovements()
                ->where('tipe', 'keluar')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->whereDay('tanggal', '<', $hari)
                ->sum('jumlah');
    }
}
