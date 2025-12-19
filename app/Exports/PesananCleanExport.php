<?php

namespace App\Exports;

use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PesananCleanExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, WithEvents
{
    private $pesananGroups = [];
    private $query;
    private $totalGrandTotal = 0;
    private $lastDataRow = 0;

    public function __construct(Builder $query = null)
    {
        $this->query = $query ?? Pesanan::with([
            'pelanggan',
            'detailProduk.produk',
            'detailPerawatan.perawatan'
        ])->orderBy('created_at', 'asc');
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Tanggal Pesanan',
            'Pelanggan',
            'Status Pesanan',
            'Metode Pembayaran',

            // Kolom untuk Produk
            'Nama Produk',
            'Qty Produk',
            'Harga Satuan Produk',
            'Sub Total Produk',

            // Kolom untuk Perawatan
            'Nama Perawatan',
            'Qty Perawatan',
            'Harga Satuan Perawatan',
            'Sub Total Perawatan',

            'Total Keseluruhan'
        ];
    }

    public function map($pesanan): array
    {
        $rows = [];
        $itemCount = 0;

        // Ambil semua produk dan perawatan
        $produkItems = $pesanan->detailProduk;
        $perawatanItems = $pesanan->detailPerawatan;

        // Cari jumlah maksimum item antara produk dan perawatan
        $maxItems = max(count($produkItems), count($perawatanItems));

        // Hitung total untuk pesanan ini
        $totalProduk = $pesanan->status === 'Dibatalkan' ? 0 : $produkItems->sum(function ($item) {
            return (int)$item->harga * (int)$item->qty;
        });

        $totalPerawatan = $pesanan->status === 'Dibatalkan' ? 0 : $perawatanItems->sum(function ($item) {
            return (int)$item->harga * (int)$item->qty;
        });

        $totalKeseluruhan = $totalProduk + $totalPerawatan;

        // **TAMBAHKAN KE TOTAL GRAND TOTAL**
        $this->totalGrandTotal += $totalKeseluruhan;

        // Jika tidak ada item sama sekali
        if ($maxItems === 0) {
            $rows[] = [
                $pesanan->id,
                $pesanan->created_at->format('d M Y'),
                $pesanan->pelanggan->Nama,
                $pesanan->status,
                $pesanan->Metode_Pembayaran,

                // Produk
                '-', // Nama Produk
                0,   // Qty Produk
                0,   // Harga Produk
                0,   // Subtotal Produk

                // Perawatan
                '-', // Nama Perawatan
                0,   // Qty Perawatan
                0,   // Harga Perawatan
                0,   // Subtotal Perawatan

                // Total
                $totalKeseluruhan
            ];
            $itemCount = 1;
        } else {
            // Loop untuk setiap baris (berdasarkan jumlah maksimum item)
            for ($i = 0; $i < $maxItems; $i++) {
                $produk = isset($produkItems[$i]) ? $produkItems[$i] : null;
                $perawatan = isset($perawatanItems[$i]) ? $perawatanItems[$i] : null;

                // Hitung nilai untuk produk
                $produkNama = $produk && $produk->produk ? $produk->produk->Nama : '';
                $qtyProduk = $produk ? ($pesanan->status === 'Dibatalkan' ? 0 : (int)$produk->qty) : 0;
                $hargaProduk = $produk ? ($pesanan->status === 'Dibatalkan' ? 0 : (int)$produk->harga) : 0;
                $subtotalProduk = $produk ? ($pesanan->status === 'Dibatalkan' ? 0 : $hargaProduk * $qtyProduk) : 0;

                // Hitung nilai untuk perawatan
                $perawatanNama = $perawatan && $perawatan->perawatan ? $perawatan->perawatan->Nama_Perawatan : '';
                $qtyPerawatan = $perawatan ? ($pesanan->status === 'Dibatalkan' ? 0 : (int)$perawatan->qty) : 0;
                $hargaPerawatan = $perawatan ? ($pesanan->status === 'Dibatalkan' ? 0 : (int)$perawatan->harga) : 0;
                $subtotalPerawatan = $perawatan ? ($pesanan->status === 'Dibatalkan' ? 0 : $hargaPerawatan * $qtyPerawatan) : 0;

                // Hanya di baris pertama untuk setiap pesanan yang menampilkan informasi dasar
                if ($i === 0) {
                    $rows[] = [
                        $pesanan->id,
                        $pesanan->created_at->format('d M Y'),
                        $pesanan->pelanggan->Nama,
                        $pesanan->status,
                        $pesanan->Metode_Pembayaran,

                        // Produk
                        $produkNama,
                        $qtyProduk,
                        $hargaProduk,
                        $subtotalProduk,

                        // Perawatan
                        $perawatanNama,
                        $qtyPerawatan,
                        $hargaPerawatan,
                        $subtotalPerawatan,

                        // Total (hanya di baris pertama)
                        $totalKeseluruhan
                    ];
                } else {
                    // Baris berikutnya hanya menampilkan item tambahan
                    $rows[] = [
                        '', // ID Pesanan kosong
                        '', // Tanggal kosong
                        '', // Pelanggan kosong
                        '', // Status kosong
                        '', // Metode Pembayaran kosong

                        // Produk
                        $produkNama,
                        $qtyProduk,
                        $hargaProduk,
                        $subtotalProduk,

                        // Perawatan
                        $perawatanNama,
                        $qtyPerawatan,
                        $hargaPerawatan,
                        $subtotalPerawatan,

                        // Total kosong untuk baris tambahan
                        ''
                    ];
                }
                $itemCount++;
            }
        }

        // Simpan informasi grouping untuk AfterSheet
        $this->pesananGroups[] = [
            'id' => $pesanan->id,
            'item_count' => $itemCount
        ];

        return $rows;
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER, // Qty Produk
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Harga Produk
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Subtotal Produk
            'K' => NumberFormat::FORMAT_NUMBER, // Qty Perawatan
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Harga Perawatan
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Subtotal Perawatan
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Total Keseluruhan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set column width
        $sheet->getColumnDimension('A')->setWidth(10);  // ID Pesanan
        $sheet->getColumnDimension('B')->setWidth(15); // Tanggal
        $sheet->getColumnDimension('C')->setWidth(20); // Pelanggan
        $sheet->getColumnDimension('D')->setWidth(15); // Status
        $sheet->getColumnDimension('E')->setWidth(15); // Metode

        // Produk
        $sheet->getColumnDimension('F')->setWidth(25); // Nama Produk
        $sheet->getColumnDimension('G')->setWidth(10); // Qty Produk
        $sheet->getColumnDimension('H')->setWidth(15); // Harga Produk
        $sheet->getColumnDimension('I')->setWidth(15); // Subtotal Produk

        // Perawatan
        $sheet->getColumnDimension('J')->setWidth(25); // Nama Perawatan
        $sheet->getColumnDimension('K')->setWidth(10); // Qty Perawatan
        $sheet->getColumnDimension('L')->setWidth(15); // Harga Perawatan
        $sheet->getColumnDimension('M')->setWidth(15); // Subtotal Perawatan

        $sheet->getColumnDimension('N')->setWidth(15); // Total Keseluruhan

        // Header style
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'E6E6FA']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // Format number columns sebagai number
        $sheet->getStyle('G:I')->getNumberFormat()->setFormatCode('#,##0'); // Produk
        $sheet->getStyle('K:M')->getNumberFormat()->setFormatCode('#,##0'); // Perawatan
        $sheet->getStyle('N:N')->getNumberFormat()->setFormatCode('#,##0'); // Total

        // Center alignment untuk kolom A-E
        $sheet->getStyle('A:E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Center alignment untuk kolom quantity
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K:K')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Right alignment untuk kolom harga dan subtotal
        $sheet->getStyle('H:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('L:M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('N:N')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->mergeCellsForSamePesanan($event);
                $this->addSummaryWithFormulas($event);
            },
        ];
    }

    private function mergeCellsForSamePesanan(AfterSheet $event)
    {
        $sheet = $event->sheet->getDelegate();
        $currentRow = 2; // Start from row 2 (after header)

        foreach ($this->pesananGroups as $group) {
            $itemCount = $group['item_count'];

            if ($itemCount > 1) {
                // Merge cells untuk kolom A-E (ID Pesanan sampai Metode Pembayaran) dan kolom N (Total)
                $columnsToMerge = ['A', 'B', 'C', 'D', 'E', 'N'];

                foreach ($columnsToMerge as $col) {
                    $startCell = $col . $currentRow;
                    $endCell = $col . ($currentRow + $itemCount - 1);
                    $sheet->mergeCells($startCell . ':' . $endCell);

                    // Center alignment untuk merged cells
                    $sheet->getStyle($startCell . ':' . $endCell)->getAlignment()->setVertical(
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    );

                    // Untuk kolom N (Total), set alignment ke right
                    if ($col === 'N') {
                        $sheet->getStyle($startCell . ':' . $endCell)->getAlignment()->setHorizontal(
                            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
                        );
                    } else {
                        $sheet->getStyle($startCell . ':' . $endCell)->getAlignment()->setHorizontal(
                            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                        );
                    }
                }
            }

            $currentRow += $itemCount;
        }

        // **SIMPAN BARIS TERAKHIR DATA**
        $this->lastDataRow = $currentRow - 1;

        // Add borders untuk seluruh data
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        if ($lastRow > 1) {
            $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);
        }
    }

    // **METHOD YANG DISEDERHANAKAN: HANYA SUBTOTAL PRODUK & PERAWATAN**
    private function addSummaryWithFormulas(AfterSheet $event)
    {
        $sheet = $event->sheet->getDelegate();

        // Baris untuk summary (1 baris setelah data terakhir)
        $summaryRow = $this->lastDataRow + 1;

        // **HEADER SUMMARY**
        $sheet->setCellValue('A' . $summaryRow, 'TOTAL KESELURUHAN:');
        $sheet->mergeCells('A' . $summaryRow . ':F' . $summaryRow);

        // **HANYA SUBTOTAL PRODUK & PERAWATAN - RUMUS SUM**
        $sheet->setCellValue('I' . $summaryRow, '=SUM(I2:I' . $this->lastDataRow . ')'); // Total Subtotal Produk
        $sheet->setCellValue('M' . $summaryRow, '=SUM(M2:M' . $this->lastDataRow . ')'); // Total Subtotal Perawatan

        // **TOTAL KESELURUHAN - RUMUS SUM**
        $sheet->setCellValue('N' . $summaryRow, '=SUM(N2:N' . $this->lastDataRow . ')'); // Total Keseluruhan

        // Style untuk summary
        $sheet->getStyle('A' . $summaryRow . ':N' . $summaryRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '2E86C1']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '1B4F72'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // Alignment khusus untuk kolom numerik
        $sheet->getStyle('I' . $summaryRow . ':N' . $summaryRow)->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
        );

        // // **RINGKASAN DETAIL (2 baris setelah summary)**
        // $detailRow = $summaryRow + 2;

        // $sheet->setCellValue('A' . $detailRow, 'RINGKASAN DETAIL:');
        // $sheet->getStyle('A' . $detailRow)->getFont()->setBold(true);

        // // **PRODUK**
        // $sheet->setCellValue('A' . ($detailRow + 1), 'PRODUK:');
        // $sheet->setCellValue('B' . ($detailRow + 1), 'Total Subtotal:');
        // $sheet->setCellValue('C' . ($detailRow + 1), '=I' . $summaryRow); // Referensi ke total subtotal produk

        // // **PERAWATAN**
        // $sheet->setCellValue('E' . ($detailRow + 1), 'PERAWATAN:');
        // $sheet->setCellValue('F' . ($detailRow + 1), 'Total Subtotal:');
        // $sheet->setCellValue('G' . ($detailRow + 1), '=M' . $summaryRow); // Referensi ke total subtotal perawatan

        // // **TOTAL KESELURUHAN**
        // $sheet->setCellValue('I' . ($detailRow + 1), 'TOTAL KESELURUHAN:');
        // $sheet->setCellValue('J' . ($detailRow + 1), 'Grand Total:');
        // $sheet->setCellValue('K' . ($detailRow + 1), '=N' . $summaryRow); // Referensi ke total keseluruhan

        // // **VALIDASI: TOTAL PRODUK + TOTAL PERAWATAN = GRAND TOTAL**
        // $sheet->setCellValue('A' . ($detailRow + 3), 'VALIDASI:');
        // $sheet->setCellValue('B' . ($detailRow + 3), 'Total Produk + Total Perawatan = Grand Total');
        // $sheet->setCellValue('C' . ($detailRow + 3), '=IF((I' . $summaryRow . '+M' . $summaryRow . ')=N' . $summaryRow . ', "✓ BENAR", "✗ SALAH")');

        // // Style untuk ringkasan detail
        // $detailRange = 'A' . $detailRow . ':C' . ($detailRow + 3);
        // $sheet->getStyle($detailRange)->applyFromArray([
        //     'font' => [
        //         'bold' => false,
        //     ],
        //     'borders' => [
        //         'outline' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['rgb' => '7FB3D5'],
        //         ],
        //     ],
        //     'fill' => [
        //         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        //         'color' => ['rgb' => 'EBF5FB'],
        //     ],
        // ]);

        // // Style untuk header ringkasan
        // $sheet->getStyle('A' . $detailRow)->getFont()->setBold(true);
        // $sheet->getStyle('A' . ($detailRow + 1))->getFont()->setBold(true);
        // $sheet->getStyle('E' . ($detailRow + 1))->getFont()->setBold(true);
        // $sheet->getStyle('I' . ($detailRow + 1))->getFont()->setBold(true);
        // $sheet->getStyle('A' . ($detailRow + 3))->getFont()->setBold(true);

        // // **CATATAN UNTUK KASIR (4 baris setelah ringkasan detail)**
        // $noteRow = $detailRow + 5;
        // $sheet->setCellValue('A' . $noteRow, 'CATATAN UNTUK KASIR:');
        // $sheet->mergeCells('A' . $noteRow . ':N' . $noteRow);

        // $sheet->setCellValue('A' . ($noteRow + 1), '✅ Rumus SUM sudah otomatis terpasang untuk:');
        // $sheet->mergeCells('A' . ($noteRow + 1) . ':N' . ($noteRow + 1));

        // $sheet->setCellValue('A' . ($noteRow + 2), '• Kolom I  = Total Subtotal Produk: =SUM(I2:I' . $this->lastDataRow . ')');
        // $sheet->mergeCells('A' . ($noteRow + 2) . ':N' . ($noteRow + 2));

        // $sheet->setCellValue('A' . ($noteRow + 3), '• Kolom M = Total Subtotal Perawatan: =SUM(M2:M' . $this->lastDataRow . ')');
        // $sheet->mergeCells('A' . ($noteRow + 3) . ':N' . ($noteRow + 3));

        // $sheet->setCellValue('A' . ($noteRow + 4), '• Kolom N = Grand Total: =SUM(N2:N' . $this->lastDataRow . ')');
        // $sheet->mergeCells('A' . ($noteRow + 4) . ':N' . ($noteRow + 4));

        // $sheet->setCellValue('A' . ($noteRow + 5), '📊 Data akan otomatis ter-update jika ada perubahan');
        // $sheet->mergeCells('A' . ($noteRow + 5) . ':N' . ($noteRow + 5));

        // $sheet->setCellValue('A' . ($noteRow + 6), '💡 Validasi: Total Produk + Total Perawatan harus sama dengan Grand Total');
        // $sheet->mergeCells('A' . ($noteRow + 6) . ':N' . ($noteRow + 6));

        // // Style untuk catatan
        // $noteRange = 'A' . $noteRow . ':N' . ($noteRow + 6);
        // $sheet->getStyle($noteRange)->applyFromArray([
        //     'font' => [
        //         'italic' => true,
        //         'color' => ['rgb' => '7D6608']
        //     ],
        //     'fill' => [
        //         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        //         'color' => ['rgb' => 'FCF3CF']
        //     ],
        // ]);

        // $sheet->getStyle('A' . $noteRow)->getFont()->setBold(true);
    }
}
