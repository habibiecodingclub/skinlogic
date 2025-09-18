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
                                try {
                                    $product = Produk::find($state);
                                    if ($product) {
                                        $set('harga', $product->Harga);
                                        $set('stok_available', $product->Stok);
                                    } else {
                                        $set('harga', 0);
                                        $set('stok_available', 0);
                                        Notification::make()
                                            ->title('Produk tidak ditemukan')
                                            ->danger()
                                            ->send();
                                    }
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Terjadi kesalahan saat memuat data produk')
                                        ->danger()
                                        ->send();
                                }
                            }),
                        TextInput::make('qty')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, $get) {
                                $produkId = $get('produk_id');
                                $stokAvailable = $get('stok_available');

                                if ($produkId && $stokAvailable !== null) {
                                    if ($state > $stokAvailable) {
                                        $set('qty', $stokAvailable);

                                        Notification::make()
                                            ->title('Stok Tidak Cukup')
                                            ->body("Stok produk hanya tersedia {$stokAvailable} unit")
                                            ->danger()
                                            ->send();
                                    }
                                }
                            })
                            ->rules([
                                function ($get) {
                                    return function (string $attribute, $value, $fail) use ($get) {
                                        $produkId = $get('produk_id');
                                        if ($produkId) {
                                            $produk = Produk::find($produkId);
                                            if ($produk && $value > $produk->Stok) {
                                                $fail("Stok {$produk->Nama} hanya tersedia {$produk->Stok} unit");
                                                Notification::make()
                                                    ->title('Stok Tidak Cukup')
                                                    ->danger()
                                                    ->send();
                                            }
                                        }
                                    };
                                },
                            ]),
                        TextInput::make('harga')
                            ->label("Harga Satuan")
                            ->numeric()
                            ->prefix("Rp. ")
                            ->default(function ($get) {
                                $produkId = $get('produk_id');
                                if ($produkId) {
                                    return Produk::find($produkId)->Harga;
                                }
                                return 0;
                            })
                            ->readOnly(),
                        TextInput::make('stok_available')
                            ->hidden()
                            ->default(0),
                    ])
                    ->addActionLabel('Tambah Produk')
                    ->columnSpanFull()
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        if (isset($data['produk_id']) && isset($data['qty'])) {
                            $produk = Produk::find($data['produk_id']);
                            if ($produk) {
                                if ($data['qty'] > $produk->Stok) {
                                    Notification::make()
                                        ->title('Stok Tidak Cukup')
                                        ->body("Stok {$produk->Nama} hanya tersedia {$produk->Stok} unit")
                                        ->danger()
                                        ->persistent()
                                        ->send();

                                    throw new \Exception("Stok {$produk->Nama} tidak mencukupi");
                                }

                                $produk->decrement('Stok', $data['qty']);

                                Notification::make()
                                    ->title('Stok Berhasil Dikurangi')
                                    ->body("Stok {$produk->Nama} berkurang {$data['qty']} unit")
                                    ->success()
                                    ->send();
                            }
                        }
                        return $data;
                    }),

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
                                try {
                                    $service = Perawatan::find($state);
                                    if ($service) {
                                        $set('harga', $service->Harga);
                                    } else {
                                        $set('harga', 0);
                                        Notification::make()
                                            ->title('Perawatan tidak ditemukan')
                                            ->danger()
                                            ->send();
                                    }
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Terjadi kesalahan saat memuat data perawatan')
                                        ->danger()
                                        ->send();
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
                            ->default(function ($get) {
                                $perawatanId = $get('perawatan_id');
                                if ($perawatanId) {
                                    return Perawatan::find($perawatanId)->Harga;
                                }
                                return 0;
                            })
                            ->readOnly(),
                    ])
                    ->addActionLabel('Tambah Perawatan')
                    ->columnSpanFull(),

                // Grand Total
                Placeholder::make('grand_total')
                    ->label('GRAND TOTAL')
                    ->content(function ($get) {
                        $totalProduk = 0;
                        $totalPerawatan = 0;

                        // Hitung total produk
                        $detailProduk = $get('detailProduk');
                        if (is_array($detailProduk)) {
                            foreach ($detailProduk as $item) {
                                if (isset($item['harga']) && isset($item['qty'])) {
                                    $totalProduk += ($item['harga'] * $item['qty']);
                                }
                            }
                        }

                        // Hitung total perawatan
                        $detailPerawatan = $get('detailPerawatan');
                        if (is_array($detailPerawatan)) {
                            foreach ($detailPerawatan as $item) {
                                if (isset($item['harga']) && isset($item['qty'])) {
                                    $totalPerawatan += ($item['harga'] * $item['qty']);
                                }
                            }
                        }

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
            ->query(Pesanan::with(['detailProduk.produk', 'detailPerawatan.perawatan']))
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
            ->actions([
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
                                    <h3 class="font-semibold text-lg text-blue-900">GRAND TOTAL: Rp. ' . number_format($total, 0, ",", ".") . '</h3>
                                    <div class="grid grid-cols-2 gap-4 mt-2 text-sm text-blue-800">
                                        <div>Total Produk: Rp. ' . number_format($totalProduk, 0, ",", ".") . '</div>
                                        <div>Total Perawatan: Rp. ' . number_format($totalPerawatan, 0, ",", ".") . '</div>
                                    </div>
                                </div>
                            </div>
                        ');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
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
            ->with([
                'pelanggan',
                'detailProduk.produk',
                'detailPerawatan.perawatan'
            ]);
    }
}
