<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Products;

use App\Http\Controllers\Controller;
use Domains\Catalog\Actions\CheckIfItemHasAnUnassignedProcurement;
use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Models\Product;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(Product $product): JsonResponse {
        if ($product->variants->count() > 0) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Delete Product Variants First.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($product->store > 0) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Cannot Delete a Product With Pieces Still In The Store.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if (CheckIfItemHasAnUnassignedProcurement::handle(item_id: $product->id, type: AllowedItemTypes::PRODUCT->value)) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Cannot Delete a Product With Unassigned Procurement.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($product->delete()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Product Deleted Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something When Wrong.',
            ],
            status: Http::NOT_IMPLEMENTED()
        );
    }
}
