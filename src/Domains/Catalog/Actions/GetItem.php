<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions;

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Illuminate\Database\Eloquent\Model;

class GetItem {
    public static function handle(int $item_id, string $item_type): ?Model {
        return $item_type === AllowedItemTypes::VARIANT->value
          ? Variant::query()->where('id', $item_id)->first()
          : Product::query()->where('id', $item_id)->first();
    }
}
