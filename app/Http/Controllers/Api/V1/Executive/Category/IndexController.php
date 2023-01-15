<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\Executive\CategoryResource;
use Domains\Catalog\Models\Category;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $categories = QueryBuilder::for(
            subject: Category::class
        )->allowedIncludes(includes: ['products'])->latest()->get();

        return response()->json(
            data: CategoryResource::collection(
                resource: $categories
            ),
            status: Http::OK()
        );
    }
}
