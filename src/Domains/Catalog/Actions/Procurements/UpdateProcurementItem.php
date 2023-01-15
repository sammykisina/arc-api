<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Procurements;

use Domains\Catalog\Constants\ProcurementItemForms;
use Domains\Catalog\Models\Procurement;

class UpdateProcurementItem {
    public static function handle(array $attributes, Procurement $procurement): bool {
        return match ($attributes['form']) {
            ProcurementItemForms::singles()->label => UpdateProcurementItem::update(
                attributes: array_merge($attributes, ['form_quantity' => null]),
                procurement: $procurement
            ),

            ProcurementItemForms::crate()->label,
            ProcurementItemForms::box()->label,
            ProcurementItemForms::pack()->label => UpdateProcurementItem::update(
                attributes: array_merge($attributes, ['number_of_single_pieces' => null]),
                procurement: $procurement
            ),
        };
    }

    private static function update(array $attributes, Procurement $procurement): bool {
        if ($procurement->item()->update($attributes)) {
            // send Mail to Supplier

            return true;
        }
    }
}
