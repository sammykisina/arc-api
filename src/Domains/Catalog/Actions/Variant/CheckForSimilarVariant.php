<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Variant;

use Domains\Catalog\Models\Variant;

class CheckForSimilarVariant {
    public static function handle(string $name, int $measure): bool {
        $variant = Variant::query()
          ->where(column: 'name', operator: $name)
          ->where(column: 'measure', operator: $measure)
          ->first();

        if ($variant) {
            return true;
        } else {
            return false;
        }
    }
}
