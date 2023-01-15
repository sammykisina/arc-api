<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Procurements;

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Models\Supplier;

class CheckIfSupplierSuppliesProcuredItem {
    public static function handle(int $supplier_id, int $item_id, string $type): bool {
        $supplier = Supplier::query()->where(column: 'id', operator: $supplier_id)->first();

        return $type === AllowedItemTypes::VARIANT->value
        ? $supplier->variants()->where('variant_id', $item_id)->exists()
        : $supplier->products()->where('product_id', $item_id)->exists();
    }
}
