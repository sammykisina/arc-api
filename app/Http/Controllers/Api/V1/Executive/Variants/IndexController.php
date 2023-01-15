<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Variants;

use App\Http\Controllers\Controller;
use App\Http\Resources\Executive\VariantResource;
use Domains\Catalog\Models\Variant;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $variants = QueryBuilder::for(
            subject: Variant::class
        )->allowedIncludes(
            includes:['product']
        )->latest()->get();

        return response()->json(
            data:VariantResource::collection(
                resource: $variants
            ),
            status: Http::OK()
        );
    }
}
