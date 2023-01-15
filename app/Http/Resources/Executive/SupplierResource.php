<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'supplier',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'number_of_closed_deals' => $this->number_of_closed_deals,
                'contact_info' => [
                    'location' => $this->location,
                    'phone_number' => $this->phone_number,
                    'email' => $this->email,
                ],
                'status' => $this->status,
            ],
            'relationships' => [
                'variants' => VariantResource::collection(
                    resource: $this->whenLoaded(
                        relationship: 'variants'
                    )
                ),
                'products' => ProductResource::collection(
                    resource: $this->whenLoaded(
                        relationship: 'products'
                    )
                ),
            ],
        ];
    }
}
