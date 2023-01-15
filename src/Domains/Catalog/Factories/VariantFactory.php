<?php

declare(strict_types=1);

namespace Domains\Catalog\Factories;

use Domains\Catalog\ValueObjects\VariantValueObject;

class VariantFactory {
    public static function make(array $attributes): VariantValueObject {
        return new VariantValueObject(
            name: $attributes['name'],
            cost: $attributes['cost'],
            retail: $attributes['retail'],
            stock: $attributes['stock'],
            measure: $attributes['measure'],
            product_id: $attributes['product_id'],
            vat: $attributes['vat']
        );
    }
}
