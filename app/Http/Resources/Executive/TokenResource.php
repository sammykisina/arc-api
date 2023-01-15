<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'token',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'number_of_single_pieces' => $this->number_of_single_pieces,
                'measure' => $this->measure,
                'owner' => $this->owner,
                'added_to_store' => $this->added_to_store,
                'item_id' => $this->item_id,
                'item_type' => $this->item_type,
                'approved' => $this->approved
            ]
        ];
    }
}
