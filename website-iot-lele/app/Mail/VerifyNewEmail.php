<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VerifyNewEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Konfirmasi Pergantian Email')
                    ->view('auth-verify')
                    ->with([
                        'url' => url('/verify-new-email/' . $this->user->email_change_token),
                    ]);
    }
}