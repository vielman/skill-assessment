<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request) {
        $credential = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credential)){
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60 * 24);
            return response(["token" => $token], Response::HTTP_OK)->withoutCookie($cookie);

        } else {
            return response(["message" => "invalid credentials"], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function userProfile(Request $request) {
        return response()->json([
            "message" => "userProfile OK",
            "data" => auth()->user()
            ], Response::HTTP_OK
        );
    }
}
