<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesananResource\Pages;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Perawatan;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-m-shopping-cart';

    protected static ?string $pluralModelLabel = 'Pesanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pelanggan_id')
                    ->relationship('pelanggan', 'Nama')
                    ->label('Pelanggan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('Nama')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(2),
                                TextInput::make('Email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                TextInput::make('Nomor_Telepon')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->maxLength(15)
                                    ->columnSpan(1),
                                Select::make('Status')
                                    ->options([
                                        'Member' => 'Member',
                                        'Non Member' => 'Non Member'
                                    ])
                                    ->default('Non Member')
                                    ->required()
                                    ->columnSpan(2),
                            ])
                    ])
                    ->createOptionUsing(function (array $data) {
                        try {
                            $pelanggan = Pelanggan::create($data);
                            Notification::make()
                                ->title('Pelanggan Berhasil Ditambahkan')
                                ->success()
                                ->send();
                            return $pelanggan->id;
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal Menambah Pelanggan')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                            return null;
                        }
                    }),

                Select::make("Metode_Pembayaran")
                    ->options([
                        "Cash" => "Cash",
                        "QRIS" => "QRIS",
                        "Debit" => "Debit"
                    ])
                    ->required()
                    ->default('Cash'),

                Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'Berhasil' => 'Berhasil',
                        'Dibatalkan' => 'Dibatalkan'
                    ])
                    ->default('Berhasil')
                    ->required()
                    ->reactive(),

                // **KEMBALI KE detailProduk TAPI TANPA RELATIONSHIP**
                Repeater::make('detailProduk')
                    ->label('Produk')
                    ->schema([
                        Select::make('produk_id')
                            ->label('Produk')
                            ->options(Produk::query()->pluck('Nama', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->preload()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $product = Produk::find($state);
                                if ($product) {
                                    $set('harga', $product->Harga);
                                } else {
                                    $set('harga', 0);
                                }
                            }),

                        TextInput::make('qty')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $produkId = $get('produk_id');
                                if ($produkId) {
                                    $produk = Produk::find($produkId);
                                    if ($produk && (int)$state > $produk->Stok) {
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
                            ->readOnly(),
                    ])
                    ->columns(3)
                    ->addActionLabel('Tambah Produk')
                    ->columnSpanFull()
                    ->defaultItems(0)
                    ->reorderable(false)
                    ->extraItemActions([
                        Forms\Components\Actions\Action::make('remove')
                            ->icon('heroicon-m-trash')
                            ->color('danger')
                            ->action(function ($state, Forms\Set $set, $index) {
                                $items = $state;
                                unset($items[$index]);
                                $set('detailProduk', array_values($items));
                            }),
                    ]),

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
                            ->afterStateUpdated(function ($state, callable $set) {
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
                    ->defaultItems(0),

                Placeholder::make('grand_total')
                    ->label('GRAND TOTAL')
                    ->content(function ($get) {
                        $totalProduk = collect($get('detailProduk') ?? [])
                            ->sum(fn($item) => (int)($item['harga'] ?? 0) * (int)($item['qty'] ?? 0));

                        $totalPerawatan = collect($get('detailPerawatan') ?? [])
                            ->sum(fn($item) => (int)($item['harga'] ?? 0) * (int)($item['qty'] ?? 0));

                        $grandTotal = $totalProduk + $totalPerawatan;

                        return new HtmlString('
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h3 class="text-lg font-bold text-blue-900 text-center">
                                    GRAND TOTAL: Rp. ' . number_format($grandTotal, 0, ",", ".") . '
                                </h3>
                            </div>
                        ');
                    })
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    // ... rest of the code tetap sama


    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('pelanggan.Nama')
                ->label('Pelanggan')
                ->searchable()
                ->sortable(),

            TextColumn::make('status')
                ->label('Status Pesanan')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Berhasil' => 'success',
                    'Dibatalkan' => 'danger',
                    default => 'gray',
                })
                ->sortable(),

            TextColumn::make('total_pembayaran')
                ->label('Total Pembayaran')
                ->getStateUsing(function ($record) {
                    $totalProduk = \App\Models\PesananProduk::where('pesanan_id', $record->id)
                        ->sum(\DB::raw('qty * harga'));

                    $totalPerawatan = \App\Models\PesananPerawatan::where('pesanan_id', $record->id)
                        ->sum(\DB::raw('qty * harga'));

                    $total = $totalProduk + $totalPerawatan;
                    return 'Rp ' . number_format($total, 0, ',', '.');
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
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('pelanggan_status')
                ->label('Status Pelanggan')
                ->relationship('pelanggan', 'Status')
                ->options([
                    'Member' => 'Member',
                    'Non Member' => 'Non Member'
                ]),

            Tables\Filters\SelectFilter::make('Metode_Pembayaran')
                ->options([
                    'Cash' => 'Cash',
                    'QRIS' => 'QRIS',
                    'Debit' => 'Debit',
                ]),

            Tables\Filters\SelectFilter::make('status')
                ->label('Status Pesanan')
                ->options([
                    'Berhasil' => 'Berhasil',
                    'Dibatalkan' => 'Dibatalkan'
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
                                    <p>Status: ' . $record->pelanggan->Status . '</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Informasi Pesanan</h3>
                                    <p>Tanggal: ' . $record->created_at->format('d M Y H:i') . '</p>
                                    <p>Metode Pembayaran: ' . $record->Metode_Pembayaran . '</p>
                                    <p>Status Pesanan: ' . $record->status . '</p>
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

            // **ACTION BATALKAN - Hanya untuk status Berhasil**
            Action::make('cancel')
                ->label('Batalkan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (Pesanan $record): bool => $record->status === 'Berhasil')
                ->requiresConfirmation()
                ->modalHeading('Batalkan Pesanan')
                ->modalDescription('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan.')
                ->modalSubmitActionLabel('Ya, Batalkan')
                ->action(function (Pesanan $record) {
                    try {
                        \Illuminate\Support\Facades\DB::transaction(function () use ($record) {
                            // Update status menjadi Dibatalkan
                            $record->update(['status' => 'Dibatalkan']);

                            // Kembalikan stok produk
                            foreach ($record->detailProduk as $detail) {
                                $detail->produk->tambahStok(
                                    $detail->qty,
                                    "Pengembalian stok karena pembatalan pesanan #{$record->id}"
                                );
                            }
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan berhasil dibatalkan')
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal membatalkan pesanan')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            // **ACTION AKTIFKAN KEMBALI - Hanya untuk status Dibatalkan**
            Action::make('activate')
                ->label('Aktifkan Kembali')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (Pesanan $record): bool => $record->status === 'Dibatalkan')
                ->requiresConfirmation()
                ->modalHeading('Aktifkan Pesanan Kembali')
                ->modalDescription('Apakah Anda yakin ingin mengaktifkan pesanan ini kembali? Stok produk akan dikurangi.')
                ->modalSubmitActionLabel('Ya, Aktifkan')
                ->action(function (Pesanan $record) {
                    try {
                        \Illuminate\Support\Facades\DB::transaction(function () use ($record) {
                            // Validasi stok sebelum mengaktifkan
                            foreach ($record->detailProduk as $detail) {
                                if ($detail->produk->Stok < $detail->qty) {
                                    throw new \Exception("Stok produk {$detail->produk->Nama} tidak mencukupi. Stok tersedia: {$detail->produk->Stok}, diperlukan: {$detail->qty}");
                                }
                            }

                            // Update status menjadi Berhasil
                            $record->update(['status' => 'Berhasil']);

                            // Kurangi stok produk
                            foreach ($record->detailProduk as $detail) {
                                $detail->produk->kurangiStok(
                                    $detail->qty,
                                    "Pengurangan stok karena aktivasi pesanan #{$record->id}"
                                );
                            }
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan berhasil diaktifkan kembali')
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal mengaktifkan pesanan')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            // **HAPUS EDIT ACTION - Tidak bisa edit pesanan yang sudah dibuat**
            // Tables\Actions\EditAction::make(),

            // **HAPUS DELETE ACTION - Tidak bisa hapus pesanan**
            // Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            // **HAPUS BULK ACTIONS - Tidak bisa hapus multiple pesanan**
            // Tables\Actions\BulkActionGroup::make([
            //     Tables\Actions\DeleteBulkAction::make(),
            // ]),
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

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('kasir') || auth()->user()->hasAnyRole(['admin', 'manajer']);
    }
}
