<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StokOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $bulanIni = now()->format('m');
        $tahunIni = now()->format('Y');

        $totalStokAwal = 0;
        $totalStokAkhir = 0;
        $totalMasuk = 0;
        $totalKeluar = 0;

        // Hitung total dari semua produk
        foreach (Produk::all() as $produk) {
            $rekap = $produk->getRekapBulanan($tahunIni, $bulanIni);
            $totalStokAwal += $rekap['stok_awal'];
            $totalStokAkhir += $rekap['stok_akhir'];
            $totalMasuk += $rekap['total_masuk'];
            $totalKeluar += $rekap['total_keluar'];
        }

        return [
            Stat::make('Stok Awal Bulan', number_format($totalStokAwal))
                ->description('Stok awal ' . now()->locale('id')->monthName)
                ->icon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Stok Masuk', number_format($totalMasuk))
                ->description('Total masuk bulan ' . now()->locale('id')->monthName)
                ->icon('heroicon-m-arrow-down-circle')
                ->color('success'),

            Stat::make('Stok Keluar', number_format($totalKeluar))
                ->description('Total keluar bulan ' . now()->locale('id')->monthName)
                ->icon('heroicon-m-arrow-up-circle')
                ->color('danger'),

            Stat::make('Stok Akhir Bulan', number_format($totalStokAkhir))
                ->description('Prediksi stok akhir bulan')
                ->icon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}
