<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);
    

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'Password berhasil diperbarui!');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
        ]);

        $user = auth()->user();
        $user->email = $request->email;
        $user->save();

        return back()->with('status', 'Email berhasil diperbarui!');
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->save();

        return back()->with('status', 'Nama berhasil diperbarui!');
    }
}