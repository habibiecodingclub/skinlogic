<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action untuk buat produk baru
            Actions\CreateAction::make()
                ->label('Tambah Produk Baru')
                ->icon('heroicon-o-plus-circle'),

            // Action untuk tambah stok manual
            Actions\Action::make('tambahStokManual')
                ->label('Tambah Stok')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('produk_id')
                        ->label('Pilih Produk')
                        ->options(\App\Models\Produk::all()->pluck('Nama', 'id'))
                        ->required()
                        ->searchable()
                        ->placeholder('Pilih produk')
                        ->native(false)
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) {
                                $produk = \App\Models\Produk::find($state);
                                if ($produk) {
                                    $set('stok_sekarang', $produk->Stok);
                                }
                            }
                        }),

                    Forms\Components\TextInput::make('stok_sekarang')
                        ->label('Stok Sekarang')
                        ->numeric()
                        ->readOnly()
                        ->dehydrated(false)
                        ->default(0),

                    Forms\Components\TextInput::make('jumlah')
                        ->label('Jumlah Stok yang Ditambah')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->default(1)
                        ->helperText('Masukkan jumlah stok yang akan ditambahkan')
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            $stokSekarang = $get('stok_sekarang') ?? 0;
                            $jumlah = $state ?? 0;
                            $set('stok_setelah', $stokSekarang + $jumlah);
                        }),

                    Forms\Components\TextInput::make('stok_setelah')
                        ->label('Stok Setelah Penambahan')
                        ->numeric()
                        ->readOnly()
                        ->dehydrated(false)
                        ->default(0),

                    Forms\Components\TextInput::make('keterangan')
                        ->label('Keterangan')
                        ->required()
                        ->default('Penambahan stok manual')
                        ->maxLength(255)
                        ->helperText('Contoh: Restok dari supplier, koreksi stok, dll.'),

                    Forms\Components\DatePicker::make('tanggal')
                        ->label('Tanggal Transaksi')
                        ->maxDate(now())
                        ->default(now())
                        ->helperText('Tanggal ketika stok ditambahkan'),
                ])
                ->action(function (array $data) {
    // DEBUG: Log data yang diterima
    Log::info("Action dipanggil dengan data:", $data);

    DB::transaction(function () use ($data) {
        try {
            $produk = \App\Models\Produk::find($data['produk_id']);

            if (!$produk) {
                throw new \Exception('Produk tidak ditemukan');
            }

            Log::info("Produk ditemukan: {$produk->Nama}, Stok: {$produk->Stok}");

            // Simpan stok sebelum perubahan
            $stokSebelum = $produk->Stok;

            // **PASTIKAN HANYA SATU KALI PANGGIL**
            $produk->tambahStok(
                $data['jumlah'],
                $data['keterangan'],
                $data['tanggal']
            );

            // **JANGAN refresh() karena bisa baca data stale**
            // $produk->refresh();

            Log::info("Setelah tambahStok - Stok: {$produk->Stok}");

            // Notifikasi sukses
            Notification::make()
                ->title('Stok Berhasil Ditambahkan! 🎉')
                ->body(
                    "Produk: {$produk->Nama}\n" .
                    "Stok sebelum: {$stokSebelum} unit\n" .
                    "Jumlah ditambah: {$data['jumlah']} unit\n" .
                    "Stok sekarang: {$produk->Stok} unit"
                )
                ->success()
                ->send();

        } catch (\Exception $e) {
            Log::error("Error tambah stok: " . $e->getMessage());
            Notification::make()
                ->title('Gagal Menambah Stok ❌')
                ->body($e->getMessage())
                ->danger()
                ->send();
            throw $e;
        }
    });
})
                ->modalHeading('Tambah Stok Manual')
                ->modalDescription('Tambahkan stok untuk produk tertentu')
                ->modalSubmitActionLabel('Tambah Stok')
                ->modalCancelActionLabel('Batal')
                ->modalWidth('lg'),

            // Action untuk kurangi stok manual
        //     Actions\Action::make('kurangiStokManual')
        //         ->label('Kurangi Stok')
        //         ->icon('heroicon-o-minus')
        //         ->color('danger')
        //         ->form([
        //             Forms\Components\Select::make('produk_id')
        //                 ->label('Pilih Produk')
        //                 ->options(\App\Models\Produk::all()->pluck('Nama', 'id'))
        //                 ->required()
        //                 ->searchable()
        //                 ->placeholder('Pilih produk')
        //                 ->native(false)
        //                 ->live()
        //                 ->afterStateUpdated(function ($state, Forms\Set $set) {
        //                     if ($state) {
        //                         $produk = \App\Models\Produk::find($state);
        //                         if ($produk) {
        //                             $set('stok_sekarang', $produk->Stok);
        //                         }
        //                     }
        //                 }),

        //             Forms\Components\TextInput::make('stok_sekarang')
        //                 ->label('Stok Sekarang')
        //                 ->numeric()
        //                 ->readOnly()
        //                 ->dehydrated(false)
        //                 ->default(0),

        //             Forms\Components\TextInput::make('jumlah')
        //                 ->label('Jumlah Stok yang Dikurangi')
        //                 ->numeric()
        //                 ->required()
        //                 ->minValue(1)
        //                 ->default(1)
        //                 ->helperText('Masukkan jumlah stok yang akan dikurangi')
        //                 ->live()
        //                 ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
        //                     $stokSekarang = $get('stok_sekarang') ?? 0;
        //                     $jumlah = $state ?? 0;
        //                     $stokSetelah = $stokSekarang - $jumlah;
        //                     $set('stok_setelah', max(0, $stokSetelah));
        //                 })
        //                 ->rule(function ($state, Forms\Get $get) {
        //                     $produkId = $get('produk_id');
        //                     $jumlah = $state ?? 0;

        //                     if ($produkId && $jumlah > 0) {
        //                         $produk = \App\Models\Produk::find($produkId);
        //                         if ($produk && $jumlah > $produk->Stok) {
        //                             return false;
        //                         }
        //                     }
        //                     return true;
        //                 }),

        //             Forms\Components\TextInput::make('stok_setelah')
        //                 ->label('Stok Setelah Pengurangan')
        //                 ->numeric()
        //                 ->readOnly()
        //                 ->dehydrated(false)
        //                 ->default(0),

        //             Forms\Components\TextInput::make('keterangan')
        //                 ->label('Keterangan')
        //                 ->required()
        //                 ->default('Pengurangan stok manual')
        //                 ->maxLength(255)
        //                 ->helperText('Contoh: Penjualan, sample, rusak, dll.'),

        //             Forms\Components\DatePicker::make('tanggal')
        //                 ->label('Tanggal Transaksi')
        //                 ->default(now())
        //                 ->helperText('Tanggal ketika stok dikurangi'),
        //         ])
        //         ->action(function (array $data) {
        //             DB::transaction(function () use ($data) {
        //                 try {
        //                     $produk = \App\Models\Produk::find($data['produk_id']);

        //                     if (!$produk) {
        //                         throw new \Exception('Produk tidak ditemukan');
        //                     }

        //                     // Cek stok cukup
        //                     if ($produk->Stok < $data['jumlah']) {
        //                         throw new \Exception("Stok tidak cukup! Stok tersedia: {$produk->Stok} unit");
        //                     }

        //                     // Simpan stok sebelum perubahan
        //                     $stokSebelum = $produk->Stok;

        //                     // Panggil method kurangiStok dengan tanggal
        //                     $produk->kurangiStok(
        //                         $data['jumlah'],
        //                         $data['keterangan'],
        //                         $data['tanggal']
        //                     );

        //                     // Refresh untuk dapat data terbaru
        //                     $produk->refresh();

        //                     // Notifikasi sukses
        //                     Notification::make()
        //                         ->title('Stok Berhasil Dikurangi! ✅')
        //                         ->body(
        //                             "**Produk:** {$produk->Nama}\n" .
        //                             "**Stok sebelum:** {$stokSebelum} unit\n" .
        //                             "**Jumlah dikurangi:** {$data['jumlah']} unit\n" .
        //                             "**Stok sekarang:** {$produk->Stok} unit\n" .
        //                             "**Keterangan:** {$data['keterangan']}"
        //                         )
        //                         ->success()
        //                         ->persistent()
        //                         ->send();

        //                 } catch (\Exception $e) {
        //                     Notification::make()
        //                         ->title('Gagal Mengurangi Stok ❌')
        //                         ->body($e->getMessage())
        //                         ->danger()
        //                         ->persistent()
        //                         ->send();
        //                     throw $e;
        //                 }
        //             });
        //         })
        //         ->modalHeading('Kurangi Stok Manual')
        //         ->modalDescription('Kurangi stok untuk produk tertentu')
        //         ->modalSubmitActionLabel('Kurangi Stok')
        //         ->modalCancelActionLabel('Batal')
        //         ->modalWidth('lg'),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }
}
