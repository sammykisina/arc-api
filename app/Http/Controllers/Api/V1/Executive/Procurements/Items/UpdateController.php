<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Procurements\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Procurements\Items\UpdateRequest;
use Domains\Catalog\Actions\Procurements\UpdateProcurementItem;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, Procurement $procurement): JsonResponse {
        if ($procurement->status === ProcurementStatus::delivered()->label) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => "This Procurement Was Fulfilled, It's Item Cannot Be Updated.",
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if (UpdateProcurementItem::handle(
            attributes: $request->validated(),
            procurement: $procurement
        )) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Procurement Item Updated Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::ACCEPTED()
        );
    }
}
