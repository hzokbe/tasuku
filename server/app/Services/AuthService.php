<?php

namespace App\Services;

use App\Mail\RecoverPasswordMail;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function sendResetPasswordLink(string $username, string $email, string $token): void
    {
        Mail::to($email)->send(new RecoverPasswordMail($username, $token));
    }
}
