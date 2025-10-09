<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();

                $token = $user->createToken('API Token')->accessToken;

                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => 3600,
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name,
                    ]
                ]);
            }

            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
