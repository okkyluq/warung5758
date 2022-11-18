<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\API\UserLogin;
use App\PengaturanSistem;

class UserLoginController extends Controller
{
    public function login(UserLogin $request)
    {
    	$credentials = $request->only('username', 'password');

    	try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Username dan Password Salah'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json([
            'token' => $token,
            'pengaturan' => PengaturanSistem::all(),
        ]);

    }

    public function get_user_info(Request $request)
    {

        return response()->json([
            'user' => Auth()->user(),
        ]);
    }

    public function refresh_token(Request $request)
    {
        $token = auth('api')->refresh();

        return response()->json([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        return response()->json($request->all());
    }
}
