<?php
namespace App\Services;

use App\Mail\RegisterEmail;
use Illuminate\Support\Facades\Mail;

class SendEmailService{
    public function __construct()
    {
        
    }

    public function sendEmail($user, $code){
        Mail::to($user->email)->send(new RegisterEmail($code));
        
        return response()->json([
            'Email enviado com sucesso!'
        ]);
    }
}