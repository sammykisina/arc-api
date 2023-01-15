<?php

declare(strict_types=1);

namespace Domains\Catalog\Factories;

use Domains\Catalog\ValueObjects\TokenValueObject;

class TokenFactory {
    public static function make(array $attributes): TokenValueObject {
        return new TokenValueObject(
            name: $attributes['name'],
            number_of_single_pieces: $attributes['number_of_single_pieces'],
            measure: $attributes['measure'],
            owner: auth()->user()->role->name,
            item_id: array_key_exists('item_id', $attributes) ? $attributes['item_id'] : null,
            item_type: array_key_exists('item_type', $attributes) ? $attributes['item_type'] : null,
            approved: auth()->user()->role->slug === "super-admin" ? true : false
        );
    }
}
