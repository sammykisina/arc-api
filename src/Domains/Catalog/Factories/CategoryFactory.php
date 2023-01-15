<?php

declare(strict_types=1);

namespace Domains\Catalog\Factories;

use Domains\Catalog\ValueObjects\CategoryValueObject;

class CategoryFactory {
    public static function make(array $attributes): CategoryValueObject {
        return new CategoryValueObject(
            name: $attributes['name'],
            description: $attributes['description']
        );
    }
}
