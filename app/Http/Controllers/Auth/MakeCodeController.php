<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SendEmailService;
use Illuminate\Support\Str;

class MakeCodeController extends Controller
{
    private $sendEmailService;

    public function __construct(SendEmailService $sendEmailService)
    {
        $this->sendEmailService = $sendEmailService;
    }

    public function makeCode() {
        $user = auth()->user();

        $verificationCode = Str::random(6);
    
        $user->verificationCodes()->create([
            'code' => $verificationCode,
        ]);

        $this->sendEmailService->sendEmail($user, $verificationCode);

        return true;
    }
}
