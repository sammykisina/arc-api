<?php

declare(strict_types=1);

namespace App\Http\Resources\SuperAdmin;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'role',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'slug' => $this->slug,
            ],
            'relationships' => [
                'users' => EmployeeResource::collection(
                    resource: $this->whenLoaded(
                        relationship: 'users'
                    )
                ),
            ],
        ];
    }
}
