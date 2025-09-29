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

                            // **OTOMATIS SET TANGGAL BERDASARKAN created_at PRODUK**
                            $set('tanggal', $produk->created_at->format('Y-m-d'));
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
                ->maxLength(255)
                ->default(function (Forms\Get $get) {
                    $produkId = $get('produk_id');
                    if ($produkId) {
                        $produk = \App\Models\Produk::find($produkId);
                        return $produk ? "Stok awal - {$produk->Nama}" : 'Penyesuaian stok manual';
                    }
                    return 'Penyesuaian stok manual';
                }),

            Forms\Components\DatePicker::make('tanggal')
                ->required()
                ->default(function (Forms\Get $get) {
                    // **Default tanggal berdasarkan created_at produk**
                    $produkId = $get('produk_id');
                    if ($produkId) {
                        $produk = \App\Models\Produk::find($produkId);
                        return $produk ? $produk->created_at : now();
                    }
                    return now();
                }),

            Forms\Components\Placeholder::make('info')
                ->content(function (Forms\Get $get) {
                    $produkId = $get('produk_id');
                    $tipe = $get('tipe');
                    $jumlah = $get('jumlah') ?? 0;
                    $stokSekarang = $get('stok_sekarang') ?? 0;
                    $tanggal = $get('tanggal');

                    if (!$produkId) return 'Pilih produk terlebih dahulu';

                    $produk = \App\Models\Produk::find($produkId);
                    if (!$produk) return 'Produk tidak ditemukan';

                    $info = "Tanggal movement: " . (\Carbon\Carbon::parse($tanggal)->format('d M Y')) . "\n";
                    $info .= "Created at produk: " . $produk->created_at->format('d M Y') . "\n";

                    if ($tipe && $jumlah > 0) {
                        if ($tipe === 'masuk') {
                            $stokBaru = $stokSekarang + $jumlah;
                            $info .= "Stok {$produk->Nama} akan bertambah dari {$stokSekarang} menjadi {$stokBaru}";
                        } else {
                            if ($jumlah > $stokSekarang) {
                                $info .= "❌ Stok tidak cukup! Stok tersedia: {$stokSekarang}, diperlukan: {$jumlah}";
                            } else {
                                $stokBaru = $stokSekarang - $jumlah;
                                $info .= "Stok {$produk->Nama} akan berkurang dari {$stokSekarang} menjadi {$stokBaru}";
                            }
                        }
                    } else {
                        $info .= "Stok saat ini: {$stokSekarang}";
                    }

                    return $info;
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
                Tables\Filters\Filter::make('stok_awal')
                    ->label('Tampilkan Stok Awal')
                    ->query(fn (Builder $query): Builder => $query->where('keterangan', 'like', '%stok awal%')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (StokMovement $record) {
                        // Handle penghapusan stok movement
                        $produk = $record->produk;
                        if ($record->tipe === 'masuk') {
                            $produk->decrement('Stok', $record->jumlah);
                        } else {
                            $produk->increment('Stok', $record->jumlah);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                $produk = $record->produk;
                                if ($record->tipe === 'masuk') {
                                    $produk->decrement('Stok', $record->jumlah);
                                } else {
                                    $produk->increment('Stok', $record->jumlah);
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStokMovements::route('/'),
            'create' => Pages\CreateStokMovement::route('/create'),
            'edit' => Pages\EditStokMovement::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
    return auth()->user()->hasRole('kasir') || auth()->user()->hasAnyRole(['admin', 'manajer']); // abaikan error ini, cuman error di intellisense
}

}
