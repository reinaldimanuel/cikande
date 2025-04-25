<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Verifikasi Email Akun')
                    ->view('auth-verify')
                    ->with([
                        'url' => url('/verify-email/' . $this->user->verification_token),
                    ]);
    }
}