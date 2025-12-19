<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use App\Models\Pelanggan;
use App\Models\Perawatan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Reservasi';

    protected static ?string $pluralModelLabel = 'Reservasi';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan')
                    ->schema([
                        Forms\Components\Select::make('pelanggan_id')
                            ->label('Pelanggan')
                            ->relationship('pelanggan', 'Nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('Nama')
                                            ->label('Nama Lengkap')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('Email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('Nomor_Telepon')
                                            ->label('Nomor Telepon')
                                            ->tel()
                                            ->maxLength(15)
                                            ->columnSpan(1),
                                        Forms\Components\Select::make('Status')
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
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Detail Reservasi')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_reservasi')
                                    ->label('Tanggal Reservasi')
                                    ->required()
                                    ->minDate(now())
                                    ->default(now()),

                                Forms\Components\TimePicker::make('jam_reservasi')
                                    ->label('Jam Reservasi')
                                    ->required()
                                    ->seconds(false)
                                    ->default('09:00'),

                                Forms\Components\Select::make('terapis_id')
                                    ->label('Terapis/Staff')
                                    ->options(User::role('terapis')->orWhere('id', auth()->id())->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                Forms\Components\Select::make('status')
                                    ->label('Status Reservasi')
                                    ->options(Reservation::getStatusOptions())
                                    ->default(Reservation::STATUS_MENUNGGU)
                                    ->required(),
                            ]),
                    ]),
