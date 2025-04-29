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
        ->orderBy('updated_at', 'desc')
        ->get()
        ->map(function ($pond) {
            if ($pond->birth_fish) {
                $start = Carbon::parse($pond->birth_fish);
                $end = Carbon::parse($pond->updated_at);
                $totalDays = $start->diffInDays($end);

                $pond->history_formatted_age = $this->formatAge($totalDays);
            } else {
                $pond->history_formatted_age = '-';
            }

            return $pond;
        });

        return view('app-history', compact('deactivatedPonds'));
    }

    public function getAverageSensor(Request $request, $id_pond)
    {   

        $range = $request->query('range'); // e.g., '1m', '3m', '6m', '1y'

        $lastReading = SensorReading::where('id_pond', $id_pond)->latest('created_at')->first();

        if (!$lastReading) {
            return response()->json(['message' => 'Tidak ada data sensor'], 404);
        }

        $endDate = $lastReading->created_at;

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

        $average = SensorReading::where('id_pond', $id_pond)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                ROUND(AVG(ph), 1) as avg_ph,
                ROUND(AVG(temperature), 1) as avg_temperature,
                ROUND(AVG(tds), 0) as avg_tds,
                ROUND(AVG(conductivity), 0) as avg_conductivity,
                ROUND(AVG(salinity), 2) as avg_salinity
            ')
            ->first();

        return response()->json([
            'avg_ph' => $average->avg_ph,
            'avg_temperature' => $average->avg_temperature,
            'avg_tds' => $average->avg_tds,
            'avg_conductivity' => $average->avg_conductivity,
            'avg_salinity' => $average->avg_salinity,
            'last_reading_time' => $lastReading->created_at->toDateTimeString(),
        ]);
    }

    protected function formatAge($totalDays)
    {
        $years = floor($totalDays / 365);
        $remainingDays = $totalDays % 365;

        $months = floor($remainingDays / 30);
        $remainingDays %= 30;

        $weeks = floor($remainingDays / 7);
        $days = $remainingDays % 7;

        if ($years > 0) {
            return $years . ' tahun' . ($months > 0 ? ' ' . $months . ' bulan' : '');
        } elseif ($months > 0) {
            return $months . ' bulan' . ($weeks > 0 ? ' ' . $weeks . ' minggu' : '');
        } elseif ($weeks > 0) {
            return $weeks . ' minggu' . ($days > 0 ? ' ' . $days . ' hari' : '');
        } else {
            return $days . ' hari';
        }
    }
}