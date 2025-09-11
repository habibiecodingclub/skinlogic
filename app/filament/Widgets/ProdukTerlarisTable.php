<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use App\Models\PesananProduk;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

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
                    ->select('produks.*')
                    ->addSelect([
                        'total_terjual' => PesananProduk::query()
                            ->selectRaw('COALESCE(SUM(qty), 0)')
                            ->whereColumn('pesanan_produk.produk_id', 'produks.id'),
                        'total_pendapatan' => PesananProduk::query()
                            ->selectRaw('COALESCE(SUM(qty * harga), 0)')
                            ->whereColumn('pesanan_produk.produk_id', 'produks.id')
                    ])
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

                Tables\Columns\TextColumn::make('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('Stok')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

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
                Tables\Actions\Action::make('view')
                    ->url(fn (Produk $record): string => route('filament.admin.resources.produks.edit', $record))
                    ->icon('heroicon-o-eye'),
            ])
            ->paginated([10, 25, 50, 100]);
    }
}
