<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth-recover-pw');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset kata sandi berhasil dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan email tersebut.']);
    }
}