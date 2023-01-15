<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Catalog\Constants\SuppliersStatus;
use Domains\Catalog\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class SupplierFactory extends Factory {
    protected $model = Supplier::class;

    public function definition(): array {
        return [
            'name' => fake()->words(nb: 4, asText: true),
            'number_of_closed_deals' => 0,
            'location' => fake()->words(nb: 2, asText: true),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'status' => Arr::random(
                array: SuppliersStatus::toLabels()
            ),
        ];
    }

    public function active(): static {
        return $this->state(
            fn (array $attributes): array => [
                'status' => SuppliersStatus::active()->label,
            ]
        );
    }

    public function inactive(): static {
        return $this->state(
            fn (array $attributes): array => [
                'status' => Arr::random(
                    array: [
                        SuppliersStatus::underreview()->label,
                        SuppliersStatus::suspended()->label,
                    ]
                ),
            ]
        );
    }
}
