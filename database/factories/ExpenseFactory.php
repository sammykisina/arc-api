<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory {
    protected $model = Expense::class;

    public function definition(): array {
        return [
            'description' => '',
            'amount' => '',
            'date' => '',
            'spender_id' => '',
            'authorize_id' => '',
            'shift_id' => '',
        ];
    }
}
