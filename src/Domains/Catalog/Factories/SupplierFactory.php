<?php

declare(strict_types=1);

namespace Domains\Catalog\Factories;

use Domains\Catalog\ValueObjects\SupplierValueObject;

class SupplierFactory {
    public static function make(array $attributes): SupplierValueObject {
        return new SupplierValueObject(
            name: $attributes['name'],
            location: $attributes['location'],
            phone_number: $attributes['phone_number'],
            email: $attributes['email']
        );
    }
}
