<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Variants;

use App\Http\Controllers\Controller;
use Domains\Catalog\Actions\CheckIfItemHasAnUnassignedProcurement;
use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Models\Variant;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(Variant $variant): JsonResponse {
        if ($variant->store > 0) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Cannot Delete a Product With Pieces Still In The Store.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if (CheckIfItemHasAnUnassignedProcurement::handle(item_id: $variant->id, type: AllowedItemTypes::VARIANT->value)) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Cannot Delete a Variant With Unassigned Procurement.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($variant->delete()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Variant Deleted Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'something went wrong',
            ],
            status: Http::EXPECTATION_FAILED()
        );
    }
}
