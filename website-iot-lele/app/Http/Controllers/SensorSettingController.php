<?php

namespace App\Http\Controllers;

use App\Models\SensorSettings;
use Illuminate\Http\Request;

class SensorSettingController extends Controller
{
    public function index()
    {
        $settings = SensorSettings::first(); // Get the only row

        return view('sensor.index', compact('settings'));
    }

    public function update(Request $request, $id_pond)
    {
        $settings = SensorSettings::findOrFail('id_pond'); // Only one row exists
        $settings->update($request->except('id_pond'));

        return redirect()->back()->with('success', 'Berhasil, nilai sensor telah diubah!');
    }
}