<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeedingSchedule;
use App\Models\FeedingHistory;

class FeedingScheduleController extends Controller
{
    public function index()
    {
        $schedules = FeedingSchedule::orderBy('feeding_time')->get();
        $histories = FeedingHistory::orderBy('feeding_time', 'desc')->get(); // Fetch history data

        return view('feeding', compact('schedules', 'histories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'feeding_time' => 'required|date_format:H:i',
        ]);

        FeedingSchedule::create([
            'feeding_time' => $request->feeding_time,
            'status' => 'Belum'
        ]);

        return redirect()->route('feeding.index');
    }

    public function update(Request $request, $id)
    {
        $schedule = FeedingSchedule::findOrFail($id);
        $schedule->update(['feeding_time' => $request->feeding_time]);

        return redirect()->route('feeding.index');
    }

    public function destroytime($id)
    {
        FeedingSchedule::findOrFail($id)->delete();

        return redirect()->route('feeding.index');
    }
}