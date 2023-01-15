<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Factories;

use Domains\Fulfillment\ValueObjects\TableValueObject;

class TableFactory {
    public static function make(array $attributes): TableValueObject {
        return new TableValueObject(
            name: $attributes['name'],
            number_of_seats: $attributes['number_of_seats'],
            extendable: $attributes['extendable'],
            number_of_extending_seats: $attributes['number_of_extending_seats'] ?? null
        );
    }
}
