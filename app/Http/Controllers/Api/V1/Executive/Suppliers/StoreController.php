<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Suppliers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Suppliers\StoreRequest;
use App\Http\Resources\Executive\SupplierResource;
use Domains\Catalog\Actions\Suppliers\CreateSupplier;
use Domains\Catalog\Factories\SupplierFactory;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        if ($supplier = CreateSupplier::handle(
            supplierValueObject: SupplierFactory::make(
                attributes: $request->validated()
            )
        )) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Supplier Created Successfully.',
                    'supplier' => new SupplierResource(
                        resource: $supplier
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
