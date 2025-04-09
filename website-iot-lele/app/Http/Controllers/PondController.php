<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pond;
use App\Models\SensorReading;
use App\Models\SensorSettings;
use App\Models\FeedingSchedule;
use App\Models\FeedingHistory;
use App\Models\Feeder;
use View;

class PondController extends Controller
{
    public function index() {
        $ponds = Pond::where('status_pond', 'Active')->orderBy('created_at', 'asc')->get();
        return view('pond', compact('ponds'));
    }

    public function show($id_pond){
        // Fetch the pond data
        $pond = Pond::where('id_pond', $id_pond)->firstOrFail();

        // Fetch the latest sensor reading for this pond
        $sensorReadings = SensorReading::where('id_pond', $id_pond)->orderBy('created_at', 'asc')->get();

        // Fetch pond settings (assumed there is a settings table)
        $settings = SensorSettings::where('id_pond', $id_pond)->first(); // Adjust if settings are per pond

        //Fetch Feeding Schedule and Histories
        $schedules = FeedingSchedule::where('id_pond', $id_pond)->orderBy('feeding_time')->get();
        $histories = FeedingHistory::where('id_pond', $id_pond)->orderBy('feeding_time', 'desc')->get(); // Fetch history data

        $feeder = Feeder::where('id_pond', $id_pond)->first();

        return view('ponddetail', compact('pond', 'sensorReadings', 'settings','schedules','histories','feeder'));
    }

    public function store(Request $request) {
        // Validate input data
        $request->validate([
            'name_pond' => 'required',
            'age_fish' => 'required|integer',
            'total_fish' => 'required|integer',
        ]);
    
        // Create the pond with default status "Active"
        $pond = Pond::create($request->all() + ['status_pond' => 'Active']);
    
        // Insert default sensor settings for the new pond
        SensorSettings::create([
            'id_pond'          => $pond->id_pond,
            'min_ph'           => 6.5,   // Optimal min pH for most fish
            'max_ph'           => 8.5,   // Optimal max pH
            'min_temperature'  => 24.0,  // Optimal min temp (°C)
            'max_temperature'  => 30.0,  // Optimal max temp (°C)
            'min_tds'          => 300,   // Minimum TDS (ppm)
            'max_tds'          => 1200,  // Maximum TDS (ppm)
            'min_conductivity' => 500,   // Minimum Conductivity (µS/cm)
            'max_conductivity' => 1500,  // Maximum Conductivity (µS/cm)
            'min_salinity'     => 0.1,   // Minimum Salinity (ppt)
            'max_salinity'     => 35.0   // Maximum Salinity (ppt)
        ]);
    
        return redirect()->route('kolam.index')->with('success', 'Kolam berhasil ditambahkan!');
    }

    public function update(Request $request, $id_pond) {
        $pond = Pond::findOrFail($id_pond);
        $pond->update($request->except('id_pond'));

        return redirect()->route('kolam.index')->with('success', 'Berhasil, data kolam telah diperbarui!');
    }

    public function deactivate($id_pond) {
        $pond = Pond::findOrFail($id_pond);
        $pond->update([
        'status_pond' => 'Deactive'
        ]);

        return redirect()->route('kolam.index')->with('success', 'Kolam dihapus!');
    }

    public function deactivatedPonds()
    {
        $deactivatedPonds = Pond::where('status_pond', 'Deactive')->orderBy('updated_at', 'desc')->get();
        return view('history', compact('deactivatedPonds'));
    }

    public function updatesensor(Request $request, $id_pond)
    {
        // Find the record by id_pond
        $settings = SensorSettings::where('id_pond', $id_pond)->firstOrFail();

        // Validate the request data (optional but recommended)
        $validatedData = $request->validate([
            'min_ph' => 'nullable|numeric',
            'max_ph' => 'nullable|numeric',
            'min_temperature' => 'nullable|numeric',
            'max_temperature' => 'nullable|numeric',
            'min_tds' => 'nullable|integer',
            'max_tds' => 'nullable|integer',
            'min_conductivity' => 'nullable|integer',
            'max_conductivity' => 'nullable|integer',
            'min_salinity' => 'nullable|numeric',
            'max_salinity' => 'nullable|numeric',
        ]);

        // Update the record
        $settings->update($validatedData);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Berhasil, nilai sensor telah diubah!');
    }

    public function storetime(Request $request, $id_pond)
    {
        $request->validate([
            'feeding_time' => 'required|date_format:H:i',
        ]);
    
        FeedingSchedule::create([
            'id_pond' => $id_pond,  // Store the selected id_pond
            'feeding_time' => $request->feeding_time,
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan waktu!');
    }

    public function updatetime(Request $request, $id)
    {
        // Find the feeding schedule by ID
        $schedule = FeedingSchedule::where('id', $id)->firstOrFail();

        // Validate the request
        $request->validate([
            'feeding_time' => 'required|date_format:H:i', // Ensures the time format is valid
        ]);

        // Update the feeding time
        $schedule->update([
            'feeding_time' => $request->input('feeding_time'),
        ]);

        return redirect()->back()->with('success', 'Berhasil, waktu telah diubah!');
    }

    public function destroytime($id)
    {
        FeedingSchedule::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Waktu dihapus!');
    }
}