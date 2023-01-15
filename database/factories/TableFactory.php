<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Fulfillment\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class TableFactory extends Factory {
    protected $model = Table::class;

    public function definition(): array {
        $extendable = fake()->boolean();

        return [
            'name' => fake()->words(nb: 2, asText: true),
            'number_of_seats' => fake()->numberBetween(int1: 2, int2: 4),
            'extendable' => $extendable,
            'number_of_extending_seats' => $extendable ? fake()->numberBetween(int1: 2, int2: 4) : null,
        ];
    }

    public function non_extendable(): static {
        return $this->state(fn (array $attributes): array => [
            'extendable' => false,
            'number_of_extending_seats' => null
        ]);
    }

    public function extendable(): static {
        return $this->state(fn (array $attributes): array => [
            'extendable' => true,
            'number_of_extending_seats' => fake()->numberBetween(int1: 2, int2: 4)
        ]);
    }
}
