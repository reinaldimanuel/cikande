<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\PondController;
use App\Http\Controllers\LoginController;  
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\PasswordResetLinkController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\PakanController;
use App\Http\Controllers\PumpController;

Route::post('/pump-control', [PumpController::class, 'control']);

Route::get('/', function () {
    return view('welcome');
});

//Section Dasbor
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dasbor', [ChartController::class, 'showStatistics'])->name('dashboard.statistics');
    });

//Section Kolam
    Route::middleware(['auth','verified'])->group(function () {
        // List Kolam
            Route::resource('kolam', PondController::class);
            Route::get('/kolam/{id_pond}', [PondController::class, 'show'])->name('kolam.show');
            Route::put('kolam/{id_pond}/deactivate', [PondController::class, 'deactivate'])->name('kolam.deactivate');
        //Tab kakan
            Route::put('/kolam/{id}/update-jumlah-pakan', [PondController::class, 'updatetotalfood'])->name('kolam.updatetotalfood');
        //Tab Sensor Value Setting
            Route::put('/kolam/{id_pond}/updatesensor', [PondController::class, 'updatesensor'])->name('kolam.updatesensor');
    });

//Section Riwayat
    Route::middleware(['auth','verified'])->group(function () {
        Route::get('/riwayat', [HistoryController::class, 'deactivatedPonds'])->name('riwayat.deactivated');
        Route::get('/riwayat/{id_pond}/average-sensor', [HistoryController::class, 'getAverageSensor']);
    });

//Section Authentication
    //Login
        Route::get('/login', [LoginController::class, 'index'])->name('login.index');
        Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
    // Register
        Route::get('/daftar', [RegisterController::class, 'create'])->name('daftar.create');
        Route::post('/daftar', [RegisterController::class, 'store'])->name('daftar.store');
    // Email Verification
        Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
        Route::get('/verify-email/{token}', [VerificationController::class, 'verify'])->name('verification.verify');
    // Forgot Password
        Route::get('password/pemulihan', [PasswordResetLinkController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('password/email', [PasswordResetLinkController::class, 'sendResetLinkEmail'])->name('password.email');
    // New Password Reset
        Route::get('password/reset/{token}', [NewPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [NewPasswordController::class, 'reset'])->name('password.update');

//Section Pengaturan Akun
    Route::middleware(['auth','verified'])->group(function () {
        Route::get('/pengaturan', function () {return view('app-accset');});
        Route::get('/pengaturan', [ProfileController::class, 'index'])->name('settings.index');
        Route::post('/pengaturan/password', [ProfileController::class, 'updatePassword'])->name('settings.password');
        Route::post('/pengaturan/email', [ProfileController::class, 'updateEmail'])->name('settings.email');
        Route::get('/verify-new-email/{token}', [ProfileController::class, 'verifyNewEmail'])->name('verify-new-email');
        Route::post('/pengaturan/nama', [ProfileController::class, 'updateName'])->name('settings.name');
    });

// Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    
use Illuminate\Http\Request;
use App\Models\SensorData;

// Route::post('/insert', function (Request $request) {
//     $validated = $request->validate([
//         'suhu' => 'required|numeric',
//         'ph' => 'required|numeric',
//         'tds' => 'required|numeric',
//         'tinggi' => 'required|numeric',
//     ]);

//     SensorData::create($validated);

//     return response()->json(['status' => 'Data berhasil disimpan']);
// })->withoutMiddleware(['web']);

// kode manual pakan 
Route::get('/manual-feeding', [PakanController::class, 'ManualPakan']);
