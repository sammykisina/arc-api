<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Bartender\Models\Counter;
use Domains\Bartender\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class CounterFactory extends Factory {
    protected $model = Counter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => fake()->words(nb: 2, asText: true),
            'shift_id' => Shift::factory()->create(),
        ];
    }
}
