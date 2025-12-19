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
use Illuminate\Support\HtmlString;
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
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make("Nomor_SKU")
                        ->label('SKU')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->columnSpan(1),

                    Forms\Components\Toggle::make('is_bundling')
                        ->label('Produk Bundling?')
                        ->reactive()
                        ->inline(false)
                        ->columnSpan(1),
                ]),

            Forms\Components\TextInput::make("Nama")
                ->label('Nama Produk')
                ->required()
                ->maxLength(255),

            // **HARGA BERDASARKAN JENIS PRODUK**
            Forms\Components\Group::make()
                ->schema(function ($get) {
                    $isBundling = $get('is_bundling');

                    if ($isBundling) {
                        return [
                            Forms\Components\TextInput::make("harga_bundling")
                                ->label('Harga Bundling')
                                ->required()
                                ->numeric()
                                ->prefix('Rp')
                                ->minValue(0)
                                ->maxValue(999999999999)
                                ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : '')
                                ->dehydrateStateUsing(function ($state) {
                                    if (is_string($state) && str_contains($state, '.')) {
                                        return (int) str_replace('.', '', $state);
                                    }
                                    return (int) $state;
                                })
                                ->helperText('Harga jual untuk produk bundling')
                                ->placeholder('0'),

                            Forms\Components\Placeholder::make('info_bundling')
                                ->content('Harga normal akan digunakan untuk perhitungan komponen')
                                ->helperText('Harga di atas adalah harga jual bundling ke customer')
                        ];
                    } else {
                        return [
                            Forms\Components\TextInput::make("Harga")
                                ->label('Harga')
                                ->required()
                                ->numeric()
                                ->prefix('Rp')
                                ->minValue(0)
                                ->maxValue(999999999999)
                                ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : '')
                                ->dehydrateStateUsing(function ($state) {
                                    if (is_string($state) && str_contains($state, '.')) {
                                        return (int) str_replace('.', '', $state);
                                    }
                                    return (int) $state;
                                })
                                ->helperText('Masukkan angka tanpa titik (contoh: 1000000)')
                                ->placeholder('0'),
                        ];
                    }
                }),

            // **STOK - BERBEDA UNTUK BUNDLING**
            Forms\Components\Group::make()
                ->schema(function ($get, string $operation) {
                    $isBundling = $get('is_bundling');

                    if ($isBundling) {
                        // Untuk bundling: tidak perlu input stok
                        return [
                            Forms\Components\Placeholder::make('stok_info_bundling')
                                ->content('Stok bundling dikelola otomatis berdasarkan stok komponen')
                                ->helperText('Stok akan dicek berdasarkan produk yang tersedia dalam bundling'),

                            Forms\Components\Hidden::make('Stok')
                                ->default(0),
                        ];
                    } else {
                        // Untuk produk biasa
                        return [
                            Forms\Components\TextInput::make("Stok")
                                ->label('Stok')
                                ->numeric()
                                ->required()
                                ->minValue(0)
                                ->default(0)
                                ->readOnly(fn (string $operation): bool => $operation === 'edit')
                                ->helperText(function (string $operation) use ($isBundling): string {
                                    if ($isBundling) {
                                        return 'Stok bundling dikelola otomatis';
                                    }
                                    return $operation === 'edit'
                                        ? 'Stok tidak dapat diubah secara manual. Gunakan menu Riwayat Stok untuk penyesuaian.'
                                        : 'Stok awal produk';
                                })
                                ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        ];
                    }
                }),

            // **KOMPONEN BUNDLING - HANYA UNTUK PRODUK BUNDLING**
            Forms\Components\Section::make('Komponen Bundling')
                ->schema([
                    Forms\Components\Repeater::make('produkBundlingItems')
                        ->label('')
                        ->relationship('produkBundlingItems')
                        ->schema([
                            Forms\Components\Select::make('produk_id')
                                ->label('Pilih Produk')
                                ->options(Produk::where('is_bundling', false)->pluck('Nama', 'id'))
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    if ($state) {
                                        $produk = Produk::find($state);
                                        if ($produk) {
                                            $set('harga_satuan', $produk->Harga);
                                            $set('keterangan', $produk->Nama);
                                        }
                                    } else {
                                        $set('harga_satuan', 0);
                                        $set('keterangan', null);
                                    }
                                }),

                            Forms\Components\TextInput::make('qty')
                                ->label('Jumlah')
                                ->numeric()
                                ->minValue(1)
                                ->default(1)
                                ->required(),

                            Forms\Components\TextInput::make('harga_satuan')
                                ->label('Harga Satuan')
                                ->numeric()
                                ->prefix('Rp')
                                ->readOnly()
                                ->dehydrated(false)
                                ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state),

                            Forms\Components\TextInput::make('keterangan')
                                ->label('Keterangan')
                                ->maxLength(500)
                                ->placeholder('Keterangan penggunaan produk dalam bundling'),
                        ])
                        ->columns(4)
                        ->addActionLabel('Tambah Produk ke Bundling')
                        ->defaultItems(0)
                        ->reorderable(false)
                        ->visible(fn ($get) => $get('is_bundling')), // Hanya tampil untuk bundling
                ])
                ->collapsible()
                ->visible(fn ($get) => $get('is_bundling')), // Hanya tampil untuk bundling
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

            Tables\Columns\IconColumn::make('is_bundling')
                ->label('Bundling')
                ->boolean()
                ->sortable(),

            Tables\Columns\TextColumn::make("Harga")
                ->label('Harga')
                ->formatStateUsing(fn ($state, $record) => 'Rp ' . number_format($record->is_bundling ? $record->harga_bundling : $state, 0, ',', '.'))
                ->sortable(),

            Tables\Columns\TextColumn::make("Stok")
                ->label('Stok')
                ->formatStateUsing(fn ($state, $record) => $record->is_bundling ? 'Auto' : $state)
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('is_bundling')
                ->label('Jenis Produk')
                ->options([
                    '0' => 'Produk Biasa',
                    '1' => 'Produk Bundling',
                ]),
        ])
        // ->headerActions([
                // ExportAction::make()->exports([
                //     ExcelExport::make()->fromTable()
                // ])->label('Download')
            // ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\Action::make('lihat_komponen')
                ->label('Komponen')
                ->icon('heroicon-o-eye')
                ->visible(fn ($record) => $record->is_bundling)
                ->modalHeading('Komponen Bundling')
                ->modalContent(function (Produk $record) {
                    $html = '<div class="space-y-3">';

                    if ($record->produkBundlingItems->count() > 0) {
                        $html .= '<div class="text-sm text-gray-600 mb-4">';
                        $html .= '<div>Harga Bundling: <strong>Rp ' . number_format($record->harga_bundling, 0, ',', '.') . '</strong></div>';
                        $html .= '<div>Total Harga Komponen: <strong>Rp ' . number_format($record->total_harga_komponen, 0, ',', '.') . '</strong></div>';
                        $html .= '<div>Selisih: <strong>Rp ' . number_format($record->selisih_harga, 0, ',', '.') . '</strong></div>';
                        $html .= '</div>';

                        foreach ($record->produkBundlingItems as $item) {
                            $subtotal = $item->harga_satuan * $item->qty;
                            $html .= '
                                <div class="border rounded-lg p-3">
                                    <div class="font-semibold">' . $item->nama_produk . '</div>
                                    <div class="grid grid-cols-3 gap-2 text-sm text-gray-600 mt-1">
                                        <div>Jumlah: ' . $item->qty . ' unit</div>
                                        <div>Harga: Rp ' . number_format($item->harga_satuan, 0, ',', '.') . '</div>
                                        <div>Subtotal: Rp ' . number_format($subtotal, 0, ',', '.') . '</div>
                                        <div class="col-span-3">Keterangan: ' . ($item->keterangan ?? '-') . '</div>
                                        <div class="col-span-3">Stok Tersedia: ' . $item->stok_produk . ' unit</div>
                                    </div>
                                </div>
                            ';
                        }
                    } else {
                        $html .= '<p class="text-gray-500">Tidak ada komponen dalam bundling ini.</p>';
                    }

                    $html .= '</div>';
                    return new HtmlString($html);
                })
                ->modalSubmitAction(false),
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
