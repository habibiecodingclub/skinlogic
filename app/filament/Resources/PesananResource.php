<?php

namespace App\Filament\Resources;

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
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;

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
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make("Metode_Pembayaran")
                    ->options([
                        "Cash" => "Cash",
                        "QRIS" => "QRIS",
                        "Debit" => "Debit"
                    ])
                    ->required()
                    ->default('Cash'),

                // Repeater untuk Produk - DIPERBAIKI
                Repeater::make('detailProduk')
                    ->label('Produk')
                    ->relationship('detailProduk')
                    ->schema([
                        Select::make('produk_id')
                            ->label('Produk')
                            ->options(Produk::query()->pluck('Nama', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->preload()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $product = Produk::find($state);
                                if ($product) {
                                    $set('harga', $product->Harga);
                                    // HAPUS: $set('stok_available', $product->Stok);
                                } else {
                                    $set('harga', 0);
                                    // HAPUS: $set('stok_available', 0);
                                }
                            })
                            ->reactive(),

                        TextInput::make('qty')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, $get) {
                                $produkId = $get('produk_id');
                                if ($produkId) {
                                    $produk = Produk::find($produkId);
                                    if ($produk && $state > $produk->Stok) {
                                        $set('qty', $produk->Stok);
                                        Notification::make()
                                            ->title('Stok Tidak Cukup')
                                            ->body("Stok hanya tersedia {$produk->Stok} unit")
                                            ->danger()
                                            ->send();
                                    }
                                }
                            }),

                        TextInput::make('harga')
                            ->label("Harga Satuan")
                            ->numeric()
                            ->prefix("Rp. ")
                            ->default(0)
                            ->readOnly()
                            ->reactive(),

                        // HAPUS FIELD stok_available dari schema karena tidak ada di database
                        // TextInput::make('stok_available') -> HAPUS
                    ])
                    ->columns(3) // Ubah dari 4 menjadi 3 kolom
                    ->addActionLabel('Tambah Produk')
                    ->columnSpanFull()
                    ->createItemButtonLabel('Tambah Produk')
                    ->defaultItems(0),

                // Repeater untuk Perawatan
                Repeater::make('detailPerawatan')
                    ->label('Perawatan')
                    ->relationship('detailPerawatan')
                    ->schema([
                        Select::make('perawatan_id')
                            ->label('Perawatan')
                            ->options(Perawatan::query()->pluck('Nama_Perawatan', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->preload()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $service = Perawatan::find($state);
                                if ($service) {
                                    $set('harga', $service->Harga);
                                } else {
                                    $set('harga', 0);
                                }
                            }),

                        TextInput::make('qty')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),

                        TextInput::make('harga')
                            ->label("Harga Satuan")
                            ->numeric()
                            ->prefix("Rp. ")
                            ->default(0)
                            ->readOnly(),
                    ])
                    ->columns(3)
                    ->addActionLabel('Tambah Perawatan')
                    ->columnSpanFull()
                    ->createItemButtonLabel('Tambah Perawatan')
                    ->defaultItems(0),

                // Grand Total
                Placeholder::make('grand_total')
                    ->label('GRAND TOTAL')
                    ->content(function ($get) {
                        $totalProduk = collect($get('detailProduk') ?? [])
                            ->sum(fn($item) => ($item['harga'] ?? 0) * ($item['qty'] ?? 0));

                        $totalPerawatan = collect($get('detailPerawatan') ?? [])
                            ->sum(fn($item) => ($item['harga'] ?? 0) * ($item['qty'] ?? 0));

                        $grandTotal = $totalProduk + $totalPerawatan;

                        return new HtmlString('
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h3 class="text-lg font-bold text-blue-900 text-center">
                                    GRAND TOTAL: Rp. ' . number_format($grandTotal, 0, ",", ".") . '
                                </h3>
                                <div class="grid grid-cols-2 gap-4 mt-2 text-sm text-blue-800">
                                    <div>Total Produk: Rp. ' . number_format($totalProduk, 0, ",", ".") . '</div>
                                    <div>Total Perawatan: Rp. ' . number_format($totalPerawatan, 0, ",", ".") . '</div>
                                </div>
                            </div>
                        ');
                    })
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

                TextColumn::make('total_pembayaran')
                    ->label('Total Pembayaran')
                    ->formatStateUsing(function ($record) {
                        $totalProduk = $record->detailProduk->sum(function($item) {
                            return $item->harga * $item->qty;
                        });
                        $totalPerawatan = $record->detailPerawatan->sum(function($item) {
                            return $item->harga * $item->qty;
                        });
                        $total = $totalProduk + $totalPerawatan;
                        return 'Rp. ' . number_format($total, 0, ',', '.');
                    })
                    ->sortable(),

                TextColumn::make("Metode_Pembayaran")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Cash' => 'success',
                        'QRIS' => 'primary',
                        'Debit' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make("created_at")
                    ->label("Tanggal Pesanan")
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('produk_count')
                    ->label('Jumlah Produk')
                    ->getStateUsing(fn ($record) => $record->detailProduk->count())
                    ->badge(),

                TextColumn::make('perawatan_count')
                    ->label('Jumlah Perawatan')
                    ->getStateUsing(fn ($record) => $record->detailPerawatan->count())
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Metode_Pembayaran')
                    ->options([
                        'Cash' => 'Cash',
                        'QRIS' => 'QRIS',
                        'Debit' => 'Debit',
                    ]),
            ])
            ->actions([
                Action::make('view')
                    ->label('Detail')
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
                                        <p>Email: ' . ($record->pelanggan->Email ?? '-') . '</p>
                                        <p>Telepon: ' . ($record->pelanggan->Nomor_Telepon ?? '-') . '</p>
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
                                                        <td class="border border-gray-300 p-2 text-right">Rp. ' . number_format($item->harga, 0, ",", ".") . '</td>
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
                                                        <td class="border border-gray-300 p-2 text-right">Rp. ' . number_format($item->harga, 0, ",", ".") . '</td>
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
                                    <h3 class="font-semibold text-lg text-blue-900">GRAND TOTAL: Rp. ' . number_format($total, 0, ",", ".") . '</h3>
                                </div>
                            </div>
                        ');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Handle bulk delete - kembalikan stok
                            foreach ($records as $pesanan) {
                                foreach ($pesanan->detailProduk as $detail) {
                                    $detail->produk->increment('Stok', $detail->qty);
                                    $detail->produk->tambahStok(
                                        $detail->qty,
                                        "Pembatalan massal pesanan #{$pesanan->id}",
                                        now()
                                    );
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
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'pelanggan',
                'detailProduk.produk',
                'detailPerawatan.perawatan'
            ]);
    }
}
