<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Http\Requests\User\RegisterUserRequest;
use App\Models\Role;
use App\Events\UserLogin;

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

                event(new UserLogin($user));

                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => 3600,
                    'user' => new UserResource($user),
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
        try {
            return new UserResource($request->user());
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function register(RegisterUserRequest $request)
    {
        try {
            $user = User::create($request->validated());
            $user->assignRole(Role::ROLE_USER);

            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
