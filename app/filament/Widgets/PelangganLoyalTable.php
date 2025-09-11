<?php

namespace App\Filament\Widgets;

use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\PesananProduk;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

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
                        ->whereColumn('pesanans.pelanggan_id', 'pelanggans.id'),
                    'total_pembelian' => PesananProduk::query()
                        ->selectRaw('COALESCE(SUM(pesanan_produk.qty * pesanan_produk.harga), 0)')
                        ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
                        ->whereColumn('pesanans.pelanggan_id', 'pelanggans.id')
                ])
                ->orderByDesc('total_pesanan')
        )
            ->columns([
                Tables\Columns\TextColumn::make('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Member' => 'success',
                        'Non Member' => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total_pesanan')
                    ->label('Total Pesanan')
                    ->sortable()
                    ->alignCenter()
                    ->numeric(),

                Tables\Columns\TextColumn::make('total_pembelian')
                    ->label('Total Pembelian')
                    ->sortable()
                    ->money('IDR')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('pesanans.created_at')
                    ->label('Terakhir Pesan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Pelanggan $record): string => route('filament.admin.resources.pelanggans.edit', $record))
                    ->icon('heroicon-o-eye'),
            ])
            ->paginated([10, 25, 50, 100]);
    }
}
