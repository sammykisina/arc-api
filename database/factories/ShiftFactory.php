<?php

declare(strict_types=1);

namespace Database\Factories;

use Carbon\Carbon;
use Domains\Bartender\Models\Shift;
use Domains\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory {
    protected $model = Shift::class;

    public function definition(): array {
        $currentDateTime = Carbon::now();

        return [
            'name' => fake()->words(nb: 4, asText: true),

            'start_date' => $currentDateTime->toDateString(),
            'start_time' => $currentDateTime->toTimeString(),

            'end_date' => $currentDateTime->addHours(24)->toDateString(),
            'end_time' => $currentDateTime->addHours(24)->toTimeString(),

            'creator' => User::factory()->create([
                'role_id' => 2,
            ])->first_name,
            'active' => true,
        ];
    }
}
