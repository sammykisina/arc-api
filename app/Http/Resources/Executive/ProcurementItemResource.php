<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use Illuminate\Http\Resources\Json\JsonResource;

class ProcurementItemResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'procurement_item',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'form' => $this->form,
                'form_quantity' => $this->form_quantity,
                'number_of_pieces_in_form' => $this->number_of_pieces_in_form,
                'number_of_single_pieces' => $this->number_of_single_pieces,
                'measure' => $this->measure,
                'item_id' => $this->item_id,
                'type' => $this->type,
                'added_to_store' => $this->added_to_store,
            ],
            'relationships' => [
                'procurement' => new ProcurementResource(
                    resource:$this->whenLoaded(
                        relationship: 'procurement'
                    )
                ),
            ],
        ];
    }
}
