<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory {
    protected $model = Category::class;

    public function definition(): array {
        return [
            'name' => fake()->words(nb:3, asText: true),
            'description' => fake()->paragraphs(nb:4, asText:true),
        ];
    }
}
