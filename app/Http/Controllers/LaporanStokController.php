<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class LaporanStokController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('m'));

        $produks = Produk::with('stokMovements')->get();

        $laporan = [];

        foreach ($produks as $produk) {
            $stokAwal = $produk->getStokAwalBulan($tahun, $bulan);
            $stokAkhir = $produk->getStokAkhirBulan($tahun, $bulan);
            $totalMasuk = $produk->stokMovements()
                ->where('tipe', 'masuk')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->sum('jumlah');
            $totalKeluar = $produk->stokMovements()
                ->where('tipe', 'keluar')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->sum('jumlah');

            $laporan[] = [
                'produk' => $produk->Nama,
                'stok_awal' => $stokAwal,
                'stok_masuk' => $totalMasuk,
                'stok_keluar' => $totalKeluar,
                'stok_akhir' => $stokAkhir,
            ];
        }

        return view('laporan.stok', compact('laporan', 'tahun', 'bulan'));
    }

    /**
     * Laporan stok per tanggal tertentu
     */
    public function perTanggal(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $tanggalCarbon = \Carbon\Carbon::parse($tanggal);

        $produks = Produk::all();

        $laporan = [];

        foreach ($produks as $produk) {
            $stokPadaTanggal = $produk->getStokPadaTanggal($tanggal);

            $laporan[] = [
                'produk' => $produk->Nama,
                'stok_tanggal' => $stokPadaTanggal,
                'stok_akhir_bulan' => $produk->getStokAkhirBulan(
                    $tanggalCarbon->year,
                    $tanggalCarbon->month
                ),
            ];
        }

        return view('laporan.stok-tanggal', compact('laporan', 'tanggal'));
    }
}
