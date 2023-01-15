<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Variants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Variants\UpdateRequest;
use Domains\Catalog\Actions\Variant\CheckForSimilarVariant;
use Domains\Catalog\Models\Variant;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, Variant $variant): JsonResponse {
        if (
            $request->get(key: 'name')
            && $request->get(key: 'measure')
            && CheckForSimilarVariant::handle(name:$request->get(key: 'name'), measure:$request->get(key: 'measure'))
        ) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'A Similar Variant Exists.',
                ],
                status: Http::NOT_ACCEPTABLE()
            );
        }

        if ($request->get(key: 'measure') && CheckForSimilarVariant::handle(name:$variant->name, measure:$request->get(key: 'measure'))) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'A Similar Variant Exists.',
                ],
                status: Http::NOT_ACCEPTABLE()
            );
        }

        if ($variant->update($request->validated())) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Variant Updated Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'something went wrong',
            ],
            // status: Http::NOT_MODIFIED()
        );
    }
}
