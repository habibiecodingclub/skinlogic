<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok - {{ $periode['nama_bulan'] }} {{ $periode['tahun'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STOK BULANAN</h1>
        <p>Klinik Kecantikan</p>
        <p>Periode: {{ $periode['nama_bulan'] }} {{ $periode['tahun'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Stok Awal</th>
                <th>Stok Masuk</th>
                <th>Stok Keluar</th>
                <th>Stok Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produks as $produk)
            <tr>
                <td>{{ $produk->Nama }}</td>
                <td class="text-right">{{ $produk->getStokAwalBulan($periode['tahun'], $periode['bulan']) }}</td>
                <td class="text-right">{{ $produk->stokMovements->where('tipe', 'masuk')->sum('jumlah') }}</td>
                <td class="text-right">{{ $produk->stokMovements->where('tipe', 'keluar')->sum('jumlah') }}</td>
                <td class="text-right">{{ $produk->getStokAkhirBulan($periode['tahun'], $periode['bulan']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
    </div>
</body>
</html>
