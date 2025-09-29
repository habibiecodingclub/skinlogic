<?php

namespace App\Filament\Widgets;

use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\PesananProduk;
use App\Models\PesananPerawatan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;

class PelangganLoyalTable extends BaseWidget
{
    protected static ?string $heading = 'Daftar Pelanggan Paling Loyal';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = '1';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pelanggan::query()
                    ->select('pelanggans.*')
                    ->addSelect([
                        'total_pesanan' => Pesanan::query()
                            ->selectRaw('COUNT(*)')
                            ->whereColumn('pesanans.pelanggan_id', 'pelanggans.id')
                            ->where('pesanans.status', 'Berhasil'),
                        'total_pembelian' => PesananProduk::query()
                            ->selectRaw('COALESCE(SUM(pesanan_produk.qty * pesanan_produk.harga), 0)')
                            ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
                            ->whereColumn('pesanans.pelanggan_id', 'pelanggans.id')
                            ->where('pesanans.status', 'Berhasil'),
                        'total_perawatan' => PesananPerawatan::query()
                            ->selectRaw('COALESCE(SUM(pesanan_perawatan.qty * pesanan_perawatan.harga), 0)')
                            ->join('pesanans', 'pesanan_perawatan.pesanan_id', '=', 'pesanans.id')
                            ->whereColumn('pesanans.pelanggan_id', 'pelanggans.id')
                            ->where('pesanans.status', 'Berhasil'),
                        'terakhir_pesan' => Pesanan::query()
                            ->select('created_at')
                            ->whereColumn('pesanans.pelanggan_id', 'pelanggans.id')
                            ->where('pesanans.status', 'Berhasil')
                            ->orderByDesc('created_at')
                            ->limit(1)
                    ])
                    ->havingRaw('total_pesanan > 0')
                    ->orderByDesc('total_pesanan')
            )
            ->columns([
                Tables\Columns\TextColumn::make('Nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('Status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Member' => 'success',
                        'Non Member' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total_pesanan')
                    ->label('Total Pesanan')
                    ->sortable()
                    ->alignCenter()
                    ->numeric(),

                Tables\Columns\TextColumn::make('total_pembelian')
                    ->label('Total Pembelian Produk')
                    ->sortable()
                    ->money('IDR')
                    ->alignRight()
                    ->getStateUsing(function ($record) {
                        return $record->total_pembelian;
                    }),

                Tables\Columns\TextColumn::make('total_perawatan')
                    ->label('Total Pembelian Perawatan')
                    ->sortable()
                    ->money('IDR')
                    ->alignRight()
                    ->getStateUsing(function ($record) {
                        return $record->total_perawatan;
                    }),

                Tables\Columns\TextColumn::make('total_semua')
                    ->label('Total Semua Pembelian')
                    ->sortable()
                    ->money('IDR')
                    ->alignRight()
                    ->color('success')
                    ->weight('bold')
                    ->getStateUsing(function ($record) {
                        return ($record->total_pembelian ?? 0) + ($record->total_perawatan ?? 0);
                    }),

                Tables\Columns\TextColumn::make('terakhir_pesan')
                    ->label('Terakhir Pesan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat Histori')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (Pelanggan $record) => "Histori Pembelian - {$record->Nama}")
                    ->modalContent(fn (Pelanggan $record): View => view(
                        'filament.widgets.pelanggan-histori',
                        ['pelanggan' => $record]
                    ))
                    ->modalCancelActionLabel('Tutup')
                    ->modalSubmitAction(false)
                    ->modalWidth('7xl'),
            ])
            ->emptyStateHeading("Belum ada pelanggan yang transaksi")
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->paginated([10, 25, 50, 100]);
    }
}
