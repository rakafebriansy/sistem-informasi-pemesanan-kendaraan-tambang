<?php

namespace App\Filament\Employee\Resources\UsageHistoryResource\Widgets;

use App\Models\UsageHistory;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MonthlyVehicleBorrowingChart extends ChartWidget
{
    protected static ?string $heading = 'Peminjaman Kendaraan per Bulan';

    protected function getData(): array
    {
        $usageHistories = UsageHistory::with('vehicle')
            ->where(function ($query) {
                $query->where('status', 'accepted_by_chief')
                    ->orWhere('status', 'done');
            })
            ->where('start_date', '>=', Carbon::now()->subDays(30))
            ->get();

        $grouped = (object)$usageHistories
            ->groupBy(fn($item) => $item->vehicle->vehicleType->name)
            ->map->count();

        $datasets = $grouped->map(
            fn($count, $vehicleType) => [
                'label' => 'Tipe ' . $vehicleType,
                'data' => [$count],
                'backgroundColor' => 'rgba(' . rand(50, 200) . ',' . rand(50, 200) . ',' . rand(50, 200) . ', 0.7)',
                'borderWidth' => 0,
            ]
        )->values()->toArray();

        return [
            'datasets' => $datasets,
            'labels' => ['30 Hari Terakhir'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
