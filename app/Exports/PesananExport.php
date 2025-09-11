<?php
namespace App\Exports;

use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Pesanan;

class PesananExport extends ExcelExport implements WithMapping, WithHeadings, WithStyles
{
    public function __construct()
    {
        parent::__construct(
            'Pesanan Export',
            Pesanan::with(['pelanggan', 'detailProduk.produk'])
        );
        $this->filename = 'pesanan-export-' . date('Y-m-d') . '.xlsx';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Pelanggan',
            'Produk',
            'Jumlah',
            'Harga',
            'Total',
            'Tanggal'
        ];
    }

    public function map($pesanan): array
    {
        return [
            $pesanan->id,
            $pesanan->pelanggan->Nama,
            $pesanan->detailProduk->map(fn($item) => $item->produk->Nama)->implode("\n"),
            $pesanan->detailProduk->map(fn($item) => $item->qty)->implode("\n"),
            $pesanan->detailProduk->map(fn($item) => 'Rp '.number_format($item->harga, 0))->implode("\n"),
            'Rp '.number_format($pesanan->detailProduk->sum('harga'), 0),
            $pesanan->created_at->format('d/m/Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:G' => ['alignment' => ['wrapText' => true]],
        ];
    }
}
