<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\Executive\ProductResource;
use Domains\Catalog\Models\Product;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $products = QueryBuilder::for(
            subject: Product::class
        )->allowedIncludes(
            includes: ['category', 'variants']
        )->latest()->get();

        return response()->json(
            data: ProductResource::collection(
                resource: $products
            ),
            status: Http::OK()
        );
    }
}
