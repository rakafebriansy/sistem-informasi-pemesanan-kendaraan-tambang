<?php

namespace App\Filament\Employee\Resources\UsageHistoryResource\Widgets;

use Filament\Actions\Exports\Models\Export;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class MyExportsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Unduhan Saya';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('file_name')->label('Nama File')->limit(40),
                TextColumn::make('total_rows')->label('Baris Total'),
                TextColumn::make('created_at')->label('Tanggal')->dateTime(),
            ])
            ->actions([
                Action::make('download')
                    ->label('Download')
                    ->url(fn(Export $record) => route('exports.download',['export' => $record->id]))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-down-tray'),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        $user = filament()->auth()->user();

        return Export::query()
            ->when($user instanceof \App\Models\Admin, fn($q) => $q->where('user_id', $user->id))
            ->when($user instanceof \App\Models\Employee, fn($q) => $q->where('user_id', $user->id))
            ->latest();
    }
}
