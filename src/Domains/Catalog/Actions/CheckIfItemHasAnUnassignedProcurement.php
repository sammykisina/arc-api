<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions;

use Domains\Catalog\Models\ProcurementItem;

class CheckIfItemHasAnUnassignedProcurement {
    public static function handle(int $item_id, string $type): ?ProcurementItem {
        return ProcurementItem::query()
          ->where('item_id', $item_id)
          ->where('type', $type)
          ->where('added_to_store', false)->first();
    }
}
