<?php

declare(strict_types=1);

namespace Domains\Catalog\Constants;

enum AllowedItemTypes: string {
    case PRODUCT = 'product';
    case VARIANT = 'variant';

    public static function toArray(): array {
        return array_column(array: AllowedItemTypes::cases(), column_key:'value');
    }
}
