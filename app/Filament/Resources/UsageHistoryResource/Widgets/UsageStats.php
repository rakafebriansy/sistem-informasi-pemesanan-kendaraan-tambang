<?php

namespace App\Filament\Resources\UsageHistoryResource\Widgets;

use App\Models\UsageHistory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsageStats extends BaseWidget
{
    protected function getStats(): array
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        return [
            Stat::make(
                'Kendaraan Sedang Dipakai',
                UsageHistory::query()
                    ->where('status', '!=', 'done')
                    ->where('start_date', '<=', $now)
                    // ->where('end_date', '>=', $now)
                    ->count()
            ),

            Stat::make(
                'Pemesan Bulan Ini',
                UsageHistory::query()
                    ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->distinct('renter_id')
                    ->count('renter_id')
            ),

            Stat::make(
                'Rata-rata Konsumsi BBM',
                number_format(
                    UsageHistory::query()
                        ->whereNotNull('fuel_consumption')
                        ->avg('fuel_consumption'),
                    2
                ) . ' L'
            ),
        ];
    }
}
