<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Catalog\Models\Token;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class TokenFactory extends Factory {
    protected $model = Token::class;

    public function definition(): array {
        return [
            'name' => fake()->words(nb: 2, asText: true),
            'number_of_single_pieces' => fake()->numberBetween(int1: 5, int2: 10),
            'measure' => fake()->numberBetween(int1: 200, int2: 500),
            'owner' => Arr::random(array: ['Administrator', 'Super Admin']),
        ];
    }

    public function not_tied_to_item(): static {
        return $this->state(fn (array $attributes): array => [
            'item_id' => null,
            'item_type' => null
        ]);
    }

    public function approved(): static {
        return $this->state(fn (array $attributes): array => [
            'approved' => true
        ]);
    }

    public function belonging_to_admin(): static {
        return $this->state(fn (array $attributes): array => [
            'owner' => "Administrator"
        ]);
    }
}
