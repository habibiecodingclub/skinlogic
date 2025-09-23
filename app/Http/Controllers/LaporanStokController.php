<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanStokController extends Controller
{
    public function cetak(Request $request)
    {
        $bulanTahun = $request->get('bulan_tahun', now()->format('m-Y'));
        [$bulan, $tahun] = explode('-', $bulanTahun);

        $produks = Produk::with(['stokMovements' => function($query) use ($tahun, $bulan) {
            $query->whereYear('tanggal', $tahun)
                  ->whereMonth('tanggal', $bulan)
                  ->orderBy('tanggal');
        }])->get();

        $data = [
            'produks' => $produks,
            'periode' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nama_bulan' => Carbon::create($tahun, $bulan, 1)->locale('id')->monthName,
            ],
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = Pdf::loadView('laporan.stok-pdf', $data);
        return $pdf->download('laporan-stok-' . $bulanTahun . '.pdf');
    }
}
