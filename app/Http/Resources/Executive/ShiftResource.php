<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use App\Http\Resources\SuperAdmin\EmployeeResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'shift',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'duration' => [
                    'start' => [
                        'date' => Carbon::parse($this->start_date)->format('l jS \of F Y'),
                        'time' => Carbon::parse($this->start_time)->format('h:i:s A'),
                    ],
                    'end' => [
                        'date' => Carbon::parse($this->end_date)->format('l jS \of F Y'),
                        'time' => Carbon::parse($this->end_time)->format('h:i:s A'),
                    ],
                ],
                'total_amount' => $this->total_amount,
                'active' => $this->active,
                'created_by' => $this->creator,
            ],
            'relationships' => [
                'workers' => EmployeeResource::collection(
                    $this->whenLoaded(
                        relationship:'workers'
                    )
                ),
            ],
        ];
    }
}
