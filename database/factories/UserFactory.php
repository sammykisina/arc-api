<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory {
    protected $model = User::class;

    public function definition(): array {
        return [
            'work_id' => fake()->phoneNumber(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make(value: 'password'),
            'role_id' => fake()->numberBetween(int1: 1, int2: 5),
            'created_by' => 'admin',
            'remember_token' => Str::random(10),
        ];
    }

    public function executive(): static {
        return $this->state(fn (array $attributes): array => [
            'role_id' => Arr::random([
                Role::query()->where('slug', 'super-admin')->first()->id,
                Role::query()->where('slug', 'admin')->first()->id,
            ]),
        ]);
    }
}
