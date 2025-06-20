<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\ExportResource\Widgets\MyExportsWidget;
use App\Filament\Employee\Resources\UsageHistoryResource\Pages;
use App\Filament\Employee\Resources\UsageHistoryResource\RelationManagers;
use App\Filament\Exports\UsageHistoryExporter;
use App\Models\UsageHistory;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
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
        'accepted_by_manager' => 'Diterima manager',
        'accepted_by_chief' => 'Diterima Kepala',
        'done' => 'Selesai',
        'canceled' => 'Dibatalkan'
    ];
    protected static ?string $model = UsageHistory::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

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
                TextColumn::make('vehicle.code')->label('Kendaraan')->searchable()->sortable(),
                TextColumn::make('region.name')->label('Wilayah')->sortable(),
                TextColumn::make('renter.name')->label('Pemesan')->searchable()->sortable(),
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
                        $user = Filament::auth()->user();
                        if (($record->status === 'not_accepted_yet' || $record->status === 'accepted_by_manager') && $user->position == 'manager') {
                            return [
                                'not_accepted_yet' => self::$statusses['not_accepted_yet'],
                                'accepted_by_manager' => self::$statusses['accepted_by_manager'],
                            ];
                        }

                        if (($record->status === 'accepted_by_manager' || $record->status === 'accepted_by_chief') && $user->position == 'chief') {
                            return [
                                'accepted_by_manager' => self::$statusses['accepted_by_manager'],
                                'accepted_by_chief' => self::$statusses['accepted_by_chief'],
                            ];
                        }

                        return [self::$statusses[$record->status]];
                    })
                    ->disablePlaceholderSelection()
                    ->selectablePlaceholder(false)
                    ->rules(['in:not_accepted_yet,accepted_by_manager,accepted_by_chief'])
                    ->disabled(function ($record): bool {
                        $user = Filament::auth()->user();

                        return match ($record->status) {
                            'not_accepted_yet' => $user->position !== 'manager',
                            'accepted_by_manager' => !in_array($user->position, ['manager', 'kepala']),
                            'accepted_by_chief' => $user->position !== 'kepala',
                            default => true,
                        };
                    })
                ,
                TextColumn::make('fuel_consumption')->sortable()->label('BBM (L)'),
            ])
            ->filters([
                SelectFilter::make('position')
                    ->label('Wilayah')
                    ->relationship('region', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exporter(UsageHistoryExporter::class)->after(function (array $data, $livewire) {
                        $livewire->redirect('/employee/usage-histories');
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
