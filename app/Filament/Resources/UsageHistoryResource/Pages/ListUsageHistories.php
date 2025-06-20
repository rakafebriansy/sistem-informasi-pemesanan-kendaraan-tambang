<?php

namespace App\Filament\Resources\UsageHistoryResource\Pages;

use App\Filament\Resources\UsageHistoryResource;
use App\Filament\Resources\UsageHistoryResource\Widgets\UsageStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListUsageHistories extends ListRecords
{
    protected static string $resource = UsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->action(function ($record) {
                \Illuminate\Support\Facades\Log::info('Aksi tambah pemakaian ditekan di Filament', [
                    'user' => auth()->user()?->username,
                    'record_id' => $record->id,
                ]);
            }),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            UsageStats::class
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'not_accepted_yet' => Tab::make()
                ->label('Belum Diterima')
                ->query(fn($query) => $query->where('status', 'not_accepted_yet')),
            'accepted_by_manager' => Tab::make()
                ->label('Diterima manager')
                ->
                query(fn($query) => $query->where('status', 'accepted_by_manager')),
            'accepted_by_chief' => Tab::make()
                ->label('Diterima chief')
                ->query(fn($query) => $query->where('status', 'accepted_by_chief')),
            'done' => Tab::make()
                ->label('Selesai')
                ->query(fn($query) => $query->where('status', 'done')),
            'canceled' => Tab::make()
                ->label('Dibatalkan')
                ->query(fn($query) => $query->where('status', 'canceled')),
        ];
    }
}
