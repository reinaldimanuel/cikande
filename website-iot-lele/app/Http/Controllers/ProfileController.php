<?php

namespace App\Http\Controllers;


use App\Mail\VerifyNewEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

class ProfileController extends Controller
{
    public function index(Request $request) {
        return view('app-accset');
    }

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

        if (!$user instanceof User) {
            abort(403, 'Unauthorized');
        }
        
        $user->new_email = $request->email;
        $user->email_change_token = Str::uuid();
        $user->save();
        
        Mail::to($user->new_email)->send(new VerifyNewEmail($user));

        return back()->with('status', 'Link konfirmasi telah dikirim ke alamat email baru. Silakan cek!');
    }

    public function verifyNewEmail($token)
    {
        $user = User::where('email_change_token', $token)->firstOrFail();

        $user->email = $user->new_email;
        $user->new_email = null;
        $user->email_change_token = null;
        $user->save();

        return redirect()->route('settings.index')->with('status', 'Email berhasil diperbarui!');
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