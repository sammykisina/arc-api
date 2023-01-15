<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Suppliers;

use App\Http\Controllers\Controller;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\Supplier;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(Supplier $supplier): JsonResponse {
        if (Procurement::query()
            ->where('supplier_id', $supplier->id)
            ->where('status', ProcurementStatus::pending()->label)->first()
        ) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Supplier Has A Pending Procurement.',
                ],
                status: Http::NOT_ACCEPTABLE()
            );
        }

        if ($supplier->delete()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Supplier Deleted Successfully.',
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
