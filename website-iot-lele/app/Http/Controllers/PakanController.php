<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PakanController extends Controller
{
    public function ManualPakan()
    {
        $esp_ip = 'http://122.200.6.145/manual-feeding';
        try {
        $response = Http::get($esp_ip);
        return back()->with('success', 'Pakan manual berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim pakan manual!');
        }
    }
}
