<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Shift;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shift\StoreRequest;
use App\Http\Resources\Admin\ShiftResource;
use Domains\Bartender\Actions\Shift\CreateShift;
use Domains\Bartender\Models\Shift;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        /**
         * check if there is an active shift
         */
        $activeShift = Shift::query()->where('active', true)->first();

        if ($activeShift) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'active shift detected.Contact the active bartender for further info.',
                ],
                // status: Http::NOT_IMPLEMENTED()
            );
        }

        /**
         * check if there are products to create a shift with
         */
        $productsWithoutVariants = Product::query()
            ->where('form', 'independent')
            ->where('store', '>', 0)
            ->get();
        $productVariants = Variant::query()
            ->with('product')
            ->where('store', '>', 0)
            ->get();

        $counterItems = array_merge($productsWithoutVariants->all(), $productVariants->all());
        if (count($counterItems) == 0) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Shift can not be created.All Products have no pieces in the store.',
                ],
            );
        }
        /**
         * create a new shift
         */
        $newShift = CreateShift::handle(
            user_id: $request->get(key: 'user_id'),
            creator: $request->get(key: 'creator'),
            waiters: $request->get(key: 'waiters'),
            counterItems: $counterItems
        );

        if ($newShift) {
            $shift = Shift::query()->where('uuid', $newShift->uuid)->with('workers.role')->first();

            /**
             * send a notification to all shift workers
             */

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'shift created',
                    'shift' => new ShiftResource(
                        resource: $shift
                    ),
                ],
                // status: Http::CREATED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'something when wrong',
            ],
        );
    }
}
