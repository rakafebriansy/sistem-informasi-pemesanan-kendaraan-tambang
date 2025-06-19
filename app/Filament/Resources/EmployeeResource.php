<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('admin_id'),
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->unique()
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('generateUsername')
                            ->icon('heroicon-o-arrow-path')
                            ->tooltip('Set Random Username')
                            ->action(function (Forms\Set $set) {
                                $random = Str::upper(Str::random(5)) . random_int(100, 999);
                                $set('username', $random);
                            })
                    ),
                TextInput::make('name')->required()->label('Nama'),
                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->revealable()
                    ->required(),
                Select::make('position')
                    ->options([
                        'staff' => 'Staff',
                        'manager' => 'Manager',
                        'chief' => 'Chief',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')->searchable()->label('Username'),
                TextColumn::make('name')->searchable()->sortable()->label('Nama Pegawai'),
                TextColumn::make('position')->searchable()->label('Posisi')->formatStateUsing(fn(string $state) => ucwords($state)),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('position')
                    ->label('Jenis Kendaraan')
                    ->options([
                        'staf' => 'Staf',
                        'manajer' => 'Manajer',
                        'kepala' => 'Kepala',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Pegawai';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Pegawai';
    }
}
