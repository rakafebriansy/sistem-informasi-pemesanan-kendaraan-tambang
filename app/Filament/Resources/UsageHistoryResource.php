<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsageHistoryResource\Pages;
use App\Filament\Resources\UsageHistoryResource\RelationManagers;
use App\Models\UsageHistory;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsageHistoryResource extends Resource
{
    private static $statusses = [
        'not_accepted_yet' => 'Belum Diterima',
        'accepted_by_manager' => 'Diterima Manajer',
        'accepted_by_chief' => 'Diterima Kepala',
        'done' => 'Selesai',
        'canceled' => 'Dibatalkan'
    ];
    protected static ?string $model = UsageHistory::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('vehicle_id')
                    ->label('Kendaraan')
                    ->preload()
                    ->relationship('vehicle', 'code')
                    ->searchable()
                    ->required(),
                Select::make('region_id')
                    ->label('Wilayah')
                    ->relationship('region', 'name')
                    ->searchable()
                    ->required(),
                Select::make('renter_id')
                    ->label('Pemesan')
                    ->relationship('renter', 'name')
                    ->searchable()
                    ->required(),
                Select::make('driver_id')
                    ->label('Pengemudi')
                    ->relationship('driver', 'name')
                    ->searchable()
                    ->required(),
                DateTimePicker::make('start_date')
                    ->label('Waktu Mulai')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->label('Waktu Selesai')
                    ->required(),
                TextInput::make('fuel_consumption')
                    ->label('Konsumsi BBM (liter)')
                    ->numeric()
                    ->suffix('liter'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.code')->label('Kendaraan')->searchable(),
                TextColumn::make('region.name')->label('Wilayah'),
                TextColumn::make('renter.name')->label('Pemesan')->searchable(),
                TextColumn::make('end_date')
                    ->label('Status Jatuh Tempo')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        if (!$state)
                            return '-';

                        $now = Carbon::now()->startOfDay();
                        $end = Carbon::parse($state)->startOfDay();

                        if ($end->equalTo($now)) {
                            return 'Jatuh tempo';
                        }

                        if ($end->lessThan($now)) {
                            return 'Kadaluarsa';
                        }

                        $diffInMonths = $now->diffInMonths($end);
                        $tempNow = $now->copy()->addMonths($diffInMonths);
                        $diffInDays = $tempNow->diffInDays($end);

                        $diff = $diffInDays > 0 ? "{$diffInDays} hari" : '';

                        if ($diffInMonths > 1) {
                            $diff = "{$diffInMonths} bulan " . $diff;
                        }

                        return $diff;
                    }),
                SelectColumn::make('status')
                    ->options(function ($record) {
                        if ($record->status === 'not_accepted_yet') {
                            return [
                                'not_accepted_yet' => self::$statusses[$record->status],
                                'canceled' => self::$statusses['canceled'],
                            ];
                        }

                        if ($record->status === 'canceled') {
                            return [
                                'canceled' => self::$statusses[$record->status],
                                'not_accepted_yet' => self::$statusses['not_accepted_yet'],
                            ];
                        }

                        if (
                            $record->status === 'accepted_by_chief' &&
                            $record->end_date < now()
                        ) {
                            return [
                                'accepted_by_chief' => self::$statusses[$record->status],
                                'done' => self::$statusses['done'],
                            ];
                        }

                        if (
                            $record->status === 'done'
                        ) {
                            return [
                                'accepted_by_chief' => self::$statusses['accepted_by_chief'],
                                'done' => self::$statusses[$record->status],
                            ];
                        }

                        return [self::$statusses[$record->status]];
                    })
                    ->disablePlaceholderSelection()
                    ->selectablePlaceholder(false)
                    ->rules(['in:not_accepted_yet,canceled,done'])
                    ->disabled(function ($record) {
                        $options = [];

                        if ($record->status === 'not_accepted_yet') {
                            $options = [
                                'not_accepted_yet',
                                'canceled',
                            ];
                        } else if ($record->status === 'canceled') {
                            $options = [
                                'canceled',
                                'not_accepted_yet',
                            ];
                        } else if (
                            $record->status === 'accepted_by_chief' &&
                            $record->end_date < now()
                        ) {
                            $options = [
                                'accepted_by_chief',
                                'done',
                            ];
                        } else {
                            $options = [$record->status];
                        }

                        return count($options) === 1;
                    }),
                TextColumn::make('fuel_consumption')->sortable()->label('BBM (L)'),
            ])
            ->filters([
                SelectFilter::make('position')
                    ->label('Wilayah')
                    ->relationship('region', 'name')
                    ->searchable(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsageHistories::route('/'),
            'create' => Pages\CreateUsageHistory::route('/create'),
            'edit' => Pages\EditUsageHistory::route('/{record}/edit'),
        ];
    }
    public static function getModelLabel(): string
    {
        return 'Pemakaian';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Pemakaian';
    }
}
