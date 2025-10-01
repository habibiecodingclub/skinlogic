<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use App\Models\PesananProduk;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;

class ProdukTerlarisTable extends BaseWidget
{
    protected static ?string $heading = 'Daftar Produk Terlaris';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = '1';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Produk::query()
                    ->whereHas('pesanan')
                    ->select('produks.*')
                    ->addSelect([
                        'total_terjual' => PesananProduk::query()
                            ->selectRaw('COALESCE(SUM(pesanan_produk.qty), 0)')
                            ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
                            ->whereColumn('pesanan_produk.produk_id', 'produks.id')
                            ->where('pesanans.status', 'Berhasil'),
                        'total_pendapatan' => PesananProduk::query()
                            ->selectRaw('COALESCE(SUM(pesanan_produk.qty * pesanan_produk.harga), 0)')
                            ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
                            ->whereColumn('pesanan_produk.produk_id', 'produks.id')
                            ->where('pesanans.status', 'Berhasil')
                    ])
                    ->havingRaw('total_terjual > 0')
                    ->orderByDesc('total_terjual')
            )
            ->columns([
                Tables\Columns\TextColumn::make('Nomor_SKU')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('Nama')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_bundling')
                    ->label('Bundling')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

                // **PERBAIKAN: TAMPILKAN HARGA YANG BENAR BERDASARKAN JENIS PRODUK**
                Tables\Columns\TextColumn::make('harga_jual')
                    ->label('Harga Jual')
                    ->getStateUsing(function (Produk $record) {
                        // Untuk bundling, gunakan harga_bundling, untuk biasa gunakan Harga
                        return $record->is_bundling ? $record->harga_bundling : $record->Harga;
                    })
                    ->money('IDR')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('Stok')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->getStateUsing(function (Produk $record) {
                        // Untuk bundling, tampilkan "Auto" karena stok dikelola otomatis
                        return $record->is_bundling ? 'Auto' : $record->Stok;
                    }),

                Tables\Columns\TextColumn::make('total_terjual')
                    ->label('Terjual')
                    ->sortable()
                    ->alignCenter()
                    ->numeric(),

                Tables\Columns\TextColumn::make('total_pendapatan')
                    ->label('Total Pendapatan')
                    ->sortable()
                    ->money('IDR')
                    ->alignRight(),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat Histori')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (Produk $record) => "Histori Penjualan - {$record->Nama}")
                    ->modalContent(fn (Produk $record): View => view(
                        'filament.widgets.produk-histori',
                        ['produk' => $record]
                    ))
                    ->modalCancelActionLabel('Tutup')
                    ->modalSubmitAction(false)
                    ->modalWidth('7xl'),
            ])
            ->emptyStateHeading("Belum ada produk yang terjual")
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->paginated([10, 25, 50, 100]);
    }
}
