<?php

namespace App\Http\Requests\Otp;

use App\Http\Requests\ApiFormRequest;

class SendOtpRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'email|required_without:mobile',
            'mobile' => 'string|required_without:email|max:11',
            'channel' => 'string|in:email,mobile|required',
        ];
    }
}
