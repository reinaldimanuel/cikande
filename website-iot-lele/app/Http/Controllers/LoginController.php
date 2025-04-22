<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */

     public function index(): View
    {
        return view('auth-login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dasbor')->with('success', 'Sukses login!');
        }
 
        return back()->withErrors([
            'email' => '⚠️ Data tidak sesuai, mohon pastikan kembali ⚠️',
        ])->onlyInput('email');
    }
}