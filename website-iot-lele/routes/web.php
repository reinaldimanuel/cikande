<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\PondController;

Route::get('/', function () {
    return view('welcome');
});

//Section Dasbor
Route::get('/dasbor', [ChartController::class, 'showStatistics'])->name('dashboard.statistics');

//Section Kolam
Route::resource('kolam', PondController::class);
Route::get('/kolam/{id_pond}', [PondController::class, 'show'])->name('kolam.show');
Route::put('kolam/{id_pond}/deactivate', [PondController::class, 'deactivate'])->name('kolam.deactivate');

    //Tab Jadwal
    Route::post('/kolam/{id_pond}/storetime', [PondController::class, 'storetime'])->name('kolam.storetime');
    Route::put('/kolam/{id}/updatetime', [PondController::class, 'updatetime'])->name('kolam.updatetime');
    Route::delete('/kolam/{id}/destroytime', [PondController::class, 'destroytime'])->name('kolam.destroytime');
    //Tab Sensor Value Setting
    Route::put('/kolam/{id_pond}/updatesensor', [PondController::class, 'updatesensor'])->name('kolam.updatesensor');

//Section Riwayat
Route::get('/riwayat', [PondController::class, 'deactivatedPonds'])->name('riwayat.deactivated');
