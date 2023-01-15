<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Constants\ProcurementItemForms;
use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\ProcurementItem;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ProcurementItemFactory extends Factory {
    protected $model = ProcurementItem::class;

    public function definition(): array {
        $procurement_item_form = Arr::random(
            array: ProcurementItemForms::toLabels()
        );

        $procurement_item = Arr::random(
            array: [
                Product::factory()->independent()->create(),
                Variant::factory()->create(),
            ]
        );

        return [
            'name' => fake()->words(nb:2, asText: true),
            'form' => $procurement_item_form,
            'form_quantity' => $procurement_item_form != 'singles'
                ? fake()->numberBetween(int1: 1, int2: 10)
                : null,
            'number_of_pieces_in_form' => null,
            'number_of_single_pieces' => $procurement_item_form === 'singles'
                ? fake()->numberBetween(int1: 1, int2: 50)
                : null,
            'measure' => fake()->numberBetween(int1: 250, int2: 750),
            'procurement_id' => Procurement::factory()->pending()->create(),
            'item_id' => $procurement_item->id,
            'added_to_store' => false,
            'type' => strtolower(class_basename($procurement_item)) === AllowedItemTypes::VARIANT->value
                ? AllowedItemTypes::VARIANT->value
                : AllowedItemTypes::PRODUCT->value,
        ];
    }

    public function singles(): static {
        return $this->state(
            fn (array $attributes): array => [
                'form' => ProcurementItemForms::singles()->label,
                'form_quantity' => null,
                'number_of_pieces_in_form' => null,
                'number_of_single_pieces' => fake()->numberBetween(int1: 1, int2: 48),
            ]
        );
    }


    public function crate_box_pack(): static {
        return $this->state(
            fn (array $attributes): array => [
                'form' => Arr::random(
                    array: ['crate', 'box', 'pack']
                ),
                'form_quantity' => fake()->numberBetween(int1: 1, int2: 10),
                'number_of_pieces_in_form' => null,
                'number_of_single_pieces' => null,
            ]
        );
    }

    public function added_to_store(): static {
        return $this->state(
            fn (array $attributes): array => [
                'added_to_store' => true,
            ]
        );
    }

    public function not_added_to_store(): static {
        return $this->state(
            fn (array $attributes): array => [
                'added_to_store' => false,
            ]
        );
    }
}
