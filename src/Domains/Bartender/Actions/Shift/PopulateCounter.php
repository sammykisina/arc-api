<?php

declare(strict_types=1);

namespace Domains\Bartender\Actions\Shift;

use Domains\Bartender\Models\CounterItem;
use Illuminate\Database\Eloquent\Model;

class PopulateCounter {
    public static function handle(int $counter_id, array $counterItems) {
        foreach ($counterItems as $counterItem) {
            CounterItem::create([
                'name' => $counterItem->form ? $counterItem->name : $counterItem->product->name.'_'.$counterItem->name,
                'assigned' => PopulateCounter::allocateQuantity(
                    counterItem:$counterItem,
                    defaultNeedProductQuantity:(int) config(key: 'arc.counter_default_product_quantity')
                ),
                'price' => $counterItem->retail,
                'product_id' => $counterItem->id,
                'form' => class_basename($counterItem),
                'counter_id' => $counter_id,
            ]);
        }
    }

    private static function allocateQuantity(Model $counterItem, int $defaultNeedProductQuantity): int {
        if ($counterItem->store >= $defaultNeedProductQuantity) {
            $counterItem->update([
                'store' => $counterItem->store - $defaultNeedProductQuantity,
            ]);

            return  (int) $defaultNeedProductQuantity;
        } else {
            $currentItemPiecesInStore = $counterItem->store;
            $counterItem->update([
                'store' => 0,
            ]);

            return (int) $currentItemPiecesInStore;
        }
    }
}
