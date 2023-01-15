<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Products\StoreRequest;
use App\Http\Resources\Executive\ProductResource;
use Domains\Catalog\Actions\CreateProduct;
use Domains\Catalog\Factories\ProductFactory;
use Domains\Catalog\Models\Product;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        $prevSimilarProduct = $request->get(key: 'form') === 'independent' ? Product::query()
            ->where('name', $request->get(key: 'name'))
            ->where('measure', $request->get(key: 'measure'))
            ->first()
            :
            Product::query()->where('name', $request->get(key: 'name'))->first();

        if ($prevSimilarProduct) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'A Similar Product Exists.Try Deleting It First or Creating A Variant Of The Product.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        $newProduct = CreateProduct::handle(
            ProductFactory::make(
                attributes: $request->validated()
            )
        );

        $product = Product::query()
            ->where('uuid', $newProduct->uuid)
            ->with('category', 'variants')
            ->first();

        if ($product) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Product Created Successfully.',
                    'product' => new ProductResource(
                        resource:$product
                    ),
                ],
                status: Http::CREATED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something went Wrong.',
            ],
            status: Http::EXPECTATION_FAILED()
        );
    }
}
