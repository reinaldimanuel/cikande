<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorController extends Controller
{
    public function insert(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'ph' => 'required|numeric',
            'tds' => 'required|integer',
            'suhu' => 'required|numeric',
            'tinggi' => 'required|integer',
        ]);

        // Simpan ke database
        $sensor = new SensorData();
        $sensor->ph = $validated['ph'];
        $sensor->tds = $validated['tds'];
        $sensor->suhu = $validated['suhu'];
        $sensor->tinggi = $validated['tinggi'];
        $sensor->save();

        return response()->json(['status' => 'OK', 'message' => 'Data berhasil disimpan']);
    }
}