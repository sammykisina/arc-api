<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'category',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'description' => $this->description,
            ],
            'relationships' => [
                'products' => ProductResource::collection(
                    resource: $this->whenLoaded('products')
                ),
            ],
        ];
    }
}
