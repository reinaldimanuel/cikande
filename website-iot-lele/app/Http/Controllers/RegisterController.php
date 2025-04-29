<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

class RegisterController extends Controller
{

    public function create(): View
    {
        return view('auth-register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[A-Za-z0-9_ ]+$/',
            'email' => 'required|string|email|max:255|unique:users,email|email:dns',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'verification_token' => Str::uuid(),
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user));

        return redirect()->back()->with('status', 'Registrasi berhasil! Silakan cek email untuk verifikasi.');
    }
}