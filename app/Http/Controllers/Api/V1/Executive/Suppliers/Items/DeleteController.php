<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Suppliers\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Suppliers\Items\DeleteRequest;
use Domains\Catalog\Models\Supplier;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(DeleteRequest $request, Supplier $supplier): JsonResponse {
        if ($request->get(key: 'type') === 'variant' && $supplier->variants()->detach($request->get(key: 'variant_id'))) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Supply Variant Deleted Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        if ($request->get(key: 'type') === 'product' && $supplier->products()->detach($request->get(key: 'product_id'))) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Supply Product Deleted Successfully.',
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
