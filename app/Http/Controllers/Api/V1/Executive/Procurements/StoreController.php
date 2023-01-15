<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Procurements;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Procurements\StoreRequest;
use App\Http\Resources\Executive\ProcurementResource;
use Domains\Catalog\Actions\Procurements\CheckIfSupplierIsActiveForProcurements;
use Domains\Catalog\Actions\Procurements\CheckIfSupplierSuppliesProcuredItem;
use Domains\Catalog\Actions\Procurements\CreateProcurement;
use Domains\Catalog\Factories\ProcurementFactory;
use Domains\Catalog\Models\Procurement;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
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
            item_id: $request->get(key: 'item_id'),
            type: $request->get(key: 'type'),
        )) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'The Procured Item is Not Supplied By The Selected Supplier.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($procurement = CreateProcurement::handle(
            procurementValueObject:ProcurementFactory::make(
                attributes:$request->validated()
            )
        )) {
            $procurement = Procurement::query()
                ->with('supplier', 'item')
                ->where('id', $procurement->id)
                ->first();

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Procurement Created Successfully.',
                    'procurement' => new ProcurementResource(
                        resource: $procurement
                    ),
                ],
                status: Http::CREATED()
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
