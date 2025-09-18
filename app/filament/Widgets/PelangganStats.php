<?php

namespace App\Filament\Widgets;

use App\Models\PesananProduk;
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
        ];
    }

    protected function getDailyIncomeStat(): Stat
    {
        $data = PesananProduk::query()
            ->selectRaw('SUM(qty * harga) as total')
            ->whereDate('created_at', today())
            ->first();

        $yesterday = PesananProduk::query()
            ->selectRaw('SUM(qty * harga) as total')
            ->whereDate('created_at', today()->subDay())
            ->first();

        $growth = $yesterday->total > 0
            ? round((($data->total - $yesterday->total) / $yesterday->total) * 100, 2)
            : 0;

        return Stat::make('Today Income', 'IDR ' . number_format($data->total ?? 0, 0, ',', '.'))
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

        $query = PesananProduk::query()
            ->when($days, function ($q) use ($days) {
            $q->whereDate('created_at', '>=', now()->subDays($days));
            });

            $total = $query->sum(DB::raw('qty * harga'));
            $rangeLabel = $days ? "Last {$days} days" : "All time";

            return Stat::make('Total Income', 'IDR ' . number_format($total ?? 0, 0, ',', '.'))
            ->description($rangeLabel)
            ->icon('heroicon-o-chart-pie')
            ->color('primary')
            ->chart($this->getTotalIncomeChart($days))
->extraAttributes([
'class' => 'bg-gradient-to-br from-purple-50/50 to-purple-100/50 p-4 rounded-lg',
]);
}

    protected function getDailyChart(): array
    {
        return collect(range(6, 0))->map(function ($i) {
            return PesananProduk::whereDate('created_at', today()->subDays($i))
                ->sum(DB::raw('qty * harga'));
        })->toArray();
    }

    protected function getTotalIncomeChart(?int $days = null): array
    {
        $days = $days ?? 30;
        // $days = min($days, 30);

        return collect(range($days - 1, 0))->map(function ($i) {
            return PesananProduk::whereDate('created_at', today()->subDays($i))
                ->sum(DB::raw('qty * harga'));
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
