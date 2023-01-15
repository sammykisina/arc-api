<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Fulfillment\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory {
    protected $model = Order::class;

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
