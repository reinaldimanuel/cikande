<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorDataController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'ph' => 'required|numeric',
            'tds' => 'required|numeric',
            'suhu' => 'required|numeric',
            'tinggi' => 'required|numeric',
        ]);

        // Simpan data ke database
        $sensorData = new SensorData();
        $sensorData->ph = $validated['ph'];
        $sensorData->tds = $validated['tds'];
        $sensorData->suhu = $validated['suhu'];
        $sensorData->tinggi = $validated['tinggi'];
        $sensorData->save();

        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }
}


