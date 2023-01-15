<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Shared\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory {
    protected $model = Role::class;

    public function definition(): array {
        $name = fake()->words(nb:3, asText: true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
