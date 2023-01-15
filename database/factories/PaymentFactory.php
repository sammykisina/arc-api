<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Fulfillment\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory {
    protected $model = Payment::class;

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
