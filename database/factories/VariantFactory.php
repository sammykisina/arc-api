<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariantFactory extends Factory {
    protected $model = Variant::class;

    public function definition(): array {
        return [
            'name' => fake()->words(nb: 4, asText: true),
            'cost' => fake()->numberBetween(int1: 250, int2: 300),
            'retail' => fake()->numberBetween(int1: 300, int2: 350),
            'stock' => 20,
            'store' => 20,
            'measure' => fake()->numberBetween(int1: 500, int2: 600),
            'vat' => fake()->boolean(),
            'product_id' => Product::factory()->dependent()->create(),
            'active' => fake()->boolean(),
        ];
    }
}
