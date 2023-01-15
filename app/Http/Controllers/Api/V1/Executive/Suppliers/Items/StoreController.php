<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Suppliers\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Suppliers\Items\StoreRequest;
use App\Http\Resources\Executive\SupplierResource;
use Domains\Catalog\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request, Supplier $supplier): JsonResponse {
        if (!$request->get(key: 'variants') && !$request->get(key: 'products')) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'You No Items To Add.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        try {
            DB::beginTransaction();

            if ($request->get(key: 'variants') && count($request->get(key: 'variants'))) {
                $supplier->variants()->sync($request->get(key: 'variants'));
            }

            if ($request->get(key: 'products') && count($request->get(key: 'products'))) {
                $supplier->products()->sync($request->get(key: 'products'));
            }

            DB::commit();
            $supplier = Supplier::query()
                ->with('variants.product', 'products')
                ->where('id', $supplier->id)->first();

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Supplier Items Created Successfully.',
                    'supplier' => new SupplierResource(
                        resource: $supplier
                    ),
                ],
                status: Http::CREATED()
            );
        } catch (\Throwable $th) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something Went Wrong.',
                ],
                status: Http::CREATED()
            );
        }
    }
}
