<?php

declare(strict_types=1);

namespace Domains\Catalog\Factories;

use Domains\Catalog\ValueObjects\ProcurementValueObject;

class ProcurementFactory {
    public static function make(array $attributes): ProcurementValueObject {
        return new ProcurementValueObject(
            supplier_id: $attributes['supplier_id'],
            type: $attributes['type'],
            item_id: $attributes['item_id'],
            form: $attributes['procurement_details']['form'],
            form_quantity: array_key_exists(key: 'form_quantity', array: $attributes['procurement_details'])
              ? $attributes['procurement_details']['form_quantity']
              : null,
            number_of_single_pieces: array_key_exists(key: 'number_of_single_pieces', array: $attributes['procurement_details'])
                ? $attributes['procurement_details']['number_of_single_pieces']
                : null,
            measure: $attributes['procurement_details']['measure'],
        );
    }
}
