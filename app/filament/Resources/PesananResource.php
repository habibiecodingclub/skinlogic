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
                // **PRODUK - PERBAIKI HARGA OTOMATIS**
                // **PRODUK - PERBAIKI HARGA OTOMATIS UNTUK BUNDLING**
                Forms\Components\Section::make('Produk')
                    ->schema([
                        Repeater::make('items_produk')
                            ->label('')
                            ->schema([
                                Select::make('produk_id')
                                    ->label('Pilih Produk')
                                    ->options(Produk::all()->pluck('Nama', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if ($state) {
                                            $produk = Produk::find($state);
                                            if ($produk) {
                                                // **PERBAIKAN: Ambil harga yang benar berdasarkan jenis produk**
                                                $harga = $produk->is_bundling ? $produk->harga_bundling : $produk->Harga;
                                                $set('harga', $harga);
                                                Log::info("🛒 Set harga produk: {$harga} untuk produk ID: {$state} (Bundling: " . ($produk->is_bundling ? 'Ya' : 'Tidak') . ")");

                                                // **VALIDASI STOK UNTUK BUNDLING**
                                                if ($produk->is_bundling) {
                                                    $stokTersedia = $produk->getStokTersediaAttribute();
                                                    Log::info("🎁 Stok tersedia bundling {$produk->Nama}: {$stokTersedia}");
                                                }
                                            }
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
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $produkId = $get('produk_id');
                                        if ($produkId) {
                                            $produk = Produk::find($produkId);
                                            if ($produk) {
                                                if ($produk->is_bundling) {
                                                    // **VALIDASI STOK BUNDLING**
                                                    $stokTersedia = $produk->getStokTersediaAttribute();
                                                    if ((int)$state > $stokTersedia) {
                                                        $set('qty', $stokTersedia);
                                                        Notification::make()
                                                            ->title('Stok Bundling Tidak Cukup')
                                                            ->body("Stok bundling hanya tersedia {$stokTersedia} unit")
                                                            ->danger()
                                                            ->send();
                                                    }
                                                } else {
                                                    // **VALIDASI STOK PRODUK BIASA**
                                                    if ((int)$state > $produk->Stok) {
                                                        $set('qty', $produk->Stok);
                                                        Notification::make()
                                                            ->title('Stok Tidak Cukup')
                                                            ->body("Stok hanya tersedia {$produk->Stok} unit")
                                                            ->danger()
                                                            ->send();
                                                    }
                                                }
                                            }
                                        }
                                    }),

                                TextInput::make('harga')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp ')
                                    ->required()
                                    ->default(0)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        Log::info("🛒 Harga diubah manual: {$state}");
                                    }),
                            ])
                            ->columns(3)
                            ->addActionLabel('Tambah Produk')
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->extraItemActions([
                                Forms\Components\Actions\Action::make('remove')
                                    ->icon('heroicon-m-trash')
                                    ->color('danger')
                                    ->action(function ($state, Forms\Set $set, $index) {
                                        $items = $state;
                                        unset($items[$index]);
                                        $set('items_produk', array_values($items));
                                    }),
                            ]),
                    ])
                    ->collapsible(),

                // **PERAWATAN - PERBAIKI HARGA OTOMATIS**
                Forms\Components\Section::make('Perawatan')
                    ->schema([
                        Repeater::make('items_perawatan')
                            ->label('')
                            ->schema([
                                Select::make('perawatan_id')
                                    ->label('Pilih Perawatan')
                                    ->options(Perawatan::all()->pluck('Nama_Perawatan', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if ($state) {
                                            $perawatan = Perawatan::find($state);
                                            if ($perawatan) {
                                                $set('harga', $perawatan->Harga);
                                                Log::info("💆 Set harga perawatan: {$perawatan->Harga} untuk perawatan ID: {$state}");
                                            }
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
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp ')
                                    ->required() // **UBAH JADI REQUIRED**
                                    ->default(0)
                                    ->reactive(),
                            ])
                            ->columns(3)
                            ->addActionLabel('Tambah Perawatan')
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->extraItemActions([
                                Forms\Components\Actions\Action::make('remove')
                                    ->icon('heroicon-m-trash')
                                    ->color('danger')
                                    ->action(function ($state, Forms\Set $set, $index) {
                                        $items = $state;
                                        unset($items[$index]);
                                        $set('items_perawatan', array_values($items));
                                    }),
                            ]),
                    ])
                    ->collapsible(),

                Placeholder::make('grand_total')
                    ->label('GRAND TOTAL')
                    ->content(function ($get) {
                        $totalProduk = collect($get('items_produk') ?? [])
                            ->sum(fn($item) => (int)($item['harga'] ?? 0) * (int)($item['qty'] ?? 0));

                        $totalPerawatan = collect($get('items_perawatan') ?? [])
                            ->sum(fn($item) => (int)($item['harga'] ?? 0) * (int)($item['qty'] ?? 0));

                        $grandTotal = $totalProduk + $totalPerawatan;

                        // **DEBUG: Log perhitungan**
                        Log::info("💰 Grand Total Calculation:");
                        Log::info("💰 Total Produk: {$totalProduk}");
                        Log::info("💰 Total Perawatan: {$totalPerawatan}");
                        Log::info("💰 Grand Total: {$grandTotal}");

                        return new HtmlString('
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h3 class="text-lg font-bold text-blue-900 text-center">
                    GRAND TOTAL: Rp. ' . number_format($grandTotal, 0, ",", ".") . '
                </h3>
                <div class="grid grid-cols-2 gap-2 mt-2 text-sm text-blue-800">
                    <div class="text-right">Total Produk:</div>
                    <div>Rp. ' . number_format($totalProduk, 0, ",", ".") . '</div>
                    <div class="text-right">Total Perawatan:</div>
                    <div>Rp. ' . number_format($totalPerawatan, 0, ",", ".") . '</div>
                </div>
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
                    ->color(fn(string $state): string => match ($state) {
                        'Berhasil' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                // **TOTAL PEMBAYARAN - CARA YANG LEBIH AKURAT**
                TextColumn::make('total_pembayaran')
                    ->label('Total Pembayaran')
                    ->getStateUsing(function ($record) {
                        // Query manual untuk memastikan data akurat
                        $totalProduk = \App\Models\PesananProduk::where('pesanan_id', $record->id)
                            ->sum(\DB::raw('harga * qty'));

                        $totalPerawatan = \App\Models\PesananPerawatan::where('pesanan_id', $record->id)
                            ->sum(\DB::raw('harga * qty'));

                        $total = $totalProduk + $totalPerawatan;

                        return 'Rp ' . number_format($total, 0, ',', '.');
                    })
                    ->sortable()
                    ->searchable(),



                TextColumn::make("Metode_Pembayaran")
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Cash' => 'success',
                        'QRIS' => 'primary',
                        'Debit' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make("created_at")
                    ->label("Tanggal Pesanan")
                    ->dateTime('d M Y')
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
                        // **QUERY MANUAL UNTUK MENDAPATKAN DATA**
                        $detailProduk = \App\Models\PesananProduk::with('produk')
                            ->where('pesanan_id', $record->id)
                            ->get();

                        $detailPerawatan = \App\Models\PesananPerawatan::with('perawatan')
                            ->where('pesanan_id', $record->id)
                            ->get();

                        $totalProduk = $detailProduk->sum(function ($item) {
                            return $item->harga * $item->qty;
                        });

                        $totalPerawatan = $detailPerawatan->sum(function ($item) {
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
            <p>Email: ' . ($record->pelanggan->Email ?? '-') . '</p>
            <p>Telepon: ' . ($record->pelanggan->Nomor_Telepon ?? '-') . '</p>
        </div>
        <div>
            <h3 class="font-semibold">Informasi Pesanan</h3>
            <p>Tanggal: ' . $record->created_at->format('d M Y H:i') . '</p>
            <p>Metode Pembayaran: ' . $record->Metode_Pembayaran . '</p>
            <p>Status Pesanan: <span class="' . ($record->status === 'Berhasil' ? 'text-green-600 font-medium' : 'text-red-600 font-medium') . '">' . $record->status . '</span></p>
            <p>ID Pesanan: #' . $record->id . '</p>
        </div>
    </div>

    ' . ($detailProduk->count() > 0 ? '
    <div>
        <h3 class="font-semibold text-lg mb-2">Produk (' . $detailProduk->count() . ' item)</h3>
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
                ' . $detailProduk->map(function ($item) {
                            $produkNama = $item->produk ? $item->produk->Nama : 'Produk tidak ditemukan';
                            return '
                        <tr>
                            <td class="border border-gray-300 p-2">' . $produkNama . '</td>
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
    ' : '<div><p class="text-gray-500">Tidak ada produk dalam pesanan ini.</p></div>') . '

    ' . ($detailPerawatan->count() > 0 ? '
    <div>
        <h3 class="font-semibold text-lg mb-2">Perawatan (' . $detailPerawatan->count() . ' item)</h3>
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
                ' . $detailPerawatan->map(function ($item) {
                            $perawatanNama = $item->perawatan ? $item->perawatan->Nama_Perawatan : 'Perawatan tidak ditemukan';
                            return '
                        <tr>
                            <td class="border border-gray-300 p-2">' . $perawatanNama . '</td>
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
    ' : '<div><p class="text-gray-500">Tidak ada perawatan dalam pesanan ini.</p></div>') . '

    <div class="bg-blue-50 p-4 rounded-lg border-2 border-blue-200">
        <h3 class="font-semibold text-lg text-blue-900 text-center">
            GRAND TOTAL: Rp. ' . number_format($total, 0, ",", ".") . '
        </h3>
    </div>
</div>
        ');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('5xl'), // Tambahkan width lebih lebar

                // **ACTION BATALKAN - Hanya untuk status Berhasil**
                // Dalam bagian actions, ganti action cancel dengan ini:

                Action::make('cancel')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Pesanan $record): bool => $record->status === 'Berhasil')
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Pesanan')
                    ->modalDescription('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan.')
                    ->modalSubmitActionLabel('Ya, Batalkan')
                    ->action(function (Pesanan $record) {
                        try {
                            \Illuminate\Support\Facades\DB::transaction(function () use ($record) {
                                Log::info("🔄 === MEMBATALKAN PESANAN #{$record->id} ===");

                                // Update status menjadi Dibatalkan
                                $record->update(['status' => 'Dibatalkan']);

                                // **KEMBALIKAN STOK PRODUK - DENGAN HANDLING BUNDLING**
                                foreach ($record->detailProduk as $detail) {
                                    $produk = $detail->produk;

                                    if ($produk->is_bundling) {
                                        // **PRODUK BUNDLING: Kembalikan stok semua produk dalam bundling**
                                        Log::info("🔄🎁 Kembalikan stok bundling: {$produk->Nama}, Qty: {$detail->qty}");
                                        $produk->kembalikanStokBundling(
                                            $detail->qty,
                                            "Pembatalan pesanan #{$record->id}",
                                            now()
                                        );
                                        Log::info("🔄🎁✅ Berhasil mengembalikan stok bundling {$produk->Nama}");
                                    } else {
                                        // **PRODUK BIASA: Kembalikan stok seperti biasa**
                                        Log::info("🔄 Kembalikan stok produk langsung: {$produk->Nama}, Qty: {$detail->qty}");
                                        $produk->tambahStok(
                                            $detail->qty,
                                            "Pengembalian stok karena pembatalan pesanan #{$record->id}",
                                            now()
                                        );
                                        Log::info("🔄✅ Berhasil mengembalikan stok produk {$produk->Nama}");
                                    }
                                }

                                // **KEMBALIKAN STOK PRODUK DARI PERAWATAN**
                                foreach ($record->detailPerawatan as $detail) {
                                    $perawatan = $detail->perawatan;
                                    Log::info("🔄 Kembalikan stok untuk perawatan: {$perawatan->Nama_Perawatan}, Qty: {$detail->qty}");

                                    // Gunakan method yang sudah diperbaiki
                                    $perawatan->kembalikanStokProdukBulk(
                                        $detail->qty,
                                        $record->id
                                    );

                                    Log::info("🔄✅ Berhasil mengembalikan stok untuk perawatan {$perawatan->Nama_Perawatan}");
                                }

                                Log::info("🔄✅ Pesanan #{$record->id} berhasil dibatalkan dan semua stok dikembalikan");
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Pesanan berhasil dibatalkan')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Log::error("💥 Gagal membatalkan pesanan: " . $e->getMessage());
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal membatalkan pesanan')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                // Juga perbaiki action activate untuk konsistensi:
                Action::make('activate')
                    ->label('Aktifkan Kembali')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Pesanan $record): bool => $record->status === 'Dibatalkan')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan Pesanan Kembali')
                    ->modalDescription('Apakah Anda yakin ingin mengaktifkan pesanan ini kembali? Stok produk akan dikurangi.')
                    ->modalSubmitActionLabel('Ya, Aktifkan')
                    ->action(function (Pesanan $record) {
                        try {
                            \Illuminate\Support\Facades\DB::transaction(function () use ($record) {
                                Log::info("⚡ === MENGATIFKAN KEMBALI PESANAN #{$record->id} ===");

                                // **VALIDASI STOK SEBELUM MENGATIFKAN - DENGAN HANDLING BUNDLING**
                                foreach ($record->detailProduk as $detail) {
                                    $produk = $detail->produk;

                                    if ($produk->is_bundling) {
                                        // **VALIDASI STOK BUNDLING**
                                        foreach ($produk->produkBundlingItems as $bundlingItem) {
                                            $totalQtyDigunakan = $bundlingItem->qty * $detail->qty;
                                            if ($bundlingItem->produk->Stok < $totalQtyDigunakan) {
                                                throw new \Exception(
                                                    "Stok tidak mencukupi untuk mengaktifkan bundling {$produk->Nama}. " .
                                                        "Produk {$bundlingItem->produk->Nama} hanya tersedia {$bundlingItem->produk->Stok}, " .
                                                        "diperlukan {$totalQtyDigunakan}"
                                                );
                                            }
                                        }
                                    } else {
                                        // **VALIDASI STOK PRODUK BIASA**
                                        if ($produk->Stok < $detail->qty) {
                                            throw new \Exception(
                                                "Stok produk {$produk->Nama} tidak mencukupi. " .
                                                    "Stok tersedia: {$produk->Stok}, diperlukan: {$detail->qty}"
                                            );
                                        }
                                    }
                                }

                                // Validasi stok untuk produk dalam perawatan
                                foreach ($record->detailPerawatan as $detail) {
                                    $perawatan = $detail->perawatan;
                                    foreach ($perawatan->produk as $produk) {
                                        $qtyDigunakan = $produk->pivot->qty_digunakan * $detail->qty;
                                        if ($produk->Stok < $qtyDigunakan) {
                                            throw new \Exception(
                                                "Stok produk {$produk->Nama} tidak mencukupi untuk perawatan {$perawatan->Nama_Perawatan}. " .
                                                    "Stok tersedia: {$produk->Stok}, diperlukan: {$qtyDigunakan}"
                                            );
                                        }
                                    }
                                }

                                // Update status menjadi Berhasil
                                $record->update(['status' => 'Berhasil']);

                                // **KURANGI STOK PRODUK - DENGAN HANDLING BUNDLING**
                                foreach ($record->detailProduk as $detail) {
                                    $produk = $detail->produk;

                                    if ($produk->is_bundling) {
                                        // **PRODUK BUNDLING: Kurangi stok semua produk dalam bundling**
                                        Log::info("⚡🎁 Kurangi stok bundling: {$produk->Nama}, Qty: {$detail->qty}");
                                        $produk->kurangiStokBundling(
                                            $detail->qty,
                                            "Aktivasi pesanan #{$record->id}",
                                            $record->created_at
                                        );
                                        Log::info("⚡🎁✅ Berhasil mengurangi stok bundling {$produk->Nama}");
                                    } else {
                                        // **PRODUK BIASA: Kurangi stok seperti biasa**
                                        Log::info("⚡ Kurangi stok produk langsung: {$produk->Nama}, Qty: {$detail->qty}");
                                        $produk->kurangiStok(
                                            $detail->qty,
                                            "Pengurangan stok karena aktivasi pesanan #{$record->id}",
                                            $record->created_at
                                        );
                                        Log::info("⚡✅ Berhasil mengurangi stok produk {$produk->Nama}");
                                    }
                                }

                                // Kurangi stok produk dari perawatan
                                foreach ($record->detailPerawatan as $detail) {
                                    $perawatan = $detail->perawatan;
                                    Log::info("⚡ Kurangi stok untuk perawatan: {$perawatan->Nama_Perawatan}, Qty: {$detail->qty}");

                                    $perawatan->kurangiStokProdukBulk(
                                        $detail->qty,
                                        $record->id
                                    );

                                    Log::info("⚡✅ Berhasil mengurangi stok untuk perawatan {$perawatan->Nama_Perawatan}");
                                }

                                Log::info("⚡✅ Pesanan #{$record->id} berhasil diaktifkan kembali");
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Pesanan berhasil diaktifkan kembali')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Log::error("💥 Gagal mengaktifkan pesanan: " . $e->getMessage());
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
            // 'edit' => Pages\EditPesanan::route('/{record}/edit'),
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

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return auth()->user()->hasRole('kasir') || auth()->user()->hasAnyRole(['admin', 'manajer']);
    // }
}
