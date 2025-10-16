<?php

namespace App\Http\Requests\Otp;

use App\Http\Requests\ApiFormRequest;

class VerifyOtpRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:6|min:6',
            'email' => 'email|required_without:mobile',
            'mobile' => 'string|required_without:email|max:11',
        ];
    }
}
