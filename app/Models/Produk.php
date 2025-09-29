<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Produk extends Model
{
    use HasFactory;

    protected $guarded = [];

    // **TAMBAHKAN METHOD INI**
    // Di Model Produk, tambahkan logging di method tambahStok()
  // Di Model Produk, tambahkan logging
public function tambahStok($jumlah, $keterangan = null, $tanggal = null)
{
    log::info("=== TAMBAH STOK DIPANGGIL ===");
    Log::info("Produk: {$this->Nama} (ID: {$this->id})");
    Log::info("Stok sebelum: {$this->Stok}");
    Log::info("Jumlah tambah: {$jumlah}");
    Log::info("Keterangan: {$keterangan}");

    // Update stok
    $stokSebelum = $this->Stok;
    $this->increment('Stok', $jumlah);

    Log::info("Stok sesudah: {$this->Stok}");
    Log::info("Perhitungan: {$stokSebelum} + {$jumlah} = {$this->Stok}");

    // Buat stok movement
    $movement = \App\Models\StokMovement::create([
        'produk_id' => $this->id,
        'tipe' => 'masuk',
        'jumlah' => $jumlah,
        'keterangan' => $keterangan ?? 'Penambahan stok manual',
        'tanggal' => $tanggal ?? now(),
    ]);

    Log::info("Movement created: {$movement->id}");
    Log::info("=== END ===");

    return $movement;
}

    /**
     * Method untuk mengurangi stok
     */
    // public function kurangiStok($jumlah, $keterangan = null, $tanggal = null)
    // {
    //     // Update stok
    //     $this->decrement('Stok', $jumlah);

    //     // Buat stok movement
    //     $movement = \App\Models\StokMovement::create([
    //         'produk_id' => $this->id,
    //         'tipe' => 'keluar',
    //         'jumlah' => $jumlah,
    //         'keterangan' => $keterangan ?? 'Pengurangan stok manual',
    //         'tanggal' => $tanggal ?? now(),
    //     ]);

    //     return $movement;
    // }
// DI MODEL Produk - PERBAIKI dengan transaction & locking
// DI MODEL Produk - PASTIKAN METHOD INI ADA DAN BERFUNGSI
public function kurangiStok($jumlah, $keterangan = null, $tanggal = null)
{
    return \Illuminate\Support\Facades\DB::transaction(function () use ($jumlah, $keterangan, $tanggal) {
        // Lock row untuk prevent race condition
        $produk = self::lockForUpdate()->find($this->id);

        Log::info("=== KURANGI STOK DIPANGGIL ===");
        Log::info("Produk: {$produk->Nama} (ID: {$produk->id})");
        Log::info("Stok sebelum: {$produk->Stok}");
        Log::info("Jumlah kurangi: {$jumlah}");
        Log::info("Keterangan: {$keterangan}");

        // Validasi stok cukup
        if ($produk->Stok < $jumlah) {
            throw new \Exception("Stok {$produk->Nama} tidak mencukupi. Stok tersedia: {$produk->Stok}, diperlukan: {$jumlah}");
        }

        // Update stok
        $stokSebelum = $produk->Stok;
        $produk->decrement('Stok', $jumlah);
        $produk->refresh();

        Log::info("Stok sesudah: {$produk->Stok}");
        Log::info("Perhitungan: {$stokSebelum} - {$jumlah} = {$produk->Stok}");

        // Buat stok movement
        $movement = \App\Models\StokMovement::create([
            'produk_id' => $produk->id,
            'tipe' => 'keluar',
            'jumlah' => $jumlah,
            'keterangan' => $keterangan ?? 'Pengurangan stok manual',
            'tanggal' => $tanggal ?? now(),
        ]);

        Log::info("Movement created: {$movement->id}");
        Log::info("=== END KURANGI STOK ===");

        return $movement;
    });
}

    public function updateStokWithMovement($jumlah, $tipe, $keterangan, $tanggal = null)
    {
        // Update stok
        if ($tipe === 'masuk') {
            $this->increment('Stok', $jumlah);
        } else {
            $this->decrement('Stok', $jumlah);
        }

        // Buat stok movement
        return \App\Models\StokMovement::create([
            'produk_id' => $this->id,
            'tipe' => $tipe,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan,
            'tanggal' => $tanggal ?? now(),
        ]);
    }

    // **TAMBAHKAN METHOD INI di Model Produk**
/**
 * Method untuk mendapatkan stok pada tanggal tertentu
 */
public function getStokPadaTanggal($tanggal)
{
    $tanggal = \Carbon\Carbon::parse($tanggal);
    $tahun = $tanggal->year;
    $bulan = $tanggal->month;

    // Jika tanggal adalah hari pertama bulan, return stok awal bulan
    if ($tanggal->day == 1) {
        return $this->getStokAwalBulan($tahun, $bulan);
    }

    // Jika bukan hari pertama, hitung stok sampai tanggal tersebut
    $stokAwalBulan = $this->getStokAwalBulan($tahun, $bulan);

    $masukSampaiTanggal = $this->stokMovements()
        ->where('tipe', 'masuk')
        ->whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulan)
        ->whereDay('tanggal', '<=', $tanggal->day)
        ->sum('jumlah');

    $keluarSampaiTanggal = $this->stokMovements()
        ->where('tipe', 'keluar')
        ->whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulan)
        ->whereDay('tanggal', '<=', $tanggal->day)
        ->sum('jumlah');

    return $stokAwalBulan + $masukSampaiTanggal - $keluarSampaiTanggal;
}

