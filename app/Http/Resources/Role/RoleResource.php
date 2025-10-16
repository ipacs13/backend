<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Permission\PermissionCollection;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $include = collect(explode(',', $request->get('include')));
        // if ($include->contains('permissions')) {
        $this->loadMissing('permissions');
        // }


        return [
            'type' => 'role',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
            ],
            'relationships' => [
                'permissions' => new PermissionCollection($this->whenLoaded('permissions')),
            ],
        ];
    }
}
