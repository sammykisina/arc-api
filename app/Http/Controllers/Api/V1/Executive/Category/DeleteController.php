<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Category;

use App\Http\Controllers\Controller;
use Domains\Catalog\Models\Category;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(Category $category): JsonResponse {
        if ($category->delete()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Category Deleted Successfully.',
                ],
                status:Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status:Http::NOT_IMPLEMENTED()
        );
    }
}
