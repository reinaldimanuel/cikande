<?php

namespace App\Http\Controllers;

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
        
        return view('history', compact('deactivatedPonds'));
    }
}