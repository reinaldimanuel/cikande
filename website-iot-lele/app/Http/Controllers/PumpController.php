<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PumpController extends Controller
{
    public function control(Request $request)
    {
        $command = $request->input('command'); // "on" atau "off"

        if (!in_array($command, ['on', 'off'])) {
            return response()->json(['error' => 'Perintah tidak valid'], 400);
        }

        $espIp = 'http://192.168.4.1'; // Ganti dengan IP ESP kamu
        $url = $espIp . '/pump/' . $command;

        try {
            $response = Http::timeout(3)->get($url);

            if ($response->successful()) {
                return response()->json(['status' => 'Pompa ' . strtoupper($command)]);
            } else {
                return response()->json(['error' => 'ESP tidak merespon'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghubungi ESP: ' . $e->getMessage()], 500);
        }
    }
}

