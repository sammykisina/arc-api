<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'product',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'description' => $this->description,
                'form' => $this->form,
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
                'category' => new CategoryResource(
                    resource: $this->whenLoaded(
                        relationship: 'category'
                    )
                ),
                'variants' => VariantResource::collection(
                    resource: $this->whenLoaded(
                        relationship: 'variants'
                    )
                ),
            ],
        ];
    }
}
