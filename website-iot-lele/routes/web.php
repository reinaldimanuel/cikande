<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\PondController;
use App\Http\Controllers\LoginController;  
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\PumpController;

Route::post('/pump-control', [PumpController::class, 'control']);

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
Route::get('/riwayat', [HistoryController::class, 'deactivatedPonds'])->name('riwayat.deactivated');
Route::get('/riwayat/{id_pond}/average-sensor', [HistoryController::class, 'getAverageSensor']);

//Section Authentication
Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::get('/daftar', [RegisterController::class, 'create'])->name('daftar.create');
Route::post('/daftar', [RegisterController::class, 'store'])->name('daftar.store');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

Route::get('/pengaturan', function () {
    return view('app-accset');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/pengaturan/password', [ProfileController::class, 'updatePassword'])->name('settings.password');
    Route::post('/pengaturan/email', [ProfileController::class, 'updateEmail'])->name('settings.email');
    Route::post('/pengaturan/nama', [ProfileController::class, 'updateName'])->name('settings.name');
});


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


use Illuminate\Http\Request;
use App\Models\SensorData;

Route::post('/insert', function (Request $request) {
    $validated = $request->validate([
        'suhu' => 'required|numeric',
        'ph' => 'required|numeric',
        'tds' => 'required|numeric',
        'tinggi' => 'required|numeric',
    ]);

    SensorData::create($validated);

    return response()->json(['status' => 'Data berhasil disimpan']);
})->withoutMiddleware(['web']);
