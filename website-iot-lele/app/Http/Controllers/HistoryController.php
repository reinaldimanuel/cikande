<?php

namespace App\Http\Controllers;

use App\Models\SensorReading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Pond;
use View;

class HistoryController extends Controller
{
    public function deactivatedPonds(){
        
        $deactivatedPonds = Pond::where('status_pond', 'Deactive')
        ->with(['latestReading']) // Fetch latest sensor data
        ->orderBy('updated_at', 'desc')
        ->get();
        
        return view('app-history', data: compact('deactivatedPonds'));
    }

    public function getAverageSensor(Request $request, $id_pond)
    {
        $range = $request->query('range'); // e.g., '1m', '3m', '6m', '1y'

        // Ambil waktu sensor terakhir
        $lastReading = SensorReading::where('id_pond', $id_pond)->latest('created_at')->first();

        if (!$lastReading) {
            return response()->json(['message' => 'Tidak ada data sensor'], 404);
        }

        $endDate = $lastReading->created_at;

        // Hitung start date berdasarkan range
        switch ($range) {
            case '1':
                $startDate = Carbon::parse($endDate)->subMonth();
                break;
            case '3':
                $startDate = Carbon::parse($endDate)->subMonths(3);
                break;
            case '6':
                $startDate = Carbon::parse($endDate)->subMonths(6);
                break;
            case '12':
                $startDate = Carbon::parse($endDate)->subYear();
                break;
            default:
                return response()->json(['message' => 'Range tidak valid'], 400);
        }

        // Ambil data rata-rata sensor dari periode tsb
        $average = SensorReading::where('id_pond', $id_pond)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                AVG(ph) as avg_ph,
                AVG(temperature) as avg_temperature,
                AVG(tds) as avg_tds,
                AVG(conductivity) as avg_conductivity,
                AVG(salinity) as avg_salinity
            ')
            ->first();

        return response()->json([
            'avg_ph' => $average->avg_ph,
            'avg_temperature' => $average->avg_temperature,
            'avg_tds' => $average->avg_tds,
            'avg_conductivity' => $average->avg_conductivity,
            'avg_salinity' => $average->avg_salinity,
        ]);
    }
}