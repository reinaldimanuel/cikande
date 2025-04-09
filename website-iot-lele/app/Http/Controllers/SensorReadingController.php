<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\SensorSettings;

class SensorReadingController extends Controller
{
    public function index()
    {
        // Fetch latest sensor readings
    //    $sensorReadings = SensorReading::latest()->first();
        $sensorReadings = SensorReading::orderBy('created_at', 'asc')->get();
    //    $sensorReadings = SensorReading::select('*')->distinct()->orderBy('created_at', 'desc')->get()->first();
    //    $sensorReadings = SensorReading::find(SensorReading::max('id'));
        // Fetch the min/max settings
        $settings = SensorSettings::first(); 

        return view('dashboard2', compact('sensorReadings', 'settings'));
    }
}