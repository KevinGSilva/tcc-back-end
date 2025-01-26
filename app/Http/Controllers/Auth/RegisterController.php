<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request) {
        $data = $request->all();

        $user = $this->userService->register($data);

        return $user;
    }

    public function verifyEmail(Request $request) {
        $code = $request->get('code');

        $user = auth()->user();
        $userCode = $user->verificationCodes()->latest()->first();

        if($code == $userCode->code ){
            $user->markEmailAsVerified();
            return response()->json([
                "message" => "Email verificado",
                "status" => "success"
            ]);
        }

        return response()->json(['message' => 'Código inválido'], 400);
    }
}
