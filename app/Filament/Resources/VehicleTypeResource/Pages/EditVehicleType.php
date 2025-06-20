<?php

namespace App\Filament\Resources\VehicleTypeResource\Pages;

use App\Filament\Resources\VehicleTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleType extends EditRecord
{
    protected static string $resource = VehicleTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->after(function ($record) {
                \Illuminate\Support\Facades\Log::info('Aksi hapus jenis kendaraan ditekan di Filament', [
                    'user' => auth()->user()?->username,
                    'record_id' => $record->id,
                ]);
            }),
        ];
    }
}
