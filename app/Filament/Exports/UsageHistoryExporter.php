<?php

namespace App\Filament\Exports;

use App\Models\UsageHistory;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UsageHistoryExporter extends Exporter
{
    protected static ?string $model = UsageHistory::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('vehicle_id'),
            ExportColumn::make('region_id'),
            ExportColumn::make('renter_id'),
            ExportColumn::make('driver_id'),
            ExportColumn::make('start_date'),
            ExportColumn::make('end_date'),
            ExportColumn::make('fuel_consumption'),
            ExportColumn::make('status'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your usage history export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
