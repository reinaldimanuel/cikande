<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\Pond;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function showStatistics(Request $request) {
        // Get start and end date from the request, with default values
        $startDate = $request->query('start_date', now()->subMonth());
        $endDate = $request->query('end_date', now());
    
        // Get selected pond ID (default: first pond)
        $ponds = Pond::where('status_pond', 'Active')->orderBy('created_at', 'asc')->get();
        $selectedPondId = $request->query('id_pond', $ponds->first()->id_pond ?? null);
    
        // Fetch sensor readings based on selected pond and date range
        $readings = SensorReading::where('id_pond', $selectedPondId)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),  // Includes 00:00:00 of the start date
                Carbon::parse($endDate)->endOfDay()        // Includes 23:59:59 of the end date
            ])
            ->get();
    
        // Get age distribution chart data
        $agechart = Pond::where('status_pond', 'Active')
            ->select('name_pond', 'age_fish', 'total_fish as total')
            ->orderBy('name_pond')
            ->get();

        $feeders = DB::table('feeder')
            ->join('pond', 'feeder.id_pond', '=', 'pond.id_pond')
            ->select('pond.name_pond', 'feeder.feeder_status')
            ->get();

        return view('app-dashboard', compact('readings', 'startDate', 'endDate', 'agechart', 'ponds', 'selectedPondId','feeders'));
    }
}