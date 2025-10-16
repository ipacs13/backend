<?php

namespace App\Http\Controllers\Api\Otp;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Otp\SendOtpRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Otp\VerifyOtpRequest;

class OtpController extends ApiController
{
    public function send(SendOtpRequest $request): JsonResponse
    {
        try {
            $user = User::when($request->email, function ($query) use ($request) {
                return $query->where('email', $request->email);
            })->when($request->mobile, function ($query) use ($request) {
                return $query->where('mobile', $request->mobile);
            })->first();

            if (!$user) return $this->respondWithError('User not found');

            $code = rand(100000, 999999);
            $expiresAt = now()->addMinutes(5);

            DB::insert('INSERT INTO otps (user_id, channel, target, code, expires_at) VALUES (?, ?, ?, ?, ?)', [
                $user->id,
                $request->channel,
                $request->email ?? $request->mobile,
                $code,
                $expiresAt,
            ]);

            $target = ['target' => $request->email ?? $request->mobile];

            if ($request->channel === 'email') {
                Mail::raw("Your CCLPI login code: {$code}", fn($m) => $m->to($target['target'])->subject('Your OTP Code'));
            }

            return $this->respondSuccess(['message' => 'OTP sent successfully']);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function verify(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $otp = DB::table('otps')->where('code', $request->code)->where('expires_at', '>', now())->where('target', $request->email ?? $request->mobile)->first();

            if (!$otp) return $this->respondWithError('Invalid OTP');

            DB::table('otps')->where('code', $request->code)->where('expires_at', '>', now())->where('target', $request->email ?? $request->mobile)->delete();

            return $this->respondSuccess(['message' => 'OTP verified successfully']);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
