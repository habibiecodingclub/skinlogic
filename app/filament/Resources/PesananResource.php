<?php

namespace App\Filament\Resources;

use App\Exports\PesananExport;
use App\Filament\Resources\PesananResource\Pages;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Perawatan;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'Pesanan';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Select::make('pelanggan_id')
                ->relationship('pelanggan', 'Nama')
                ->label('Pelanggan')
                ->required(),
            Select::make("Metode_Pembayaran")
                ->options([
                    "Cash" => "Cash",
                    "QRIS" => "QRIS",
                    "Debit" => "Debit"
                ]),

            // Repeater untuk Produk
            Repeater::make('detailProduk')
                ->label('Produk')
                ->relationship()
                ->schema([
                    Select::make('produk_id')
                        ->label('Produk')
                        ->options(Produk::query()->pluck('Nama', 'id'))
                        ->searchable()
                        ->required()
                        ->live()
                        ->preload()
                        ->afterStateUpdated(function ($state, Set $set, $get) {
                            $product = Produk::find($state);
                            $harga = $product?->Harga ?? 0;
                            $set('harga', $harga);
                            // Hapus set subtotal karena tidak disimpan di database
                        }),
                    TextInput::make('qty')
                        ->label('Jumlah')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, $get) {
                            $hargaSatuan = $get('harga') ?? 0;
                            // Hapus set subtotal karena tidak disimpan di database
                        })
                        ->required(),
                    TextInput::make('harga')
                        ->label("Harga Satuan")
                        ->numeric()
                        ->prefix("Rp. ")
                        ->readOnly(),
                    // HAPUS field subtotal karena tidak ada di database
                    // TextInput::make('subtotal')
                    //     ->label("Subtotal")
                    //     ->numeric()
                    //     ->prefix("Rp. ")
                    //     ->readOnly(),
                ])
                ->addActionLabel('Tambah Produk')
                ->columnSpanFull(),

            // Repeater untuk Perawatan
            Repeater::make('detailPerawatan')
                ->label('Perawatan')
                ->relationship()
                ->schema([
                    Select::make('perawatan_id')
                        ->label('Perawatan')
                        ->options(Perawatan::query()->pluck('Nama_Perawatan', 'id'))
                        ->searchable()
                        ->required()
                        ->live()
                        ->preload()
                        ->afterStateUpdated(function ($state, Set $set, $get) {
                            $service = Perawatan::find($state);
                            $harga = $service?->Harga ?? 0;
                            $set('harga', $harga);
                            // Hapus set subtotal
                        }),
                    TextInput::make('qty')
                        ->label('Jumlah')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, $get) {
                            $hargaSatuan = $get('harga') ?? 0;
                            // Hapus set subtotal
                        })
                        ->required(),
                    TextInput::make('harga')
                        ->label("Harga Satuan")
                        ->numeric()
                        ->prefix("Rp. ")
                        ->readOnly(),
                    // HAPUS field subtotal
                    // TextInput::make('subtotal')
                    //     ->label("Subtotal")
                    //     ->numeric()
                    //     ->prefix("Rp. ")
                    //     ->readOnly(),
                ])
                ->addActionLabel('Tambah Perawatan')
                ->columnSpanFull(),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pelanggan.Nama')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make("Total Pembayaran")
                    ->state(function ($record) {
                        $totalProduk = $record->detailProduk->sum(function($item) {
                            return $item->harga * $item->qty;
                        });

                        $totalPerawatan = $record->detailPerawatan->sum(function($item) {
                            return $item->harga * $item->qty;
                        });

                        $total = $totalProduk + $totalPerawatan;
                        return "Rp. " . number_format($total, 0, ",", ".");
                    })
                    ->sortable(),

                TextColumn::make("Metode_Pembayaran")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Cash' => 'success',
                        'QRIS' => 'primary',
                        'Debit' => 'warning',
                    }),

                TextColumn::make("created_at")
                    ->label("Tanggal Pesanan")
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\ExportAction::make()->exports([
                //     new PesananExport()
                // ])->label('Download')
            ])
            ->actions([
                // Tombol View Detail
                Action::make('view')
                    ->label('View Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Pesanan')
                    ->modalContent(function (Pesanan $record) {
                        $totalProduk = $record->detailProduk->sum(function($item) {
                            return $item->harga * $item->qty;
                        });

                        $totalPerawatan = $record->detailPerawatan->sum(function($item) {
                            return $item->harga * $item->qty;
                        });

                        $total = $totalProduk + $totalPerawatan;

                        return new HtmlString('
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="font-semibold">Informasi Pelanggan</h3>
                                        <p>Nama: ' . $record->pelanggan->Nama . '</p>
                                        <p>Email: ' . $record->pelanggan->Email . '</p>
                                        <p>Telepon: ' . $record->pelanggan->Nomor_Telepon . '</p>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Informasi Pesanan</h3>
                                        <p>Tanggal: ' . $record->created_at->format('d M Y H:i') . '</p>
                                        <p>Metode Pembayaran: ' . $record->Metode_Pembayaran . '</p>
                                    </div>
                                </div>

                                ' . ($record->detailProduk->count() > 0 ? '
                                <div>
                                    <h3 class="font-semibold">Produk</h3>
                                    <table class="w-full border-collapse border border-gray-300">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-300 p-2 text-left">Nama Produk</th>
                                                <th class="border border-gray-300 p-2 text-center">Qty</th>
                                                <th class="border border-gray-300 p-2 text-right">Harga Satuan</th>
                                                <th class="border border-gray-300 p-2 text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ' . $record->detailProduk->map(function($item) {
                                                return '
                                                    <tr>
                                                        <td class="border border-gray-300 p-2">' . $item->produk->Nama . '</td>
                                                        <td class="border border-gray-300 p-2 text-center">' . $item->qty . '</td>
                                                        <td class="border border-gray-300 p-2 text-right">Rp. ' . number_format($item->produk->Harga, 0, ",", ".") . '</td>
                                                        <td class="border border-gray-300 p-2 text-right">Rp. ' . number_format($item->harga * $item->qty, 0, ",", ".") . '</td>
                                                    </tr>
                                                ';
                                            })->implode('') . '
                                            <tr class="bg-gray-50">
                                                <td colspan="3" class="border border-gray-300 p-2 text-right font-semibold">Total Produk:</td>
                                                <td class="border border-gray-300 p-2 text-right font-semibold">Rp. ' . number_format($totalProduk, 0, ",", ".") . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                ' : '') . '

                                ' . ($record->detailPerawatan->count() > 0 ? '
                                <div>
                                    <h3 class="font-semibold">Perawatan</h3>
                                    <table class="w-full border-collapse border border-gray-300">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-300 p-2 text-left">Nama Perawatan</th>
                                                <th class="border border-gray-300 p-2 text-center">Qty</th>
                                                <th class="border border-gray-300 p-2 text-right">Harga Satuan</th>
                                                <th class="border border-gray-300 p-2 text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ' . $record->detailPerawatan->map(function($item) {
                                                return '
                                                    <tr>
                                                        <td class="border border-gray-300 p-2">' . $item->perawatan->Nama_Perawatan . '</td>
                                                        <td class="border border-gray-300 p-2 text-center">' . $item->qty . '</td>
                                                        <td class="border border-gray-300 p-2 text-right">Rp. ' . number_format($item->perawatan->Harga, 0, ",", ".") . '</td>
                                                        <td class="border border-gray-300 p-2 text-right">Rp. ' . number_format($item->harga * $item->qty, 0, ",", ".") . '</td>
                                                    </tr>
                                                ';
                                            })->implode('') . '
                                            <tr class="bg-gray-50">
                                                <td colspan="3" class="border border-gray-300 p-2 text-right font-semibold">Total Perawatan:</td>
                                                <td class="border border-gray-300 p-2 text-right font-semibold">Rp. ' . number_format($totalPerawatan, 0, ",", ".") . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                ' : '') . '

                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h3 class="font-semibold text-lg text-blue-900">Total Pembayaran: Rp. ' . number_format($total, 0, ",", ".") . '</h3>
                                </div>
                            </div>
                        ');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['pelanggan']);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with(['detailProduk.produk', 'detailPerawatan.perawatan']);
    }
}
