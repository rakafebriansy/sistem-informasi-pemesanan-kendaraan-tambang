<?php

namespace App\Filament\Resources\UsageHistoryResource\Pages;

use App\Filament\Resources\UsageHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsageHistory extends EditRecord
{
    protected static string $resource = UsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->action(function ($record) {
                \Illuminate\Support\Facades\Log::info('Aksi hapus pemakaian ditekan di Filament', [
                    'user' => auth()->user()?->username,
                    'record_id' => $record->id,
                ]);
            }),
        ];
    }
}