/**
 * Method untuk mendapatkan stok akhir bulan tertentu
 */
public function getStokAkhirBulanPadaTanggal($tanggal)
{
    $tanggal = \Carbon\Carbon::parse($tanggal);
    $tahun = $tanggal->year;
    $bulan = $tanggal->month;

    return $this->getStokAkhirBulan($tahun, $bulan);
}

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

    public function perawatans(): BelongsToMany
    {
        return $this->belongsToMany(Perawatan::class, 'perawatan_produk')
            ->withPivot('qty_digunakan', 'keterangan')
            ->withTimestamps();
    }

    // **Method untuk mendapatkan stok awal bulan**
    // **PERBAIKI METHOD INI di Model Produk**
// **PERBAIKI METHOD getStokAwalBulan() di Model Produk**
// Di Model Produk, perbaiki method getStokAwalBulan()
// Di Model Produk, perbaiki method getStokAwalBulan()
public function getStokAwalBulan($tahun = null, $bulan = null)
{
    $tahun = $tahun ?? date('Y');
    $bulan = $bulan ?? date('m');

    $tanggalAwalBulan = \Carbon\Carbon::create($tahun, $bulan, 1);

    // **CEK: Apakah ada movement stok sebelum bulan ini?**
    $movementPertama = $this->stokMovements()
        ->orderBy('tanggal', 'asc')
        ->first();

    if (!$movementPertama) {
        return 0; // Tidak ada movement sama sekali
    }

    $tanggalMovementPertama = \Carbon\Carbon::parse($movementPertama->tanggal);

    // **Jika movement pertama terjadi setelah/saat bulan ini, stok awal = 0**
    if ($tanggalMovementPertama->greaterThanOrEqualTo($tanggalAwalBulan)) {
        return 0;
    }

    // Untuk bulan pertama (Januari), stok awal = 0
    if ($bulan == 1) {
        return 0;
    }

    // Stok awal = stok akhir bulan sebelumnya
    $bulanSebelumnya = $bulan - 1;
    $tahunSebelumnya = $tahun;

    if ($bulan == 1) {
        $bulanSebelumnya = 12;
        $tahunSebelumnya = $tahun - 1;
    }

    return $this->getStokAkhirBulan($tahunSebelumnya, $bulanSebelumnya);
}

// **Method getStokAkhirBulan() tetap sama**
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

    // **Method untuk mendapatkan history stok per tanggal**
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

    // **Method untuk stok awal hari tertentu**
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
