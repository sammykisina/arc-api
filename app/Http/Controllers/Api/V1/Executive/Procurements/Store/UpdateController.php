<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Procurements\Store;

use App\Http\Controllers\Controller;
use Domains\Catalog\Actions\Procurements\UpdateProductOrVariantStoreQuantity;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(Procurement $procurement): JsonResponse {
        if ($procurement->status != ProcurementStatus::delivered()->label) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Cannot Update Procured Item pieces When The Procurement is Not Yet Delivered.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if (UpdateProductOrVariantStoreQuantity::handle(procurement: $procurement)) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Items Store Updated Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::EXPECTATION_FAILED()
        );
    }
}
