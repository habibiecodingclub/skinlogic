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

      protected $casts = [
        'is_bundling' => 'boolean',
    ];

    /**
 * Attribute untuk menghitung stok tersedia bundling
 * Stok bundling = stok terkecil dari komponen
 */
public function getStokTersediaAttribute()
{
    if (!$this->is_bundling) {
        return $this->Stok;
    }

    if ($this->produkBundlingItems->count() === 0) {
        return 0;
    }

    $stokTersedia = null;

    foreach ($this->produkBundlingItems as $item) {
        $stokKomponen = $item->produk->Stok;
        $maxBundling = floor($stokKomponen / $item->qty);

        if ($stokTersedia === null || $maxBundling < $stokTersedia) {
            $stokTersedia = $maxBundling;
        }
    }

    return $stokTersedia ?? 0;
}



     protected static function boot()
    {
        parent::boot();

        // Prevent manual stock updates
        static::updating(function ($produk) {
            $originalStok = $produk->getOriginal('Stok');
            $newStok = $produk->Stok;

            if ($originalStok != $newStok) {
                Log::warning("⚠️ Percobaan update stok manual untuk produk: {$produk->Nama} (ID: {$produk->id})");
                Log::warning("⚠️ Stok asli: {$originalStok}, Stok baru: {$newStok}");

                // Kembalikan ke nilai semula
                $produk->Stok = $originalStok;
            }
        });
    }

     // Produk yang merupakan bundling (parent)
    public function produkBundlingItems(): HasMany
    {
        return $this->hasMany(ProdukBundlingItem::class, 'produk_bundling_id');
    }

    // Produk yang termasuk dalam bundling lain (child)
    public function includedInBundlings(): HasMany
    {
        return $this->hasMany(ProdukBundlingItem::class, 'produk_id');
    }

    // **METHOD UNTUK BUNDLING**

    /**
     * Cek apakah produk adalah bundling
     */
    public function getIsBundlingAttribute($value)
    {
        return (bool) $value;
    }

    /**
     * Get harga yang akan digunakan (harga bundling atau harga normal)
     */
    public function getHargaJualAttribute()
{
    return $this->is_bundling ? $this->harga_bundling : $this->Harga;
}

    /**
     * Get total harga komponen bundling
     */
    public function getTotalHargaKomponenAttribute()
    {
        if (!$this->is_bundling) {
            return $this->Harga;
        }

        return $this->produkBundlingItems->sum(function ($item) {
            return $item->harga_satuan * $item->qty;
        });
    }

    /**
     * Get selisih harga bundling vs komponen
     */
    public function getSelisihHargaAttribute()
    {
        if (!$this->is_bundling) {
            return 0;
        }

        return $this->harga_bundling - $this->total_harga_komponen;
    }

    /**
     * Method untuk mengurangi stok bundling
     */
    public function kurangiStokBundling($jumlah, $keterangan = null, $tanggal = null)
    {
        Log::info("🎁 === KURANGI STOK BUNDLING ===");
        Log::info("🎁 Bundling: {$this->Nama} (ID: {$this->id})");
        Log::info("🎁 Quantity: {$jumlah}");

        if (!$this->is_bundling) {
            throw new \Exception("Produk {$this->Nama} bukan produk bundling");
        }

        return DB::transaction(function () use ($jumlah, $keterangan, $tanggal) {
            // Kurangi stok untuk setiap produk dalam bundling
          foreach ($this->produkBundlingItems as $item) {
            $totalQty = $item->qty * $jumlah;
            // No stock validation to allow negative stock
            $item->produk->decrement('Stok', $totalQty);

            StokMovement::create([
                'produk_id' => $item->produk_id,
                'tipe' => 'keluar',
                'jumlah' => $totalQty,
                'keterangan' => $keterangan . " (Bundling: {$this->Nama})",
                'tanggal' => $tanggal,
            ]);
        }

            Log::info("🎁✅ Berhasil mengurangi stok semua produk dalam bundling");
        });
    }

    /**
     * Method untuk mengembalikan stok bundling
     */
    public function kembalikanStokBundling($quantity, $keterangan = null, $tanggal = null)
    {
        Log::info("🔄🎁 === KEMBALIKAN STOK BUNDLING ===");
        Log::info("🔄🎁 Bundling: {$this->Nama} (ID: {$this->id})");
        Log::info("🔄🎁 Quantity: {$quantity}");

        if (!$this->is_bundling) {
            throw new \Exception("Produk {$this->Nama} bukan produk bundling");
        }

        return DB::transaction(function () use ($quantity, $keterangan, $tanggal) {
            // Kembalikan stok untuk setiap produk dalam bundling
            foreach ($this->produkBundlingItems as $item) {
                $produk = $item->produk;
                $totalQtyDikembalikan = $item->qty * $quantity;

                Log::info("🔄🎁 Kembalikan stok produk: {$produk->Nama}");
                Log::info("🔄🎁   - Qty per bundling: {$item->qty}");
                Log::info("🔄🎁   - Total qty dikembalikan: {$totalQtyDikembalikan}");

                $produk->tambahStok(
                    $totalQtyDikembalikan,
                    "Pengembalian dari bundling: {$this->Nama}" . ($keterangan ? " ({$keterangan})" : ""),
                    $tanggal ?? now()
                );
            }

            Log::info("🔄🎁✅ Berhasil mengembalikan stok semua produk dalam bundling");
        });
    }

    // **TAMBAHKAN METHOD INI**
    // Di Model Produk, tambahkan logging di method tambahStok()
  // Di Model Produk, tambahkan logging
 public function kurangiStok($jumlah, $keterangan = null, $tanggal = null)
    {
        Log::info("🔻 === PRODUK::KURANGI_STOK DIPANGGIL ===");
        Log::info("🔻 Produk: {$this->Nama} (ID: {$this->id})");
        Log::info("🔻 Jumlah: {$jumlah}");
        Log::info("🔻 Keterangan: {$keterangan}");

        return \Illuminate\Support\Facades\DB::transaction(function () use ($jumlah, $keterangan, $tanggal) {
            // Lock row untuk prevent race condition
            $produk = self::lockForUpdate()->find($this->id);

            Log::info("🔻 Stok sebelum: {$produk->Stok}");

            // Validasi stok cukup
            // if ($produk->Stok < $jumlah) {
            //     $errorMsg = "Stok {$produk->Nama} tidak mencukupi. Stok tersedia: {$produk->Stok}, diperlukan: {$jumlah}";
            //     Log::error("🔻 {$errorMsg}");
            //     throw new \Exception($errorMsg);
            // }

            // Update stok
            $stokSebelum = $produk->Stok;
            $produk->decrement('Stok', $jumlah);
            $produk->refresh();

            Log::info("🔻 Stok sesudah: {$produk->Stok}");
            Log::info("🔻 Perhitungan: {$stokSebelum} - {$jumlah} = {$produk->Stok}");

            // Buat stok movement
            $movement = \App\Models\StokMovement::create([
                'produk_id' => $produk->id,
                'tipe' => 'keluar',
                'jumlah' => $jumlah,
                'keterangan' => $keterangan ?? 'Pengurangan stok manual',
                'tanggal' => $tanggal ?? now(),
            ]);

            Log::info("🔻 Movement created: {$movement->id}");
            Log::info("🔻 === END PRODUK::KURANGI_STOK ===");

            return $movement;
        });
    }

    /**
     * Method untuk menambah stok - VERSI DEBUG
     */
    public function tambahStok($jumlah, $keterangan = null, $tanggal = null)
    {
        Log::info("🔺 === PRODUK::TAMBAH_STOK DIPANGGIL ===");
        Log::info("🔺 Produk: {$this->Nama} (ID: {$this->id})");
        Log::info("🔺 Jumlah: {$jumlah}");
        Log::info("🔺 Keterangan: {$keterangan}");

        // Update stok
        $stokSebelum = $this->Stok;
        $this->increment('Stok', $jumlah);
        $this->refresh();

        Log::info("🔺 Stok sebelum: {$stokSebelum}");
        Log::info("🔺 Stok sesudah: {$this->Stok}");
        Log::info("🔺 Perhitungan: {$stokSebelum} + {$jumlah} = {$this->Stok}");

        // Buat stok movement
        $movement = \App\Models\StokMovement::create([
            'produk_id' => $this->id,
            'tipe' => 'masuk',
            'jumlah' => $jumlah,
            'keterangan' => $keterangan ?? 'Penambahan stok manual',
            'tanggal' => $tanggal ?? now(),
        ]);

        Log::info("🔺 Movement created: {$movement->id}");
        Log::info("🔺 === END PRODUK::TAMBAH_STOK ===");

        return $movement;
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
