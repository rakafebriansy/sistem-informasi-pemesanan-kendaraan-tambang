<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->action(function ($record) {
                \Illuminate\Support\Facades\Log::info('Aksi tambah pegawai ditekan di Filament', [
                    'user' => auth()->user()?->username,
                    'record_id' => $record->id,
                ]);
            }),
        ];
    }
}
