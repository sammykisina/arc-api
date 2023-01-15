<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Bartender\Models\Counter;
use Domains\Bartender\Models\CounterItem;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class CounterItemFactory extends Factory {
    protected $model = CounterItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $product = Product::factory()->create();
        $variant = Variant::factory()->create();
        $decide = fake()->boolean();

        return [
            'name' => $decide ? $product->name : $variant->name,
            'assigned' => 10,
            'price' => 250,
            'counter_id' => Counter::factory()->create(),
            'form' => 'dependent',
        ];
    }
}