// Di dalam form() method, ubah bagian Repeater:
Forms\Components\Section::make('Pilihan Perawatan')
    ->schema([
        Forms\Components\Repeater::make('perawatan_items')
            ->label('')
            ->schema([
                Forms\Components\Select::make('perawatan_id')
                    ->label('Pilih Perawatan')
                    ->options(Perawatan::all()->pluck('Nama_Perawatan', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $perawatan = Perawatan::find($state);
                            if ($perawatan) {
                                $set('harga', $perawatan->Harga);
                            }
                        } else {
                            $set('harga', 0);
                        }
                    }),

                Forms\Components\TextInput::make('qty')
                    ->label('Jumlah')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->required(),

                Forms\Components\TextInput::make('harga')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp ')
                    ->required()
                    ->default(0),
            ])
            ->columns(3)
            ->addActionLabel('Tambah Perawatan')
            ->defaultItems(1)
            ->reorderable(false)
            ->extraItemActions([
                Forms\Components\Actions\Action::make('remove')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->action(function ($state, Forms\Set $set, $index) {
                        $items = $state;
                        unset($items[$index]);
                        $set('perawatan_items', array_values($items));
                    }),
            ]),
    ]),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->placeholder('Catatan khusus untuk reservasi ini')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Ringkasan')
                    ->schema([
                        Forms\Components\Placeholder::make('total_harga')
    ->label('Total Harga')
    ->content(function ($get) {
        $total = 0;
        $perawatanItems = $get('perawatan_items') ?? [];

        foreach ($perawatanItems as $item) {
            $harga = $item['harga'] ?? 0;
            $qty = $item['qty'] ?? 0;
            $total += $harga * $qty;
        }

        return new HtmlString('
            <div class="text-2xl font-bold text-primary-600">
                Rp ' . number_format($total, 0, ',', '.') . '
            </div>
        ');
    }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pelanggan.Nama')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_reservasi')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jam_reservasi')
                    ->label('Jam')
                    ->time('H:i'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => Reservation::getStatusOptions()[$state] ?? $state)
                    ->color(fn($state) => match ($state) {
                        'menunggu' => 'warning',
                        'dikonfirmasi' => 'info',
                        'dikerjakan' => 'primary',
                        'selesai' => 'success',
                        'batal' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('terapis.name')
                    ->label('Terapis')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(Reservation::getStatusOptions()),

                Tables\Filters\Filter::make('tanggal_reservasi')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn($query, $date) => $query->whereDate('tanggal_reservasi', '>=', $date)
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn($query, $date) => $query->whereDate('tanggal_reservasi', '<=', $date)
                            );
                    }),

                Tables\Filters\SelectFilter::make('pelanggan_id')
                    ->label('Pelanggan')
                    ->relationship('pelanggan', 'Nama')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Reservation $record): bool => $record->status === Reservation::STATUS_MENUNGGU)
                    ->action(function (Reservation $record) {
                        $record->update(['status' => Reservation::STATUS_DIKONFIRMASI]);

                        Notification::make()
                            ->title('Reservasi Dikonfirmasi')
                            ->body('Reservasi telah dikonfirmasi dan siap untuk diproses.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('mulai')
                    ->label('Mulai')
                    ->icon('heroicon-o-play-circle')
                    ->color('primary')
                    ->visible(fn(Reservation $record): bool => $record->status === Reservation::STATUS_DIKONFIRMASI)
                    ->action(function (Reservation $record) {
                        $record->update(['status' => Reservation::STATUS_DIKERJAKAN]);

                        Notification::make()
                            ->title('Perawatan Dimulai')
                            ->body('Status reservasi telah diubah menjadi "Dikerjakan".')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-flag')
                    ->color('warning')
                    ->visible(fn(Reservation $record): bool => $record->status === Reservation::STATUS_DIKERJAKAN)
                    ->action(function (Reservation $record) {
                        $record->update(['status' => Reservation::STATUS_SELESAI]);

                        Notification::make()
                            ->title('Perawatan Selesai')
                            ->body('Reservasi telah selesai. Sekarang bisa dikonversi ke pesanan.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('buat_pesanan')
                    ->label('Buat Pesanan')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->visible(fn(Reservation $record): bool => $record->canConvertToOrder())
                    ->requiresConfirmation()
                    ->modalHeading('Konversi ke Pesanan')
                    ->modalDescription('Apakah Anda yakin ingin membuat pesanan dari reservasi ini?')
                    ->form([
                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'Cash' => 'Cash',
                                'QRIS' => 'QRIS',
                                'Debit' => 'Debit'
                            ])
                            ->default('Cash')
                            ->required(),
                    ])
                    ->action(function (Reservation $record, array $data) {
                        try {
                            $pesanan = $record->convertToOrder($data['metode_pembayaran']);

                            Notification::make()
                                ->title('Pesanan Berhasil Dibuat')
                                ->body('Reservasi telah dikonversi menjadi pesanan #' . $pesanan->id)
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal Membuat Pesanan')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('batal')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Reservation $record): bool =>
                        in_array($record->status, [
                            Reservation::STATUS_MENUNGGU,
                            Reservation::STATUS_DIKONFIRMASI
                        ])
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Reservasi')
                    ->modalDescription('Apakah Anda yakin ingin membatalkan reservasi ini?')
                    ->action(function (Reservation $record) {
                        $record->update(['status' => Reservation::STATUS_BATAL]);

                        Notification::make()
                            ->title('Reservasi Dibatalkan')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make()
    ->label('Detail')
    ->modalHeading('Detail Reservasi')
    ->modalContent(function (Reservation $record) {
        // Parse tanggal jika masih string
        $tanggal = $record->tanggal_reservasi;
        if (is_string($tanggal)) {
            $tanggal = \Carbon\Carbon::parse($tanggal);
        }

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
                        <h3 class="font-semibold">Informasi Reservasi</h3>
                        <p>Tanggal: ' . $tanggal->format('d M Y') . '</p>
                        <p>Jam: ' . $record->jam_reservasi . '</p>
                        <p>Status: <span class="px-2 py-1 rounded-full text-xs font-medium ' . ($record->status === "selesai" ? "bg-green-100 text-green-800" : ($record->status === "batal" ? "bg-red-100 text-red-800" : "bg-yellow-100 text-yellow-800")) . '">' . (Reservation::getStatusOptions()[$record->status] ?? $record->status) . '</span></p>
                        <p>Terapis: ' . ($record->terapis ? $record->terapis->name : '-') . '</p>
                    </div>
                </div>

                ' . ($record->perawatans->count() > 0 ? '
                <div>
                    <h3 class="font-semibold text-lg mb-2">Perawatan (' . $record->perawatans->count() . ' item)</h3>
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
                            ' . $record->perawatans->map(function ($perawatan) {
                                return '
                                    <tr>
                                        <td class="border border-gray-300 p-2">' . $perawatan->Nama_Perawatan . '</td>
                                        <td class="border border-gray-300 p-2 text-center">' . $perawatan->pivot->qty . '</td>
                                        <td class="border border-gray-300 p-2 text-right">Rp. ' . number_format($perawatan->pivot->harga, 0, ",", ".") . '</td>
                                        <td class="border border-gray-300 p-2 text-right">Rp. ' . number_format($perawatan->pivot->harga * $perawatan->pivot->qty, 0, ",", ".") . '</td>
                                    </tr>
                                ';
                            })->implode('') . '
                            <tr class="bg-gray-50">
                                <td colspan="3" class="border border-gray-300 p-2 text-right font-semibold">Total:</td>
                                <td class="border border-gray-300 p-2 text-right font-semibold">Rp. ' . number_format($record->total_harga, 0, ",", ".") . '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                ' : '<div><p class="text-gray-500">Tidak ada perawatan dalam reservasi ini.</p></div>') . '

                ' . ($record->catatan ? '
                <div>
                    <h3 class="font-semibold">Catatan</h3>
                    <p class="bg-gray-50 p-3 rounded border">' . nl2br(e($record->catatan)) . '</p>
                </div>
                ' : '') . '

                ' . ($record->pesanan ? '
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <h3 class="font-semibold text-green-900">Sudah Dikonversi ke Pesanan</h3>
                    <p>Pesanan #' . $record->pesanan->id . ' - ' . $record->pesanan->Metode_Pembayaran . '</p>
                    <a href="' . url('/admin/pesanans/' . $record->pesanan->id) . '" class="text-green-600 hover:text-green-800 font-medium mt-2 inline-block" target="_blank">
                        Lihat Pesanan →
                    </a>
                </div>
                ' : '') . '
            </div>
        ');
    })
    ->modalSubmitAction(false)
    ->modalWidth('4xl'),

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
