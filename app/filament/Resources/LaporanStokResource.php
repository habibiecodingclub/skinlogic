<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanStokResource\Pages;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Request;

class LaporanStokResource extends Resource
{
    protected static ?string $model = Produk::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Stok';
    protected static ?string $pluralModelLabel = "Laporan Stok";
    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('bulan_tahun')
                    ->label('Pilih Bulan')
                    ->displayFormat('M Y')
                    ->format('m-Y')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('stok_awal')
                    ->label('Stok Awal')
                    ->numeric()
                    ->getStateUsing(function ($record) {
                        $bulanTahun = Request::input('bulan_tahun') ?: now()->format('m-Y');
                        [$bulan, $tahun] = explode('-', $bulanTahun);
                        return $record->getStokAwalBulan($tahun, $bulan);
                    }),

                Tables\Columns\TextColumn::make('stok_masuk')
                    ->label('Masuk')
                    ->numeric()
                    ->getStateUsing(function ($record) {
                        $bulanTahun = Request::input('bulan_tahun') ?: now()->format('m-Y');
                        [$bulan, $tahun] = explode('-', $bulanTahun);
                        $rekap = $record->getRekapBulanan($tahun, $bulan);
                        return $rekap['total_masuk'];
                    })
                    ->color('success'),

                Tables\Columns\TextColumn::make('stok_keluar')
                    ->label('Keluar')
                    ->numeric()
                    ->getStateUsing(function ($record) {
                        $bulanTahun = Request::input('bulan_tahun') ?: now()->format('m-Y');
                        [$bulan, $tahun] = explode('-', $bulanTahun);
                        $rekap = $record->getRekapBulanan($tahun, $bulan);
                        return $rekap['total_keluar'];
                    })
                    ->color('danger'),

                Tables\Columns\TextColumn::make('stok_akhir')
                    ->label('Stok Akhir')
                    ->numeric()
                    ->getStateUsing(function ($record) {
                        $bulanTahun = Request::input('bulan_tahun') ?: now()->format('m-Y');
                        [$bulan, $tahun] = explode('-', $bulanTahun);
                        return $record->getStokAkhirBulan($tahun, $bulan);
                    })
                    ->color('primary')
                    ->weight('bold'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('filterBulan')
                    ->label('Filter Bulan')
                    ->icon('heroicon-m-calendar')
                    ->form([
                        Forms\Components\Select::make('bulan_tahun')
                            ->label('Pilih Bulan/Tahun')
                            ->options(function () {
                                $options = [];
                                $start = now()->subMonths(12);
                                $end = now();

                                while ($start <= $end) {
                                    $options[$start->format('m-Y')] = $start->locale('id')->monthName . ' ' . $start->year;
                                    $start->addMonth();
                                }

                                return $options;
                            })
                            ->default(now()->format('m-Y'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return redirect()->to(LaporanStokResource::getUrl('index', $data));
                    }),
            ])
            ->actions([
                // SEMENTARA: Gunakan ViewAction bawaan Filament
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail')
                    ->modalHeading(fn (Produk $record) => "Detail Stok - {$record->Nama}")
                    ->modalContent(function (Produk $record) {
                        $bulanTahun = Request::input('bulan_tahun') ?: now()->format('m-Y');
                        [$bulan, $tahun] = explode('-', $bulanTahun);
                        $rekap = $record->getRekapBulanan($tahun, $bulan);
                        $movements = $record->stokMovements()
                            ->whereYear('tanggal', $tahun)
                            ->whereMonth('tanggal', $bulan)
                            ->orderBy('tanggal')
                            ->orderBy('created_at')
                            ->get();

                        return view('filament.resources.laporan-stok-resource.components.detail-modal', [
                            'record' => $record,
                            'rekap' => $rekap,
                            'movements' => $movements,
                            'bulanTahun' => $bulanTahun,
                        ]);
                    })
                    ->modalWidth('4xl'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanStoks::route('/'),
            // HAPUS detail page untuk sementara
        ];
    }
}
