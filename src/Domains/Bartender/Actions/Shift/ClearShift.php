<?php

declare(strict_types=1);

namespace Domains\Bartender\Actions\Shift;

use Carbon\Carbon;
use Domains\Bartender\Models\Shift;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Illuminate\Support\Facades\DB;

class ClearShift {
    /**
     * clear a shift (make the active shift inactive) but after completing all the necessary tasks ie
     * 1. ensure no pending transactions
     * 2. ensure no unpaid bills or orders
     * 3.
     */
    public static function handle(): bool {
        /**
         * check if there is an active shift
         */
        $activeShift = Shift::query()
        ->where('active', true)
        // ->where('end_date', Carbon::today()->toDateString())
        ->first();

        /**
         * ensure no completed transactions and unpaid bills
         */
        if ($activeShift) {
            try {
                DB::beginTransaction();

                //  Todo::check completed transactions and unpaid bills

                /**
                 * clear shift
                 */
                $activeShift->update([
                    'active' => false,
                ]);

                /**
                 * set the workers on the shift as inactive
                 */
                $workers = $activeShift->workers;
                foreach ($workers as $worker) {
                    $worker->update([
                        'active' => false,
                    ]);
                }

                /**
                 * update the counter and the products
                 */
                $counterItems = $activeShift->counter->items;
                foreach ($counterItems as $counterItem) {
                    // find the item
                    $item = null;
                    if ($counterItem->form === 'Variant') {
                        $item = Variant::query()
                            ->where('id', $counterItem->product_id)
                            ->first();
                    } else {
                        $item = Product::query()
                            ->where('id', $counterItem->product_id)
                            ->first();
                    }

                    // update
                    $item->update([
                        'store' => ($item->store + ($counterItem->assigned - $counterItem->sold)),
                        'sold' => ($item->sold + $counterItem->sold),
                    ]);
                }

                /**
                 * send shift report to bartender and admin
                 */
                DB::commit();

                return true;
            } catch (\Exception $exception) {
                DB::rollBack();

                return false;
            }
        }
    }
}
