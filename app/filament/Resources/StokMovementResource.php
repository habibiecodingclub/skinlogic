<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokMovementResource\Pages;
use App\Models\StokMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StokMovementResource extends Resource
{
    protected static ?string $model = StokMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Riwayat Stok';

    protected static ?string $pluralModelLabel = 'Riwayat Stok';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produk_id')
                    ->relationship('produk', 'Nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $produk = \App\Models\Produk::find($state);
                            if ($produk) {
                                $set('stok_sekarang', $produk->Stok);
                            }
                        }
                    }),

                Forms\Components\Select::make('tipe')
                    ->options([
                        'masuk' => 'Stok Masuk',
                        'keluar' => 'Stok Keluar',
                    ])
                    ->required()
                    ->live(),

                Forms\Components\TextInput::make('jumlah')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->live(),

                Forms\Components\TextInput::make('stok_sekarang')
                    ->label('Stok Sekarang')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(false),

                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),

                Forms\Components\Placeholder::make('info')
                    ->content(function (Forms\Get $get) {
                        $produkId = $get('produk_id');
                        $tipe = $get('tipe');
                        $jumlah = $get('jumlah') ?? 0;
                        $stokSekarang = $get('stok_sekarang') ?? 0;

                        if (!$produkId) return 'Pilih produk terlebih dahulu';

                        $produk = \App\Models\Produk::find($produkId);
                        if (!$produk) return 'Produk tidak ditemukan';

                        if ($tipe && $jumlah > 0) {
                            if ($tipe === 'masuk') {
                                $stokBaru = $stokSekarang + $jumlah;
                                return "Stok {$produk->Nama} akan bertambah dari {$stokSekarang} menjadi {$stokBaru}";
                            } else {
                                if ($jumlah > $stokSekarang) {
                                    return "❌ Stok tidak cukup! Stok tersedia: {$stokSekarang}, diperlukan: {$jumlah}";
                                }
                                $stokBaru = $stokSekarang - $jumlah;
                                return "Stok {$produk->Nama} akan berkurang dari {$stokSekarang} menjadi {$stokBaru}";
                            }
                        }

                        return "Stok saat ini: {$stokSekarang}";
                    })
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produk.Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'masuk' => 'Stok Masuk',
                        'keluar' => 'Stok Keluar',
                    ]),
                Tables\Filters\SelectFilter::make('produk')
                    ->relationship('produk', 'Nama')
                    ->searchable()
                    ->preload(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStokMovements::route('/'),
            'create' => Pages\CreateStokMovement::route('/create'),
            'edit' => Pages\EditStokMovement::route('/{record}/edit'),
        ];
    }
}
