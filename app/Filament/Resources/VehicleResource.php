<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('Kode Kendaraan')
                    ->required()
                    ->unique()
                    ->maxLength(50),
                Select::make('vehicle_type_id')
                    ->label('Tipe Kendaraan')
                    ->relationship('vehicleType', 'name')
                    ->required(),
                Select::make('type')
                    ->label('Jenis')
                    ->options([
                        'angkutan_barang' => 'Angkutan Barang',
                        'angkutan_orang' => 'Angkutan Orang',
                    ])
                    ->required(),
                Toggle::make('is_rent')
                    ->label('Sewaan')
                    ->inline(false)
                    ->onColor('success')
                    ->offColor('danger')
                    ->required(),
                FileUpload::make('image')
                    ->label('Foto Kendaraan')
                    ->image()
                    ->imageEditor()
                    ->directory('vehicles')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Kode')->searchable()->sortable(),
                TextColumn::make('vehicleType.name')->label('Tipe Kendaraan'),
                TextColumn::make('type')->label('Jenis')->formatStateUsing(fn($state) => ucfirst(str_replace('_', ' ', $state))),
                IconColumn::make('is_rent')->label('Sewaan')->boolean(),
            ])
            ->filters([
                SelectFilter::make('vehicle_type_id')
                    ->label('Tipe Kendaraan')
                    ->relationship('vehicleType', 'name'),
                SelectFilter::make('type')
                    ->label('Jenis Kendaraan')
                    ->options([
                        'angkutan_barang' => 'Angkutan Barang',
                        'angkutan_orang' => 'Angkutan Orang',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->after(function ($record) {
                        \Illuminate\Support\Facades\Log::info('Aksi edit kendaraan ditekan di Filament', [
                            'user' => auth()->user()?->username,
                            'record_id' => $record->id,
                        ]);
                    }),
                    Tables\Actions\DeleteAction::make()->after(function ($record) {
                        \Illuminate\Support\Facades\Log::info('Aksi hapus kendaraan ditekan di Filament', [
                            'user' => auth()->user()?->username,
                            'record_id' => $record->id,
                        ]);
                    }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->action(function ($record) {
                        \Illuminate\Support\Facades\Log::info('Aksi hapus banyak kendaraan ditekan di Filament', [
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
    public static function getModelLabel(): string
    {
        return 'Kendaraan';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Kendaraan';
    }
}
