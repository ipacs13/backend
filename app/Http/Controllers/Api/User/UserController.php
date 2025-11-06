<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\UploadUserSignatureRequest;
use App\Services\UserService;

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
}
