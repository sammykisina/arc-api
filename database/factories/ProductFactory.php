<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Catalog\Models\Category;
use Domains\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ProductFactory extends Factory {
    protected $model = Product::class;

    public function definition() {
        $form = Arr::random(array: ['dependent', 'independent']);

        return [
            'name' => fake()->words(nb: 4, asText: true),
            'cost' => $form === 'independent' ? fake()->numberBetween(int1: 250, int2: 300) : null,
            'retail' => $form === 'independent' ? fake()->numberBetween(int1: 300, int2: 350) : null,
            'stock' => $form === 'independent' ? 20 : null,
            'store' => $form === 'independent' ? 20 : null,
            'measure' => $form === 'independent' ? fake()->numberBetween(int1: 500, int2: 600) : null,
            'category_id' => Category::factory()->create(),
            'form' => $form,
            'vat' => $form === 'independent' ? fake()->boolean() : null,
            'active' => fake()->boolean(),
        ];
    }

    public function independent(): static {
        return $this->state(
            fn (array $attributes): array => [
                'cost' => fake()->numberBetween(int1: 250, int2: 300),
                'retail' => fake()->numberBetween(int1: 300, int2: 350),
                'stock' => 20,
                'store' => 20,
                'measure' => fake()->numberBetween(int1: 500, int2: 600),
                'form' => 'independent',
                'vat' => fake()->boolean(),
            ]
        );
    }

    public function dependent(): static {
        return $this->state(
            fn (array $attributes): array => [
                'cost' => null,
                'retail' => null,
                'stock' => null,
                'store' => null,
                'measure' => null,
                'form' => 'dependent',
                'vat' => null,
            ]
        );
    }
}
