<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'variant',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'price' => [
                    'cost' => $this->cost,
                    'retail' => $this->retail,
                ],
                'inventory' => [
                    'stock' => $this->stock,
                    'store' => $this->store,
                    'sold' => $this->sold,
                ],
                'measure' => $this->measure,
                'active' => $this->active,
                'vat' => $this->vat,
            ],
            'relationships' => [
                'product' => new ProductResource(
                    resource: $this->whenLoaded(
                        relationship: 'product'
                    )
                ),
            ],
        ];
    }
}
