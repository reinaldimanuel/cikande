<?php

namespace App\Http\Controllers;

use App\Models\FeedingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class FeedingController extends Controller
{
    public function store(Request $request)
        {
            $validated = $request->validate([
                'id_pond' => 'required|integer',
                'jam' => 'required|integer',
                'menit' => 'required|integer',
                'berat' => 'required|numeric',
                'status' => 'required|string',
            ]);

            FeedingHistory::create([
                'id_pond' => $validated['id_pond'],
                'jam_feeding' => $validated['jam'],
                'menit_feeding' => $validated['menit'],
                'total_food' => $validated['berat'],
                'status' => $validated['status'],
            ]);

            return response()->json(['message' => 'Feeding log inserted successfully.']);
        }
}
