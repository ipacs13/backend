<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Support\Facades\Auth;

class UploadUserSignatureRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'signature' => 'required|string',
        ];
    }
}
