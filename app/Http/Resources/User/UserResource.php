<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserAddress\UserAddressResource;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\Permission\PermissionResource;


class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Load permissions for roles if not already loaded
        if ($this->relationLoaded('roles')) {
            $this->roles->loadMissing('permissions');
        }

        return [
            'type' => 'users',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'signature' => $this->signature ? asset('storage/' . $this->signature) : null,
            ],
            'relationships' => [
                'addresses' => UserAddressResource::collection($this->addresses),
                'roles' => RoleResource::collection($this->roles),
                'permissions' => PermissionResource::collection($this->permissions),
            ],
        ];
    }
}
