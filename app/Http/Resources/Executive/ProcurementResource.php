<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcurementResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'procurement',
            'attributes' => [
                'uuid' => $this->uuid,
                'number' => $this->number,
                'total_cost' => $this->total_cost,
                'status' => $this->status,
                'dates' => [
                    'procurement_date' => Carbon::parse($this->procurement_date)->toDateString(),
                    'due_date' => Carbon::parse($this->due_date)->toDateString(),
                    'delivered_date' => $this->delivered_date
                        ? Carbon::parse($this->delivered_date)->toDateString()
                        : null,
                    "cancelled_date" => $this->cancelled_date ? Carbon::parse($this->cancelled_date)->toDateString()
                        : null
                ],
            ],
            'relationships' => [
                'supplier' => new SupplierResource(
                    resource: $this->whenLoaded(
                        relationship: 'supplier'
                    )
                ),
                'item' => new ProcurementItemResource(
                    resource: $this->whenLoaded(
                        relationship: 'item'
                    )
                ),
            ],
        ];
    }
}
