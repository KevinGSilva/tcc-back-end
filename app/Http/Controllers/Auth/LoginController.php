<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(["email", "password"]);

        if (auth()->attempt($credentials)) {

            /** @var \App\Models\User $user **/
            $user = auth()->user();

            $user->tokens()->delete();

            $token = $user->createToken("authToken")->plainTextToken;

            return response()->json([
                "message" => "Authentication successfully",
                "user" => $user,
                "token" => $token,
                "status" => "success"
            ]);
        }

        return response()->json([
            "message" => "Credenciais inválidas",
            "status" => "error"
        ], 401);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();

        if ($user) {
            $user->tokens()->delete();

            return response()->json([
                "message" => "Logout successfully",
                "status" => "success"
            ]);
        }

        return response()->json([
            "message" => "User not authenticated",
            "status" => "error"
        ], 401);
    }
}
