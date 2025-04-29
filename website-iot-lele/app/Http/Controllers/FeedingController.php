<?php

namespace App\Http\Controllers;

use App\Models\FeedingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class FeedingController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Feeding Request:', $request->all());
        FeedingHistory::create([
        'id_pond'=>$request->input('idpond'),
        'jam_feeding' => $request->input('jam'),
        'menit_feeding' => $request->input('menit'),
        'total_food' => $request->input('berat'),
        'status' => $request->input('status'),
        ]);
        return response()->json(['message' => 'Feeding log inserted successfully.']);
    }
}
