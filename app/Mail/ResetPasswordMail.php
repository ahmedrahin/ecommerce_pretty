<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token; // Reset Token
    public $email; // User Email
    public $name;
    public $isAdmin;

    public function __construct($token, $email, $name, $isAdmin)
    {
        $this->token = $token;
        $this->email = $email;
        $this->name = $name;
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        return $this->subject('Reset Your Password')
                    ->view('email.reset-password'); 
    }
}
