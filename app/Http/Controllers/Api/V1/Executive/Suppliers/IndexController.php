<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Suppliers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Executive\SupplierResource;
use Domains\Catalog\Models\Supplier;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $suppliers = QueryBuilder::for(
            subject: Supplier::class
        )->allowedIncludes(
            includes: ['variants.product', 'products']
        )->latest()->get();

        return response()->json(
            data: SupplierResource::collection(
                resource: $suppliers
            ),
            status: Http::OK()
        );
    }
}
