<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VerificationController extends Controller
{
    public function notice()
    {
        return view('auth-verify-notice');
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->email_verified_at = Carbon::now();
        $user->verification_token = null;
        $user->save();

        return redirect()->route('login.index')->with('status', 'Email berhasil diverifikasi! Silakan login.');
    }
}