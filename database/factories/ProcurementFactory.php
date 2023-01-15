<?php

declare(strict_types=1);

namespace Database\Factories;

use Carbon\Carbon;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ProcurementFactory extends Factory {
    protected $model = Procurement::class;

    public function definition(): array {
        $date_time = Carbon::now();

        return [
            'status' => Arr::random(
                array: ProcurementStatus::toLabels()
            ),
            'procurement_date' => $date_time,
            'due_date' => $date_time->addDays(value: 5),
            'supplier_id' => Supplier::factory()->create(),
        ];
    }

    public function pending(): static {
        return $this->state(
            fn (array $attributes): array => [
                'status' => ProcurementStatus::pending()->label,
            ]
        );
    }

    public function delivered(): static {
        return $this->state(
            fn (array $attributes): array => [
                'status' => ProcurementStatus::delivered()->label,
                'total_cost' => fake()->numberBetween(int1: 5000, int2: 10000),
                'delivered_date' => Carbon::now(),
            ]
        );
    }
}
