<?php

declare(strict_types=1);

namespace App\Http\Resources\Bartender;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'order',
            'attributes' => [
                'uuid' => $this->uuid,
                'number' => $this->number,
                'cost' => [
                    'sub_total' => $this->sub_total,
                    'discount' => $this->discount,
                    'total' => $this->total,
                ],
                'status' => $this->status,
                'shift_id' => $this->shift_id,
                'times' => [
                    'completed_at' => $this->completed_at,
                    'cancelled_at' => $this->cancelled_at,
                ],
            ],
            'relationships' => [
                'orderline' => OrderlineResource::collection(
                    resource:$this->whenLoaded('items')
                ),
            ],
        ];
    }
}
