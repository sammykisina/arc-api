<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Products\UpdateRequest;
use Domains\Catalog\Models\Product;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, Product $product): JsonResponse {
        if ($product->update($request->validated())) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Product Updated Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::NOT_MODIFIED()
        );
    }
}
