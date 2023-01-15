<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Category\UpdateRequest;
use Domains\Catalog\Models\Category;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, Category $category) {
        if ($category->update($request->validated())) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Category Updated Successfully.',
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
