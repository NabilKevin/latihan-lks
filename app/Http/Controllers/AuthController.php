<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = $request->validate([
            "username" => "required",
            "password" => "required"
        ]);

        if(Auth::validate($validate)) {
            $user = User::firstWhere('username', $request->username);

            $token = bcrypt($user->id);

            $user->update(['token' => $token]);

            return response()->json([
                $token
            ], 200);
        }
        return response()->json([
            "message" => "invalid login"
        ], 401);
    }
    public function logout(Request $request)
    {
        $user = User::firstWhere('token', $request->bearerToken());

        if($user) {
            $user->update(['token' => null]);

            return response()->json([
                'message' => "logout success"
            ], 200);
        }
        return response()->json([
            "message" => "unauthorized user"
        ], 401);
    }
}
