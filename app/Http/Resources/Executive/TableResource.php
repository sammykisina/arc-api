<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'table',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'number_of_seats' => $this->number_of_seats,
                'extendable' => $this->extendable,
                'number_of_extending_seats' => $this->number_of_extending_seats,
            ],
        ];
    }
}
