<?php

namespace App\Filament\Employee\Resources\UsageHistoryResource\Widgets;

use App\Models\UsageHistory;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class DailyBorrowingTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Peminjaman Tahunan';

    protected function getData(): array
    {
        $usageHistories = UsageHistory::whereYear('start_date', now()->year)
            ->whereIn('status', ['accepted_by_chief', 'done'])
            ->get();

        $monthlyCounts = collect(range(1, 12))->mapWithKeys(fn($month) => [$month => 0]);

        foreach ($usageHistories as $item) {
            $month = Carbon::parse($item->start_date)->month;
            $monthlyCounts[$month] += 1;
        }
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'datasets' => [
                [
                    'data' => $monthlyCounts->values()->toArray(),
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
