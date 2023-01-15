<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Procurements;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Procurements\UpdateRequest;
use Domains\Catalog\Actions\Procurements\CheckIfSupplierIsActiveForProcurements;
use Domains\Catalog\Actions\Procurements\CheckIfSupplierSuppliesProcuredItem;
use Domains\Catalog\Actions\UpdateProcurement;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, Procurement $procurement): JsonResponse {
        if ($procurement->status != ProcurementStatus::pending()->label) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'This Procurement is '.$procurement->status.'. Details Cannot Be Updated.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($request->get(key: 'supplier_id')) {
            if (! CheckIfSupplierIsActiveForProcurements::handle(supplier_id: $request->get(key: 'supplier_id'))) {
                return response()->json(
                    data: [
                        'error' => 1,
                        'message' => 'Supplier is Not Active For Procurements.',
                    ],
                    status: Http::UNPROCESSABLE_ENTITY()
                );
            }

            if (! CheckIfSupplierSuppliesProcuredItem::handle(
                supplier_id: $request->get(key: 'supplier_id'),
                item_id:$procurement->item->id,
                type:$procurement->item->type,
            )) {
                return response()->json(
                    data: [
                        'error' => 1,
                        'message' => 'The Procured Item is Not Supplied By The Selected Supplier.',
                    ],
                    status: Http::UNPROCESSABLE_ENTITY()
                );
            }
        }

        $update_state = UpdateProcurement::handle(
            attributes: $request->validated(),
            procurement: $procurement,
        );

        return match ($update_state) {
            true => response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Procurement Updated Successfully.',
                ],
                status: Http::ACCEPTED()
            ),

            false => response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something Went Wrong.',
                ],
                status: Http::NOT_IMPLEMENTED()
            ),
        };
    }
}
