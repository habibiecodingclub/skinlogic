<?php

namespace App\Filament\Resources\LaporanStokResource\Pages;

use App\Filament\Resources\LaporanStokResource;
use App\Models\Produk;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Request;

class DetailLaporanStok extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = LaporanStokResource::class;
    protected static string $view = 'filament.resources.laporan-stok-resource.pages.detail-laporan-stok';

    public Produk $record;
    public string $bulanTahun;

    public function mount($record): void
    {
        $this->record = Produk::find($record);
        $this->bulanTahun = Request::input('bulan_tahun') ?: now()->format('m-Y');

        if (!$this->record) {
            abort(404, 'Produk tidak ditemukan');
        }
    }

    public function getTitle(): string|Htmlable
    {
        [$bulan, $tahun] = explode('-', $this->bulanTahun);
        $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->monthName;

        return "Detail Laporan Stok - {$this->record->Nama}";
    }

    public function table(Table $table): Table
    {
        [$bulan, $tahun] = explode('-', $this->bulanTahun);

        return $table
            ->query(
                $this->record->stokMovements()
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->orderBy('tanggal')
                    ->orderBy('created_at')
            )
            ->columns([
                TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Tanggal'),

                TextColumn::make('tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'masuk' ? 'Stok Masuk' : 'Stok Keluar')
                    ->color(fn (string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'danger',
                    })
                    ->label('Jenis'),

                TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable()
                    ->label('Jumlah'),

                TextColumn::make('keterangan')
                    ->searchable()
                    ->label('Keterangan'),

                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Waktu Input')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }

    public function getRekapData()
    {
        [$bulan, $tahun] = explode('-', $this->bulanTahun);
        return $this->record->getRekapBulanan($tahun, $bulan);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label('Kembali ke Laporan')
                ->icon('heroicon-m-arrow-left')
                ->url(LaporanStokResource::getUrl('index', ['bulan_tahun' => $this->bulanTahun])),
        ];
    }

    // Tambahkan method ini untuk handle route
    public static function getRoutePath(): string
    {
        return '/{record}/detail';
    }
}
