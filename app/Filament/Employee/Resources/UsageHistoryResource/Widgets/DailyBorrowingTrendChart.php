<?php

namespace App\Filament\Employee\Resources\UsageHistoryResource\Widgets;

use App\Models\UsageHistory;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DailyBorrowingTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Pemakaian Tahunan';

    protected function getData(): array
    {
        $data = DB::table('usage_histories')
            ->join('vehicles', 'usage_histories.vehicle_id', '=', 'vehicles.id')
            ->join('vehicle_types', 'vehicles.vehicle_type_id', '=', 'vehicle_types.id')
            ->whereYear('start_date', now()->year)
            ->whereIn('status', ['accepted_by_chief', 'done'])
            ->selectRaw('vehicle_types.name as vehicle_type, MONTH(start_date) as month, COUNT(*) as total')
            ->groupBy('vehicle_type', 'month')
            ->get();

        $vehicleTypes = $data->pluck('vehicle_type')->unique();

        $datasets = $vehicleTypes->map(function ($type) use ($data) {
            $monthlyData = collect(range(1, 12))->mapWithKeys(fn($m) => [$m => 0]);

            foreach ($data->where('vehicle_type', $type) as $row) {
                $monthlyData[$row->month] = $row->total;
            }

            return [
                'label' => $type,
                'data' => $monthlyData->values()->toArray(),
                'fill' => false,
                'borderColor' => 'rgba(' . rand(50, 200) . ',' . rand(50, 200) . ',' . rand(50, 200) . ', 1)',
                'tension' => 0.3,
            ];
        })->values()->toArray();

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
