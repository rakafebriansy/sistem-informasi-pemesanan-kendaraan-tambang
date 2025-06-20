<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicle extends EditRecord
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->action(function ($record) {
                \Illuminate\Support\Facades\Log::info('Aksi edit kendaraan ditekan di Filament', [
                    'user' => auth()->user()?->username,
                    'record_id' => $record->id,
                ]);
            }),
        ];
    }
}
