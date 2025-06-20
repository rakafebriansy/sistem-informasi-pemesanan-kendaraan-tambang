<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionResource\Pages;
use App\Filament\Resources\RegionResource\RelationManagers;
use App\Models\Region;
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

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Nama Wilayah')
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
                TextColumn::make('name')->searchable()->label('Nama Wilayah'),
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
                    \Illuminate\Support\Facades\Log::info('Aksi edit wilayah ditekan di Filament', [
                        'user' => auth()->user()?->username,
                        'record_id' => $record->id,
                    ]);
                }),
                Tables\Actions\DeleteAction::make()->after(function ($record) {
                    \Illuminate\Support\Facades\Log::info('Aksi hapus wilayah ditekan di Filament', [
                        'user' => auth()->user()?->username,
                        'record_id' => $record->id,
                    ]);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->action(function ($record) {
                        \Illuminate\Support\Facades\Log::info('Aksi hapus banyak wilayah ditekan di Filament', [
                            'user' => auth()->user()?->username,
                            'record_id' => $record->id,
                        ]);
                    })
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
            'index' => Pages\ListRegions::route('/'),
            'create' => Pages\CreateRegion::route('/create'),
            'edit' => Pages\EditRegion::route('/{record}/edit'),
        ];
    }
    public static function getModelLabel(): string
    {
        return 'Wilayah';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Wilayah';
    }
}
