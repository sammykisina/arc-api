<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Category\StoreRequest;
use App\Http\Resources\Executive\CategoryResource;
use Domains\Catalog\Actions\CreateCategory;
use Domains\Catalog\Factories\CategoryFactory;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        if ($category = CreateCategory::handle(CategoryFactory::make(attributes: $request->validated()))) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Category Created Successfully.',
                    'category' => new CategoryResource(
                        resource: $category
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
            status: Http::EXPECTATION_FAILED()
        );
    }
}
