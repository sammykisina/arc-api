<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Procurements;

use Domains\Catalog\Constants\SuppliersStatus;
use Domains\Catalog\Models\Supplier;

class CheckIfSupplierIsActiveForProcurements {
    public static function handle(int $supplier_id): ?Supplier {
        return Supplier::query()
          ->where(column: 'id', operator: $supplier_id)
          ->where(column: 'status', operator: SuppliersStatus::active()->label)
          ->first();
    }
}
