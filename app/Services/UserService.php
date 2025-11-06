<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $folderPath = $user->id . '/signature';

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        Storage::disk('public')->put($folderPath . '/' . $randomFilename, $imageData);

        $user->signature = $randomFilename;
        $user->save();

        $url = asset('storage/' . $folderPath . '/' . $randomFilename);

        return [
            'message' => 'Signature uploaded successfully',
            'url' => $url,
            'filename' => $randomFilename,
        ];
    }
}
