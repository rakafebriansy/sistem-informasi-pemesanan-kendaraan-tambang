<?php

use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/unduhan-saya/{export}', function (Export $export) {
    if (!Storage::disk($export->file_disk)->exists("/filament_exports/{$export->id}/{$export->file_name}.xlsx")) {
        abort(404);
    }

    return Response::download(
        Storage::disk($export->file_disk)->path("/filament_exports/{$export->id}/{$export->file_name}.xlsx"),
        basename($export->file_name . '.xlsx')
    );
})->name('exports.download');