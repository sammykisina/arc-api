<?php

declare(strict_types=1);

namespace App\Http\Resources\Bartender;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterItemResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'counterItem',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'assigned' => $this->assigned,
                'sold' => $this->sold,
                'price' => $this->price,
                'product_id' => $this->product_id,
                'form' => $this->form,
            ],
        ];
    }
}
