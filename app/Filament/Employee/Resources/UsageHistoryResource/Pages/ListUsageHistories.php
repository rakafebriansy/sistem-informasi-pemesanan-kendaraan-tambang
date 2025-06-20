<?php

namespace App\Filament\Employee\Resources\UsageHistoryResource\Pages;

use App\Filament\Employee\Resources\UsageHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsageHistories extends ListRecords
{
    protected static string $resource = UsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
