<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Fulfillment\Models\Orderline;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderlineFactory extends Factory {
    protected $model = Orderline::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            //
        ];
    }
}
