<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\UploadUserSignatureRequest;
use App\Http\Requests\User\UploadUserAvatarRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    public function __construct(protected UserService $userService) {}

    public function uploadSignature(UploadUserSignatureRequest $request)
    {
        try {
            $result = $this->userService->uploadSignature($request->user(), $request->input('signature'));

            return $this->respondSuccess($result);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function uploadAvatar(UploadUserAvatarRequest $request)
    {
        try {
            $user = Auth::user();
            $result = $this->userService->uploadAvatar($user, $request->only('avatar'));

            return $this->respondSuccess($result);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
