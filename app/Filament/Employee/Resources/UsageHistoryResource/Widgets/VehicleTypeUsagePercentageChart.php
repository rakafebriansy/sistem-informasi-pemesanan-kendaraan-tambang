<?php

namespace App\Filament\Employee\Resources\UsageHistoryResource\Widgets;

use App\Models\UsageHistory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class VehicleTypeUsagePercentageChart extends ChartWidget
{
    protected static ?string $heading = 'Sebaran Jenis Kendaraan';

    protected function getData(): array
    {
        $usageHistories = UsageHistory::with('vehicle')
            ->where(function ($query) {
                $query->where('status', 'accepted_by_chief')
                    ->orWhere('status', 'done');
            })
            ->get();

        $grouped = (object) $usageHistories->groupBy(
            fn($item) => $item->vehicle->type
        )->map->count();

        $backgroundColors = [];
        for ($i = 0; $i < count($grouped); $i++) {
            $backgroundColors[] = 'rgba(' . rand(50, 200) . ',' . rand(50, 200) . ',' . rand(50, 200) . ', 0.7)';
        }

        return [
            'labels' => $grouped->keys()->map(fn($label) => Str::headline($label))->toArray(),
            'datasets' => [
                [
                    'data' => $grouped->values()->toArray(),
                    'backgroundColor' => $backgroundColors,
                    'hoverOffset' => 4,
                    'labels' => 'Jenis Kendaraan',
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
