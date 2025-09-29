<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use App\Models\PesananProduk;
use App\Models\PesananPerawatan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PelangganStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 1;
    protected static string $view = 'filament.widgets.pelanggan-stats';

    public string $timeRange = '7';

    protected function getStats(): array
    {
        return [
            $this->getDailyIncomeStat(),
            $this->getTotalIncomeStat(),
            $this->getIncomeBreakdownStat(),
        ];
    }

    protected function getDailyIncomeStat(): Stat
    {
        // Income dari produk hari ini
        $produkToday = PesananProduk::query()
            ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
            ->where('pesanans.status', 'Berhasil')
            ->whereDate('pesanan_produk.created_at', today())
            ->selectRaw('SUM(pesanan_produk.qty * pesanan_produk.harga) as total')
            ->first();

        // Income dari treatment hari ini
        $treatmentToday = PesananPerawatan::query()
            ->join('pesanans', 'pesanan_perawatan.pesanan_id', '=', 'pesanans.id')
            ->where('pesanans.status', 'Berhasil')
            ->whereDate('pesanan_perawatan.created_at', today())
            ->selectRaw('SUM(pesanan_perawatan.qty * pesanan_perawatan.harga) as total')
            ->first();

        $totalToday = ($produkToday->total ?? 0) + ($treatmentToday->total ?? 0);

        // Income kemarin untuk growth calculation
        $produkYesterday = PesananProduk::query()
            ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
            ->where('pesanans.status', 'Berhasil')
            ->whereDate('pesanan_produk.created_at', today()->subDay())
            ->selectRaw('SUM(pesanan_produk.qty * pesanan_produk.harga) as total')
            ->first();

        $treatmentYesterday = PesananPerawatan::query()
            ->join('pesanans', 'pesanan_perawatan.pesanan_id', '=', 'pesanans.id')
            ->where('pesanans.status', 'Berhasil')
            ->whereDate('pesanan_perawatan.created_at', today()->subDay())
            ->selectRaw('SUM(pesanan_perawatan.qty * pesanan_perawatan.harga) as total')
            ->first();

        $totalYesterday = ($produkYesterday->total ?? 0) + ($treatmentYesterday->total ?? 0);

        $growth = $totalYesterday > 0
            ? round((($totalToday - $totalYesterday) / $totalYesterday) * 100, 2)
            : ($totalToday > 0 ? 100 : 0);

        return Stat::make('Today Income', 'IDR ' . number_format($totalToday, 0, ',', '.'))
            ->description($growth >= 0 ? "↑ {$growth}% vs yesterday" : "↓ {$growth}% vs yesterday")
            ->icon('heroicon-o-arrow-trending-up')
            ->color($growth >= 0 ? 'success' : 'danger')
            ->chart($this->getDailyChart())
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-indigo-50/50 to-indigo-100/50 p-4 rounded-lg',
            ]);
    }

    protected function getTotalIncomeStat(): Stat
    {
        $days = match ($this->timeRange) {
            '7' => 7,
            '30' => 30,
            '90' => 90,
            'all' => null,
            default => 7,
        };

        // Income dari produk
        $produkQuery = PesananProduk::query()
            ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
            ->where('pesanans.status', 'Berhasil')
            ->when($days, function ($q) use ($days) {
                $q->whereDate('pesanan_produk.created_at', '>=', now()->subDays($days));
            });

        $produkTotal = $produkQuery->sum(DB::raw('pesanan_produk.qty * pesanan_produk.harga'));

        // Income dari treatment
        $treatmentQuery = PesananPerawatan::query()
            ->join('pesanans', 'pesanan_perawatan.pesanan_id', '=', 'pesanans.id')
            ->where('pesanans.status', 'Berhasil')
            ->when($days, function ($q) use ($days) {
                $q->whereDate('pesanan_perawatan.created_at', '>=', now()->subDays($days));
            });

        $treatmentTotal = $treatmentQuery->sum(DB::raw('pesanan_perawatan.qty * pesanan_perawatan.harga'));

        $totalIncome = $produkTotal + $treatmentTotal;
        $rangeLabel = $days ? "Last {$days} days" : "All time";

        return Stat::make('Total Income', 'IDR ' . number_format($totalIncome, 0, ',', '.'))
            ->description($rangeLabel)
            ->icon('heroicon-o-chart-pie')
            ->color('primary')
            ->chart($this->getTotalIncomeChart($days))
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-purple-50/50 to-purple-100/50 p-4 rounded-lg',
            ]);
    }

    protected function getIncomeBreakdownStat(): Stat
    {
        $days = match ($this->timeRange) {
            '7' => 7,
            '30' => 30,
            '90' => 90,
            'all' => null,
            default => 7,
        };

        // Income breakdown
        $produkQuery = PesananProduk::query()
            ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
            ->where('pesanans.status', 'Berhasil')
            ->when($days, function ($q) use ($days) {
                $q->whereDate('pesanan_produk.created_at', '>=', now()->subDays($days));
            });

        $produkTotal = $produkQuery->sum(DB::raw('pesanan_produk.qty * pesanan_produk.harga'));

        $treatmentQuery = PesananPerawatan::query()
            ->join('pesanans', 'pesanan_perawatan.pesanan_id', '=', 'pesanans.id')
            ->where('pesanans.status', 'Berhasil')
            ->when($days, function ($q) use ($days) {
                $q->whereDate('pesanan_perawatan.created_at', '>=', now()->subDays($days));
            });

        $treatmentTotal = $treatmentQuery->sum(DB::raw('pesanan_perawatan.qty * pesanan_perawatan.harga'));

        $totalIncome = $produkTotal + $treatmentTotal;

        if ($totalIncome > 0) {
            $produkPercentage = round(($produkTotal / $totalIncome) * 100, 1);
            $treatmentPercentage = round(($treatmentTotal / $totalIncome) * 100, 1);
            $description = "Products: {$produkPercentage}% • Treatments: {$treatmentPercentage}%";
        } else {
            $description = "No income data";
        }

        return Stat::make('Income Breakdown', 'IDR ' . number_format($totalIncome, 0, ',', '.'))
            ->description($description)
            ->icon('heroicon-o-puzzle-piece')
            ->color('info')
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-blue-50/50 to-blue-100/50 p-4 rounded-lg',
            ]);
    }

    protected function getDailyChart(): array
    {
        return collect(range(6, 0))->map(function ($i) {
            $date = today()->subDays($i);

            $produkIncome = PesananProduk::query()
                ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
                ->where('pesanans.status', 'Berhasil')
                ->whereDate('pesanan_produk.created_at', $date)
                ->sum(DB::raw('pesanan_produk.qty * pesanan_produk.harga'));

            $treatmentIncome = PesananPerawatan::query()
                ->join('pesanans', 'pesanan_perawatan.pesanan_id', '=', 'pesanans.id')
                ->where('pesanans.status', 'Berhasil')
                ->whereDate('pesanan_perawatan.created_at', $date)
                ->sum(DB::raw('pesanan_perawatan.qty * pesanan_perawatan.harga'));

            return $produkIncome + $treatmentIncome;
        })->toArray();
    }

    protected function getTotalIncomeChart(?int $days = null): array
    {
        $days = $days ?? 30;

        return collect(range($days - 1, 0))->map(function ($i) {
            $date = today()->subDays($i);

            $produkIncome = PesananProduk::query()
                ->join('pesanans', 'pesanan_produk.pesanan_id', '=', 'pesanans.id')
                ->where('pesanans.status', 'Berhasil')
                ->whereDate('pesanan_produk.created_at', $date)
                ->sum(DB::raw('pesanan_produk.qty * pesanan_produk.harga'));

            $treatmentIncome = PesananPerawatan::query()
                ->join('pesanans', 'pesanan_perawatan.pesanan_id', '=', 'pesanans.id')
                ->where('pesanans.status', 'Berhasil')
                ->whereDate('pesanan_perawatan.created_at', $date)
                ->sum(DB::raw('pesanan_perawatan.qty * pesanan_perawatan.harga'));

            return $produkIncome + $treatmentIncome;
        })->toArray();
    }

    public function getTimeRangeOptions(): array
    {
        return [
            '7' => '1 Week',
            '30' => '1 Month',
            '90' => '3 Months',
            'all' => 'All Time',
        ];
    }

    public function setTimeRange(string $range): void
    {
        $this->timeRange = $range;
    }
}
