<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Procurements;

use App\Http\Controllers\Controller;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(Procurement $procurement): JsonResponse {
        if ($procurement->status === ProcurementStatus::pending()->label) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Cannot Delete a Pending Procurement.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if (! $procurement->item->added_to_store) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Cannot Delete a Pending Procurement.Items Not Added To Store.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($procurement->delete()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Procurement Deleted Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::NOT_IMPLEMENTED()
        );
    }
}
