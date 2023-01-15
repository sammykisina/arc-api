<?php

declare(strict_types=1);

namespace App\Http\Resources\Bartender;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderlineResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'orderline',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'price' => [
                    'cost' => $this->cost,
                    'retail' => $this->retail,
                ],
                'quantity' => $this->quantity,
            ],
        ];
    }
}
