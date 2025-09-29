<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Filament\Resources\ProdukResource\RelationManagers\StokMovementsRelationManager;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = "Produk";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("Nomor_SKU")
                    ->label('SKU')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make("Nama")
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),

Forms\Components\TextInput::make("Harga")
    ->label('Harga')
    ->required()
    ->numeric()
    ->prefix('Rp')
    ->minValue(0)
    ->maxValue(999999999999)
    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : '')
    ->dehydrateStateUsing(function ($state) {
        // Hanya convert jika state adalah string dengan format
        if (is_string($state) && str_contains($state, '.')) {
            return (int) str_replace('.', '', $state);
        }
        return (int) $state;
    })
    ->helperText('Masukkan angka tanpa titik (contoh: 1000000)')
    ->placeholder('0'),


                Forms\Components\TextInput::make("Stok")
                    ->label('Stok')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("Nomor_SKU")
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make("Nama")
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make("Harga")
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make("Stok")
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                // Filters could be added later
            ])
            ->headerActions([
                // ExportAction::make()->exports([
                //     ExcelExport::make()->fromTable()
                // ])->label('Download Excel')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StokMovementsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
