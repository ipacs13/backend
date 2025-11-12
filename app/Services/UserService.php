<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * Upload user signature from base64 image data
     *
     * @param User $user
     * @param string $base64Image
     * @return array
     * @throws \Exception
     */
    public function uploadSignature(User $user, string $base64Image): array
    {
        // Check if it's a data URL format (data:image/png;base64,...)
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            throw new \Exception('Invalid image format. Expected data URL format (data:image/png;base64,...)');
        }

        $imageType = $matches[1];
        $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
        $imageData = base64_decode($base64Data);

        if ($imageData === false) {
            throw new \Exception('Invalid base64 image data');
        }

        $randomFilename = Str::random(40) . '.' . $imageType;

        $user->addMediaFromBase64($base64Data)
            ->usingFileName($randomFilename)
            ->toMediaCollection('signature', 's3');

        return [
            'message' => 'Signature uploaded successfully',
            'url' => $user->getFirstTemporaryUrl(now()->addMinutes(5), 'signature', 's3'),
            'filename' => $user->getFirstMediaUrl('signature', 's3'),
        ];
    }

    public function uploadAvatar(User $user, array $data = []): array
    {
        $randomFilename = Str::random(40) . '.' . $data['avatar']->getClientOriginalExtension();

        $user->addMedia($data['avatar'])->toMediaCollection('avatar', 's3');

        return [
            'message' => 'Avatar uploaded successfully',
            'url' => $user->getFirstTemporaryUrl(now()->addMinutes(5), 'avatar', 's3'),
            'filename' => $randomFilename,
        ];
    }
}
