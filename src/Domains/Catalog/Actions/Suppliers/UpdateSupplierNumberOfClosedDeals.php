<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Suppliers;

use Domains\Catalog\Models\Supplier;

class UpdateSupplierNumberOfClosedDeals {
    public static function handle(int $supplier_id) {
        $supplier = Supplier::query()->where(column: 'id', operator: $supplier_id)->first();
        $supplier->update(['number_of_closed_deals' => ++$supplier->number_of_closed_deals]);
    }
}
