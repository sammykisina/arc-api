<?php

declare(strict_types=1);

namespace App\Http\Resources\Bartender;

use App\Http\Resources\Admin\ShiftResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'counter',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
            ],
            'relationships' => [
                'shift' => new ShiftResource(
                    resource:$this->whenLoaded(
                        relationship: 'shift'
                    )
                ),
                'items' => CounterItemResource::collection(
                    resource: $this->whenLoaded(
                        relationship: 'items'
                    )
                ),
            ],
        ];
    }
}
