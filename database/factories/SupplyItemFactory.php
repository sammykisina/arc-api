<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Catalog\Models\SupplyItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplyItemFactory extends Factory {
    protected $model = SupplyItem::class;

    public function definition(): array {
        return [
            'name' => fake()->words(nb: 2, asText:true),
        ];
    }
}
