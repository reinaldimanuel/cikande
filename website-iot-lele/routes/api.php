<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedingController;
use App\Models\Feeder;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\SensorDataController;

Route::post('/insert', [SensorDataController::class, 'store']);
Route::post('/feeding', [FeedingController::class, 'store']);


Route::get('/target-berat/{id_pond}', function ($id_pond) {
    $feeder = Feeder::where('id_pond', $id_pond)->first();

    if (!$feeder) {
        return response()->json(['error' => 'Feeder tidak ditemukan'], 404);
    }

    return response()->json([
        'berat' => $feeder->total_food // satuan gram
    ]);
});