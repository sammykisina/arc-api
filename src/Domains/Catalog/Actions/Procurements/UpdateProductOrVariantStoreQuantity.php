<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Procurements;

use Domains\Catalog\Actions\GetItem;
use Domains\Catalog\Constants\ProcurementItemForms;
use Domains\Catalog\Models\Procurement;

class UpdateProductOrVariantStoreQuantity {
    public static function handle(Procurement $procurement) {
        if ($item = GetItem::handle(item_id:$procurement->item->item_id, item_type:$procurement->item->type)) {
            if ($procurement->item->form != ProcurementItemForms::singles()->label) {
                return $item->update(attributes: [
                    'store' => $item->store + ($procurement->item->number_of_pieces_in_form * $procurement->item->form_quantity),
                ]);
            } else {
                return $item->update(attributes: [
                    'store' => $item->store + $procurement->item->number_of_single_pieces,
                ]);
            }
        }
    }
}
