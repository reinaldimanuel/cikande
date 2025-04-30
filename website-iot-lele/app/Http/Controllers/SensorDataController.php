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
            'id_pond' => 'required|integer',
            'ph' => 'required|numeric',
            'temperature' => 'required|numeric',
            'tds' => 'required|numeric',
            'conductivity' => 'required|numeric',
            'salinity' => 'required|numeric',
        ]);
        
        $sensorData = new SensorData();
        $sensorData->id_pond = $validated['id_pond'];
        $sensorData->ph = $validated['ph'];
        $sensorData->temperature = $validated['temperature'];
        $sensorData->tds = $validated['tds'];
        $sensorData->conductivity = $validated['conductivity'];
        $sensorData->salinity = $validated['salinity'];
        $sensorData->save();
        

        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }
}


