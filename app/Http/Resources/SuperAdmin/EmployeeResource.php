<?php

declare(strict_types=1);

namespace App\Http\Resources\SuperAdmin;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'employee',
            'attributes' => [
                'uuid' => $this->uuid,
                'work_id' => $this->work_id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'active' => $this->active,
                'password' => $this->password,
                'created_by' => $this->created_by,
                'time' => [
                    'created_at' => $this->created_at,
                ],
            ],
            'relationships' => [
                'role' => new RoleResource(
                    resource: $this->whenLoaded(
                        relationship:'role'
                    )
                ),
            ],
        ];
    }
}
