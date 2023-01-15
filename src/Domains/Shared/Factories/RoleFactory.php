<?php

declare(strict_types=1);

namespace Domains\Shared\Factories;

use Domains\Shared\ValueObjects\RoleValueObject;

class RoleFactory {
    public static function make(array $attributes): RoleValueObject {
        return new RoleValueObject(
            name: $attributes['name'],
            slug: $attributes['slug']
        );
    }
}
