<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Suppliers;

use Domains\Catalog\Constants\SuppliersStatus;
use Domains\Catalog\Models\Supplier;
use Domains\Catalog\ValueObjects\SupplierValueObject;

class CreateSupplier {
    public static function handle(SupplierValueObject $supplierValueObject) {
        return  Supplier::create([
            'name' => $supplierValueObject->name,
            'location' => $supplierValueObject->location,
            'phone_number' => $supplierValueObject->phone_number,
            'email' => $supplierValueObject->email,
            'status' => SuppliersStatus::underreview()->label,
        ]);
    }
}
