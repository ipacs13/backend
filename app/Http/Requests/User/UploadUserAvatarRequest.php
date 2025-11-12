<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Support\Facades\Auth;

class UploadUserAvatarRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => 'Avatar is required',
            'avatar.image' => 'Avatar must be an image',
            'avatar.mimes' => 'Avatar must be a jpeg, png, jpg, gif, or svg',
            'avatar.max' => 'Avatar must be less than 2MB',
        ];
    }
}
