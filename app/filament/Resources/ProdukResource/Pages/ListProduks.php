<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;

class ListProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            // Action untuk tambah stok manual
            Actions\Action::make('tambahStokManual')
                ->label('Tambah Stok Manual')
                ->icon('heroicon-o-plus')
                ->form([
                    Forms\Components\Select::make('produk_id')
                        ->label('Produk')
                        ->options(\App\Models\Produk::all()->pluck('Nama', 'id'))
                        ->required()
                        ->searchable(),

                    Forms\Components\TextInput::make('jumlah')
                        ->label('Jumlah')
                        ->numeric()
                        ->required()
                        ->minValue(1),

                    Forms\Components\TextInput::make('keterangan')
                        ->label('Keterangan')
                        ->required()
                        ->default('Penambahan stok manual'),
                ])
                ->action(function (array $data) {
                    $produk = \App\Models\Produk::find($data['produk_id']);

                    if ($produk) {
                        $produk->tambahStok($data['jumlah'], $data['keterangan']);

                        \Filament\Notifications\Notification::make()
                            ->title('Stok Berhasil Ditambah')
                            ->body("Stok {$produk->Nama} bertambah {$data['jumlah']} unit")
                            ->success()
                            ->send();
                    }
                }),
        ];
    }
}
