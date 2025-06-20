<?php

namespace App\Filament\Employee\Resources\UsageHistoryResource\Widgets;

use Filament\Widgets\ChartWidget;

class DailyBorrowingTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
