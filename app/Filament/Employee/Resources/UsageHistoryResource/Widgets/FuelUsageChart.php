<?php

namespace App\Filament\Employee\Resources\UsageHistoryResource\Widgets;

use App\Models\UsageHistory;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class FuelUsageChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Konsumsi Bahan Bakar Tahunan';

    protected function getData(): array
    {
        $usageHistories = UsageHistory::whereYear('start_date', now()->year)
            ->whereIn('status', ['accepted_by_chief', 'done'])
            ->get();

        $fuelPerMonth = collect(range(1, 12))->mapWithKeys(fn($month) => [$month => 0]);

        foreach ($usageHistories as $item) {
            $month = Carbon::parse($item->start_date)->month;
            $fuelPerMonth[$month] += (float) $item->fuel_consumption;
        }

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'datasets' => [
                [
                    'label' => 'Total BBM (liter)',
                    'data' => $fuelPerMonth->values()->toArray(),
                    'fill' => true,
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'tension' => 0.3,
                ]
            ]
        ];
    }
    protected function getType(): string
    {
        return 'line';
    }
}
