<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Tokens;

use Domains\Catalog\Actions\GetItem;
use Domains\Catalog\Models\Token;

class UpdateProductOrVariantStoreQuantity {
    public static function handle(Token $token): bool {
        $item = GetItem::handle(item_id: $token->item_id, item_type: $token->item_type);

        return $item->update(attributes: [
            'store' => $item->store + $token->number_of_single_pieces,
        ]);
    }
}
