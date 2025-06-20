<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleTypeResource\Pages;
use App\Filament\Resources\VehicleTypeResource\RelationManagers;
use App\Models\VehicleType;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleTypeResource extends Resource
{
    protected static ?string $model = VehicleType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Nama Tipe Kendaraan')
                        ->required()
                        ->unique()
                        ->maxLength(255),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->label('Nama Tipe Kendaraan'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->after(function ($record) {
                    \Illuminate\Support\Facades\Log::info('Aksi edit jenis kendaraan ditekan di Filament', [
                        'user' => auth()->user()?->username,
                        'record_id' => $record->id,
                    ]);
                }),
                Tables\Actions\DeleteAction::make()->after(function ($record) {
                    \Illuminate\Support\Facades\Log::info('Aksi hapus jenis kendaraan ditekan di Filament', [
                        'user' => auth()->user()?->username,
                        'record_id' => $record->id,
                    ]);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->action(function ($record) {
                        \Illuminate\Support\Facades\Log::info('Aksi hapus banyak jenis kendaraan ditekan di Filament', [
                            'user' => auth()->user()?->username,
                            'record_id' => $record->id,
                        ]);
                    }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicleTypes::route('/'),
            'create' => Pages\CreateVehicleType::route('/create'),
            'edit' => Pages\EditVehicleType::route('/{record}/edit'),
        ];
    }
    public static function getModelLabel(): string
    {
        return 'Tipe Kendaraan';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Tipe Kendaraan';
    }
}
