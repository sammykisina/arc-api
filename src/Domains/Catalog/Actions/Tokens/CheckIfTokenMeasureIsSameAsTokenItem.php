<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Tokens;

use Domains\Catalog\Actions\GetItem;

class CheckIfTokenMeasureIsSameAsTokenItem {
    public static function handle(
        int $token_measure,
        int $item_id,
        string $item_type
    ): bool {
        $item = GetItem::handle(
            item_id: $item_id,
            item_type: $item_type
        );

        if ($item) {
            if ($item->measure !== $token_measure) {
                return false;
            }
        }

        return true;
    }
}
