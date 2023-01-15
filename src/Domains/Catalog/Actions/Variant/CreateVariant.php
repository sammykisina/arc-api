<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Variant;

use Domains\Catalog\Models\Variant;
use Domains\Catalog\ValueObjects\VariantValueObject;

class CreateVariant {
    public static function handle(VariantValueObject $variantValueObject): Variant {
        return Variant::create([
            'name' => $variantValueObject->name,
            'cost' => $variantValueObject->cost,
            'retail' => $variantValueObject->retail,
            'stock' => $variantValueObject->stock,
            'store' => $variantValueObject->stock,
            'measure' => $variantValueObject->measure,
            'vat' => $variantValueObject->vat,
            'product_id' => $variantValueObject->product_id,
        ]);
    }
}
