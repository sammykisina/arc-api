<?php

declare(strict_types=1);

namespace Domains\Catalog\Factories;

use Domains\Catalog\ValueObjects\ProductValueObject;

class ProductFactory {
    public static function make(array $attributes): ProductValueObject {
        return new ProductValueObject(
            name: $attributes['name'],
            cost: $attributes['cost'] ?? null,
            retail: $attributes['retail'] ?? null,
            stock:$attributes['stock'] ?? null,
            measure: $attributes['measure'] ?? null,
            category_id: $attributes['category_id'],
            form: $attributes['form'],
            vat: $attributes['vat'] ?? null
        );
    }
}
